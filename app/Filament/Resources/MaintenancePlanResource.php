<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenancePlanResource\Pages;
use App\Filament\Resources\MaintenancePlanResource\RelationManagers;
use App\Models\MaintenancePlan;
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

class MaintenancePlanResource extends Resource
{
    protected static ?string $model = MaintenancePlan::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static string|\UnitEnum|null $navigationGroup = 'Maintenance';

    protected static ?string $recordTitleAttribute = 'machine_id';

    public static function canViewAny(): bool
    {
        return (bool) config('factory.modules.maintenance') && parent::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                Select::make('machine_id')->label('Machine')->relationship('machine', 'name')->searchable()->preload()->required(),
                TextInput::make('task')->required()->maxLength(255),
                TextInput::make('interval_days')->numeric()->required(),
                DatePicker::make('last_done_on'),
                DatePicker::make('next_due_on')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('machine.name')->label('machine'),
                TextColumn::make('task')->searchable()->sortable(),
                TextColumn::make('interval_days')->searchable()->sortable(),
                TextColumn::make('last_done_on'),
                TextColumn::make('next_due_on'),
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
            'index' => Pages\ManageMaintenancePlans::route('/'),
        ];
    }
}
