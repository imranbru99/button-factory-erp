<?php

namespace App\Filament\Resources\GoodsReceiptResource\Pages;

use App\Filament\Resources\GoodsReceiptResource;
use App\Services\Procurement\GoodsReceiptService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditGoodsReceipt extends EditRecord
{
    protected static string $resource = GoodsReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('ocr')
                ->label('Read supplier invoice')
                ->visible(fn () => filled($this->record->invoice_path))
                ->action(function (GoodsReceiptService $receipts) {
                    $this->record->update([
                        'ocr_payload' => $receipts->extractInvoice($this->record->invoice_path, $this->record),
                    ]);
                    Notification::make()->title('Invoice text stored on the receipt')->success()->send();
                }),
            DeleteAction::make(),
        ];
    }
}
