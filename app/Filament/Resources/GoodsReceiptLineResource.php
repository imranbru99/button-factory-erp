<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GoodsReceiptLineResource\Pages;
use App\Filament\Resources\GoodsReceiptLineResource\RelationManagers;
use App\Models\GoodsReceiptLine;
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

class GoodsReceiptLineResource extends Resource
{
    protected static ?string $model = GoodsReceiptLine::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-minus';

    protected static string|\UnitEnum|null $navigationGroup = 'Procurement';

    protected static ?string $recordTitleAttribute = 'goods_receipt_id';

    public static function canViewAny(): bool
    {
        return (bool) config('factory.modules.procurement') && parent::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                Select::make('goods_receipt_id')->label('Goodsreceipt')->relationship('goodsReceipt', 'number')->searchable()->preload()->required(),
                Select::make('purchase_order_line_id')->label('Purchaseorderline')->relationship('purchaseOrderLine', 'id')->searchable()->preload(),
                Select::make('material_id')->label('Material')->relationship('material', 'name')->searchable()->preload()->required(),
                Select::make('lot_id')->label('Lot')->relationship('lot', 'lot_number')->searchable()->preload(),
                TextInput::make('qty')->numeric()->required(),
                TextInput::make('unit_cost')->numeric()->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('goodsReceipt.number')->label('goodsReceipt'),
                TextColumn::make('purchaseOrderLine.id')->label('purchaseOrderLine'),
                TextColumn::make('material.name')->label('material'),
                TextColumn::make('lot.lot_number')->label('lot'),
                TextColumn::make('qty'),
                TextColumn::make('unit_cost'),
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
            'index' => Pages\ManageGoodsReceiptLines::route('/'),
        ];
    }
}
