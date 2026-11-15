<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Services\Catalog\CatalogCopyService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('catalogCopy')
                ->label('Write catalog copy')
                ->action(function (CatalogCopyService $catalog) {
                    $catalog->generate($this->record);
                    Notification::make()->title('Catalog copy saved')->success()->send();
                }),
            DeleteAction::make(),
        ];
    }
}
