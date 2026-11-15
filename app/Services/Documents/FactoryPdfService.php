<?php

namespace App\Services\Documents;

use App\Models\Delivery;
use App\Models\Inspection;
use App\Models\Invoice;
use ImranDev\UnicodePdf\Facades\UnicodePdf;
use Symfony\Component\HttpFoundation\Response;
use Tarikh\Tarikh;

class FactoryPdfService
{
    public function invoice(Invoice $invoice): Response
    {
        $invoice->load('customer', 'lines', 'salesOrder');

        return $this->download('documents.invoice', [
            'title' => 'Invoice '.$invoice->number,
            'document' => $invoice,
            'printed' => Tarikh::now(config('factory.timezone'))->format('d M Y H:i'),
        ], $invoice->number.'.pdf');
    }

    public function packingList(Delivery $delivery): Response
    {
        $delivery->load('salesOrder.customer', 'lines.product');

        return $this->download('documents.packing-list', [
            'title' => 'Packing list '.$delivery->number,
            'document' => $delivery,
            'printed' => Tarikh::now(config('factory.timezone'))->format('d M Y H:i'),
        ], $delivery->number.'.pdf');
    }

    public function certificate(Inspection $inspection): Response
    {
        $inspection->load('defects', 'inspector');

        return $this->download('documents.certificate', [
            'title' => 'Quality certificate '.$inspection->number,
            'document' => $inspection,
            'printed' => Tarikh::now(config('factory.timezone'))->format('d M Y H:i'),
        ], $inspection->number.'.pdf');
    }

    /** @param array<string, mixed> $data */
    private function download(string $view, array $data, string $filename): Response
    {
        return UnicodePdf::loadView($view, $data)
            ->locale(app()->getLocale())
            ->paper('a4')
            ->title($data['title'])
            ->name($filename)
            ->download($filename);
    }
}
