<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkOrderResource\Pages;
use App\Filament\Resources\WorkOrderResource\RelationManagers;
use App\Models\WorkOrder;
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

class WorkOrderResource extends Resource
{
    protected static ?string $model = WorkOrder::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-wrench';

    protected static string|\UnitEnum|null $navigationGroup = 'Production';

    protected static ?string $recordTitleAttribute = 'number';

    public static function canViewAny(): bool
    {
        return (bool) config('factory.modules.shopfloor') && parent::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                TextInput::make('number')->maxLength(255),
                Select::make('product_id')->label('Product')->relationship('product', 'name')->searchable()->preload()->required(),
                Select::make('sales_order_line_id')->label('Salesorderline')->relationship('salesOrderLine', 'id')->searchable()->preload(),
                Select::make('bom_id')->label('Bom')->relationship('bom', 'id')->searchable()->preload(),
                Select::make('formula_id')->label('Formula')->relationship('formula', 'name')->searchable()->preload(),
                Select::make('parent_work_order_id')->label('Parent')->relationship('parent', 'number')->searchable()->preload(),
                Toggle::make('is_rework'),
                TextInput::make('qty_planned')->numeric()->required(),
                TextInput::make('qty_completed')->numeric(),
                TextInput::make('qty_scrapped')->numeric(),
                TextInput::make('status')->required()->maxLength(255),
                TextInput::make('strategy')->required()->maxLength(255),
                TextInput::make('priority')->numeric(),
                DateTimePicker::make('scheduled_start'),
                DateTimePicker::make('scheduled_end'),
                DateTimePicker::make('released_at'),
                DateTimePicker::make('completed_at'),
                Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')->searchable()->sortable(),
                TextColumn::make('product.name')->label('product'),
                TextColumn::make('salesOrderLine.id')->label('salesOrderLine'),
                TextColumn::make('bom.id')->label('bom'),
                TextColumn::make('formula.name')->label('formula'),
                TextColumn::make('parent.number')->label('parent'),
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

    public static function getRelations(): array
    {
        return [
            RelationManagers\WorkOrderStagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkOrders::route('/'),
            'create' => Pages\CreateWorkOrder::route('/create'),
            'edit' => Pages\EditWorkOrder::route('/{record}/edit'),
        ];
    }
}
