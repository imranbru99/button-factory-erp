<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductStockResource\Pages;
use App\Filament\Resources\ProductStockResource\RelationManagers;
use App\Models\ProductStock;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductStockResource extends Resource
{
    protected static ?string $model = ProductStock::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cube';

    protected static string|\UnitEnum|null $navigationGroup = 'Inventory';

    protected static ?string $recordTitleAttribute = 'product_id';

    public static function canViewAny(): bool
    {
        return (bool) config('factory.modules.inventory') && parent::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                Select::make('product_id')->label('Product')->relationship('product', 'name')->searchable()->preload()->required(),
                Select::make('warehouse_id')->label('Warehouse')->relationship('warehouse', 'name')->searchable()->preload()->required(),
                TextInput::make('qty')->numeric()->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')->label('product'),
                TextColumn::make('warehouse.name')->label('warehouse'),
                TextColumn::make('qty'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProductStocks::route('/'),
        ];
    }
}
