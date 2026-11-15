<?php

namespace App\Filament\Resources\WorkOrderResource\RelationManagers;

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

class WorkOrderStagesRelationManager extends RelationManager
{
    protected static string $relationship = 'stages';

    public function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                TextInput::make('code')->required()->maxLength(255),
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('sequence')->numeric()->required(),
                TextInput::make('status')->required()->maxLength(255),
                Select::make('operator_id')->label('Operator')->relationship('operator', 'name')->searchable()->preload(),
                Select::make('machine_id')->label('Machine')->relationship('machine', 'name')->searchable()->preload(),
                DateTimePicker::make('started_at'),
                DateTimePicker::make('ended_at'),
                TextInput::make('expected_minutes')->numeric(),
                TextInput::make('qty_in')->numeric(),
                TextInput::make('qty_out')->numeric(),
                TextInput::make('qty_scrap')->numeric(),
                Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('workOrder.number')->label('workOrder'),
                TextColumn::make('code')->searchable()->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('sequence')->searchable()->sortable(),
                TextColumn::make('status')->searchable()->sortable(),
                TextColumn::make('operator.name')->label('operator'),
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
