<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MachineRuntimeResource\Pages;
use App\Filament\Resources\MachineRuntimeResource\RelationManagers;
use App\Models\MachineRuntime;
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

class MachineRuntimeResource extends Resource
{
    protected static ?string $model = MachineRuntime::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

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
                Select::make('work_order_id')->label('Workorder')->relationship('workOrder', 'number')->searchable()->preload(),
                DateTimePicker::make('started_at')->required(),
                DateTimePicker::make('ended_at'),
                TextInput::make('planned_minutes')->numeric(),
                TextInput::make('downtime_minutes')->numeric(),
                TextInput::make('good_qty')->numeric(),
                TextInput::make('total_qty')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('machine.name')->label('machine'),
                TextColumn::make('workOrder.number')->label('workOrder'),
                TextColumn::make('started_at'),
                TextColumn::make('ended_at'),
                TextColumn::make('planned_minutes'),
                TextColumn::make('downtime_minutes'),
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
            'index' => Pages\ManageMachineRuntimes::route('/'),
        ];
    }
}
