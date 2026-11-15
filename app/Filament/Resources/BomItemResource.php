<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BomItemResource\Pages;
use App\Filament\Resources\BomItemResource\RelationManagers;
use App\Models\BomItem;
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

class BomItemResource extends Resource
{
    protected static ?string $model = BomItem::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-list-bullet';

    protected static string|\UnitEnum|null $navigationGroup = 'Catalog';

    protected static ?string $recordTitleAttribute = 'bom_id';

    public static function canViewAny(): bool
    {
        return (bool) config('factory.modules.catalog') && parent::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                Select::make('bom_id')->label('Bom')->relationship('bom', 'id')->searchable()->preload()->required(),
                Select::make('parent_id')->label('Parent')->relationship('parent', 'id')->searchable()->preload(),
                TextInput::make('component_type')->required()->maxLength(255),
                Select::make('material_id')->label('Material')->relationship('material', 'name')->searchable()->preload(),
                Select::make('component_product_id')->label('Componentproduct')->relationship('componentProduct', 'name')->searchable()->preload(),
                TextInput::make('quantity')->numeric()->required(),
                TextInput::make('uom')->required()->maxLength(255),
                TextInput::make('scrap_factor')->numeric(),
                TextInput::make('consume_at_stage')->maxLength(255),
                TextInput::make('level')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bom.id')->label('bom'),
                TextColumn::make('parent.id')->label('parent'),
                TextColumn::make('component_type')->searchable()->sortable(),
                TextColumn::make('material.name')->label('material'),
                TextColumn::make('componentProduct.name')->label('componentProduct'),
                TextColumn::make('quantity'),
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
            'index' => Pages\ManageBomItems::route('/'),
        ];
    }
}
