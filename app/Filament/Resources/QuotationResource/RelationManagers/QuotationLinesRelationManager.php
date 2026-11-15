<?php

namespace App\Filament\Resources\QuotationResource\RelationManagers;

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

class QuotationLinesRelationManager extends RelationManager
{
    protected static string $relationship = 'lines';

    public function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                Select::make('product_id')->label('Product')->relationship('product', 'name')->searchable()->preload()->required(),
                TextInput::make('qty')->numeric()->required(),
                TextInput::make('unit_price')->numeric()->required(),
                TextInput::make('finish')->maxLength(255),
                TextInput::make('size_ligne')->numeric(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('quotation.number')->label('quotation'),
                TextColumn::make('product.name')->label('product'),
                TextColumn::make('qty')->searchable()->sortable(),
                TextColumn::make('unit_price'),
                TextColumn::make('finish')->searchable()->sortable(),
                TextColumn::make('size_ligne')->searchable()->sortable(),
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
