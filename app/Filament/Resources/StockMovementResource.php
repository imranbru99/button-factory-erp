<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockMovementResource\Pages;
use App\Filament\Resources\StockMovementResource\RelationManagers;
use App\Models\StockMovement;
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

class StockMovementResource extends Resource
{
    protected static ?string $model = StockMovement::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static string|\UnitEnum|null $navigationGroup = 'Inventory';

    protected static ?string $recordTitleAttribute = 'material_id';

    public static function canViewAny(): bool
    {
        return (bool) config('factory.modules.inventory') && parent::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                Select::make('material_id')->label('Material')->relationship('material', 'name')->searchable()->preload()->required(),
                Select::make('lot_id')->label('Lot')->relationship('lot', 'lot_number')->searchable()->preload(),
                Select::make('warehouse_id')->label('Warehouse')->relationship('warehouse', 'name')->searchable()->preload()->required(),
                TextInput::make('type')->required()->maxLength(255),
                TextInput::make('qty')->numeric()->required(),
                TextInput::make('unit_cost')->numeric()->required(),
                TextInput::make('reference_type')->maxLength(255),
                TextInput::make('reference_id')->numeric(),
                Textarea::make('notes')->columnSpanFull(),
                Select::make('user_id')->label('User')->relationship('user', 'name')->searchable()->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('material.name')->label('material'),
                TextColumn::make('lot.lot_number')->label('lot'),
                TextColumn::make('warehouse.name')->label('warehouse'),
                TextColumn::make('type')->searchable()->sortable(),
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
            'index' => Pages\ManageStockMovements::route('/'),
        ];
    }
}
