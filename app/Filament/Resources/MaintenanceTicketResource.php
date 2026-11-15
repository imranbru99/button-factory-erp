<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceTicketResource\Pages;
use App\Filament\Resources\MaintenanceTicketResource\RelationManagers;
use App\Models\MaintenanceTicket;
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

class MaintenanceTicketResource extends Resource
{
    protected static ?string $model = MaintenanceTicket::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static string|\UnitEnum|null $navigationGroup = 'Maintenance';

    protected static ?string $recordTitleAttribute = 'number';

    public static function canViewAny(): bool
    {
        return (bool) config('factory.modules.maintenance') && parent::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                TextInput::make('number')->maxLength(255),
                Select::make('machine_id')->label('Machine')->relationship('machine', 'name')->searchable()->preload()->required(),
                TextInput::make('priority')->required()->maxLength(255),
                TextInput::make('status')->required()->maxLength(255),
                TextInput::make('title')->required()->maxLength(255),
                Textarea::make('description')->columnSpanFull(),
                Textarea::make('suggested_solution')->columnSpanFull(),
                TextInput::make('source')->maxLength(255),
                Select::make('opened_by')->label('Opener')->relationship('opener', 'name')->searchable()->preload(),
                Select::make('assigned_to')->label('Assignee')->relationship('assignee', 'name')->searchable()->preload(),
                DateTimePicker::make('resolved_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')->searchable()->sortable(),
                TextColumn::make('machine.name')->label('machine'),
                TextColumn::make('priority')->searchable()->sortable(),
                TextColumn::make('status')->searchable()->sortable(),
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('description'),
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
            'index' => Pages\ManageMaintenanceTickets::route('/'),
        ];
    }
}
