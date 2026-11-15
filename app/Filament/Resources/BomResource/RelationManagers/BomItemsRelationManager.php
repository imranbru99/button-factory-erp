<?php

namespace App\Filament\Resources\BomResource\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
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
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BomItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
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

    public function table(Table $table): Table
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
            ->headerActions([
                CreateAction::make(),
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
}
