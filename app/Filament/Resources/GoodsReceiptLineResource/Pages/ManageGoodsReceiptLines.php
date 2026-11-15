<?php

namespace App\Filament\Resources\GoodsReceiptLineResource\Pages;

use App\Filament\Resources\GoodsReceiptLineResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageGoodsReceiptLines extends ManageRecords
{
    protected static string $resource = GoodsReceiptLineResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
