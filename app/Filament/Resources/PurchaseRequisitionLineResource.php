<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseRequisitionLineResource\Pages;
use App\Filament\Resources\PurchaseRequisitionLineResource\RelationManagers;
use App\Models\PurchaseRequisitionLine;
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

class PurchaseRequisitionLineResource extends Resource
{
    protected static ?string $model = PurchaseRequisitionLine::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-minus';

    protected static string|\UnitEnum|null $navigationGroup = 'Procurement';

    protected static ?string $recordTitleAttribute = 'purchase_requisition_id';

    public static function canViewAny(): bool
    {
        return (bool) config('factory.modules.procurement') && parent::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                Select::make('purchase_requisition_id')->label('Requisition')->relationship('requisition', 'number')->searchable()->preload()->required(),
                Select::make('material_id')->label('Material')->relationship('material', 'name')->searchable()->preload()->required(),
                TextInput::make('qty')->numeric()->required(),
                TextInput::make('estimated_cost')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('requisition.number')->label('requisition'),
                TextColumn::make('material.name')->label('material'),
                TextColumn::make('qty'),
                TextColumn::make('estimated_cost'),
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
            'index' => Pages\ManagePurchaseRequisitionLines::route('/'),
        ];
    }
}
