<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkOrderStageResource\Pages;
use App\Filament\Resources\WorkOrderStageResource\RelationManagers;
use App\Models\WorkOrderStage;
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

class WorkOrderStageResource extends Resource
{
    protected static ?string $model = WorkOrderStage::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-forward';

    protected static string|\UnitEnum|null $navigationGroup = 'Production';

    protected static ?string $recordTitleAttribute = 'name';

    public static function canViewAny(): bool
    {
        return (bool) config('factory.modules.shopfloor') && parent::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                Select::make('work_order_id')->label('Workorder')->relationship('workOrder', 'number')->searchable()->preload()->required(),
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

    public static function table(Table $table): Table
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
            'index' => Pages\ManageWorkOrderStages::route('/'),
        ];
    }
}
