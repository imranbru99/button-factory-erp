<?php

namespace App\Filament\Resources\NcrResource\RelationManagers;

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

class CapasRelationManager extends RelationManager
{
    protected static string $relationship = 'capas';

    public function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                Textarea::make('action')->columnSpanFull()->required(),
                Select::make('owner_id')->label('Owner')->relationship('owner', 'name')->searchable()->preload(),
                DatePicker::make('due_on'),
                TextInput::make('status')->required()->maxLength(255),
                Textarea::make('effectiveness_notes')->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ncr.number')->label('ncr'),
                TextColumn::make('action'),
                TextColumn::make('owner.name')->label('owner'),
                TextColumn::make('due_on'),
                TextColumn::make('status')->searchable()->sortable(),
                TextColumn::make('effectiveness_notes'),
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
