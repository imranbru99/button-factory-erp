<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LotResource\Pages;
use App\Filament\Resources\LotResource\RelationManagers;
use App\Models\Lot;
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

class LotResource extends Resource
{
    protected static ?string $model = Lot::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-archive-box';

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
                Select::make('warehouse_id')->label('Warehouse')->relationship('warehouse', 'name')->searchable()->preload()->required(),
                Select::make('bin_id')->label('Bin')->relationship('bin', 'name')->searchable()->preload(),
                Select::make('supplier_id')->label('Supplier')->relationship('supplier', 'name')->searchable()->preload(),
                TextInput::make('lot_number')->required()->maxLength(255),
                TextInput::make('qty_on_hand')->numeric()->required(),
                TextInput::make('unit_cost')->numeric()->required(),
                DateTimePicker::make('received_at'),
                DatePicker::make('expires_at'),
                TextInput::make('status')->required()->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('material.name')->label('material'),
                TextColumn::make('warehouse.name')->label('warehouse'),
                TextColumn::make('bin.name')->label('bin'),
                TextColumn::make('supplier.name')->label('supplier'),
                TextColumn::make('lot_number')->searchable()->sortable(),
                TextColumn::make('qty_on_hand'),
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
            'index' => Pages\ManageLots::route('/'),
        ];
    }
}
