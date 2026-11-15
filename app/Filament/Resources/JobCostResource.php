<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobCostResource\Pages;
use App\Filament\Resources\JobCostResource\RelationManagers;
use App\Models\JobCost;
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

class JobCostResource extends Resource
{
    protected static ?string $model = JobCost::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calculator';

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    protected static ?string $recordTitleAttribute = 'work_order_id';

    public static function canViewAny(): bool
    {
        return (bool) config('factory.modules.finance') && parent::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                Select::make('work_order_id')->label('Workorder')->relationship('workOrder', 'number')->searchable()->preload()->required(),
                TextInput::make('material_cost')->numeric(),
                TextInput::make('labor_cost')->numeric(),
                TextInput::make('machine_cost')->numeric(),
                TextInput::make('overhead_cost')->numeric(),
                TextInput::make('total_cost')->numeric(),
                TextInput::make('unit_cost')->numeric(),
                DateTimePicker::make('calculated_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('workOrder.number')->label('workOrder'),
                TextColumn::make('material_cost'),
                TextColumn::make('labor_cost'),
                TextColumn::make('machine_cost'),
                TextColumn::make('overhead_cost'),
                TextColumn::make('total_cost'),
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
            'index' => Pages\ManageJobCosts::route('/'),
        ];
    }
}
