<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GoodsReceiptResource\Pages;
use App\Filament\Resources\GoodsReceiptResource\RelationManagers;
use App\Models\GoodsReceipt;
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

class GoodsReceiptResource extends Resource
{
    protected static ?string $model = GoodsReceipt::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-archive-box-arrow-down';

    protected static string|\UnitEnum|null $navigationGroup = 'Procurement';

    protected static ?string $recordTitleAttribute = 'number';

    public static function canViewAny(): bool
    {
        return (bool) config('factory.modules.procurement') && parent::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                TextInput::make('number')->maxLength(255),
                Select::make('purchase_order_id')->label('Purchaseorder')->relationship('purchaseOrder', 'number')->searchable()->preload()->required(),
                Select::make('warehouse_id')->label('Warehouse')->relationship('warehouse', 'name')->searchable()->preload()->required(),
                DatePicker::make('received_on')->required(),
                TextInput::make('invoice_path')->maxLength(255),
                KeyValue::make('ocr_payload')->columnSpanFull(),
                Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')->searchable()->sortable(),
                TextColumn::make('purchaseOrder.number')->label('purchaseOrder'),
                TextColumn::make('warehouse.name')->label('warehouse'),
                TextColumn::make('received_on'),
                TextColumn::make('invoice_path')->searchable()->sortable(),
                TextColumn::make('ocr_payload'),
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

    public static function getRelations(): array
    {
        return [
            RelationManagers\GoodsReceiptLinesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGoodsReceipts::route('/'),
            'create' => Pages\CreateGoodsReceipt::route('/create'),
            'edit' => Pages\EditGoodsReceipt::route('/{record}/edit'),
        ];
    }
}
