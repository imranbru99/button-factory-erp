<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StageIssueResource\Pages;
use App\Filament\Resources\StageIssueResource\RelationManagers;
use App\Models\StageIssue;
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

class StageIssueResource extends Resource
{
    protected static ?string $model = StageIssue::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-camera';

    protected static string|\UnitEnum|null $navigationGroup = 'Quality';

    protected static ?string $recordTitleAttribute = 'work_order_stage_id';

    public static function canViewAny(): bool
    {
        return (bool) config('factory.modules.quality') && parent::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                Select::make('work_order_stage_id')->label('Stage')->relationship('stage', 'name')->searchable()->preload()->required(),
                Select::make('work_order_id')->label('Workorder')->relationship('workOrder', 'number')->searchable()->preload()->required(),
                TextInput::make('defect_code')->required()->maxLength(255),
                TextInput::make('severity')->required()->maxLength(255),
                Textarea::make('description')->columnSpanFull()->required(),
                TextInput::make('photo_path')->maxLength(255),
                TextInput::make('status')->required()->maxLength(255),
                KeyValue::make('ai_analysis')->columnSpanFull(),
                KeyValue::make('suggested_actions')->columnSpanFull(),
                Select::make('reported_by')->label('Reporter')->relationship('reporter', 'name')->searchable()->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('stage.name')->label('stage'),
                TextColumn::make('workOrder.number')->label('workOrder'),
                TextColumn::make('defect_code')->searchable()->sortable(),
                TextColumn::make('severity')->searchable()->sortable(),
                TextColumn::make('description'),
                TextColumn::make('photo_path')->searchable()->sortable(),
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
            'index' => Pages\ManageStageIssues::route('/'),
        ];
    }
}
