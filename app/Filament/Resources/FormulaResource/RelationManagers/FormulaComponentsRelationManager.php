<?php

namespace App\Filament\Resources\FormulaResource\RelationManagers;

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

class FormulaComponentsRelationManager extends RelationManager
{
    protected static string $relationship = 'components';

    public function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                Select::make('material_id')->label('Material')->relationship('material', 'name')->searchable()->preload()->required(),
                TextInput::make('role')->required()->maxLength(255),
                TextInput::make('percentage')->numeric()->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('formula.name')->label('formula'),
                TextColumn::make('material.name')->label('material'),
                TextColumn::make('role')->searchable()->sortable(),
                TextColumn::make('percentage'),
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
