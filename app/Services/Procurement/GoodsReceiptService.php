<?php

namespace App\Services\Procurement;

use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptLine;
use App\Models\PurchaseOrder;
use App\Services\Inventory\StockService;
use ImranDevBd\AiOcr\Facades\AiOcr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GoodsReceiptService
{
    public function __construct(private readonly StockService $stock) {}

    /** @param array<int, array{purchase_order_line_id: int, qty: float, unit_cost: float, lot_number: string}> $lines */
    public function receive(PurchaseOrder $order, int $warehouseId, array $lines, ?string $invoicePath = null): GoodsReceipt
    {
        return DB::transaction(function () use ($order, $warehouseId, $lines, $invoicePath) {
            $receipt = GoodsReceipt::query()->create([
                'purchase_order_id' => $order->id,
                'warehouse_id' => $warehouseId,
                'received_on' => now()->toDateString(),
                'invoice_path' => $invoicePath,
            ]);

            $warehouse = $receipt->warehouse;

            foreach ($lines as $line) {
                $poLine = $order->lines()->whereKey($line['purchase_order_line_id'])->firstOrFail();
                $lot = $this->stock->receive(
                    $poLine->material,
                    $warehouse,
                    $line['lot_number'],
                    (float) $line['qty'],
                    (float) $line['unit_cost'],
                    $receipt,
                    $order->supplier,
                );

                GoodsReceiptLine::query()->create([
                    'goods_receipt_id' => $receipt->id,
                    'purchase_order_line_id' => $poLine->id,
                    'material_id' => $poLine->material_id,
                    'lot_id' => $lot->id,
                    'qty' => $line['qty'],
                    'unit_cost' => $line['unit_cost'],
                ]);

                $poLine->qty_received = (float) $poLine->qty_received + (float) $line['qty'];
                $poLine->save();
            }

            $order->load('lines');
            $open = $order->lines->contains(fn ($line) => (float) $line->qty_received + 0.0001 < (float) $line->qty);
            $order->update(['status' => $open ? 'partial' : 'received']);

            if ($invoicePath) {
                $receipt->update(['ocr_payload' => $this->extractInvoice($invoicePath, $receipt)]);
            }

            return $receipt->load('lines');
        });
    }

    /** @return array<string, mixed> */
    public function extractInvoice(string $path, GoodsReceipt $receipt): array
    {
        try {
            $absolute = Storage::disk('public')->exists($path)
                ? Storage::disk('public')->path($path)
                : (is_file($path) ? $path : null);

            if ($absolute === null) {
                return ['status' => 'missing_file'];
            }

            $result = AiOcr::file($absolute)->ownedBy($receipt)->extract();

            return [
                'status' => $result->failed ? 'failed' : 'extracted',
                'text' => $result->text(),
                'data' => $result->toArray(),
            ];
        } catch (Throwable $exception) {
            return ['status' => 'unavailable', 'message' => $exception->getMessage()];
        }
    }
}
