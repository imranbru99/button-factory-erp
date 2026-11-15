<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProcessTemplateStageResource\Pages;
use App\Filament\Resources\ProcessTemplateStageResource\RelationManagers;
use App\Models\ProcessTemplateStage;
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

class ProcessTemplateStageResource extends Resource
{
    protected static ?string $model = ProcessTemplateStage::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-bars-3';

    protected static string|\UnitEnum|null $navigationGroup = 'Planning';

    protected static ?string $recordTitleAttribute = 'name';

    public static function canViewAny(): bool
    {
        return (bool) config('factory.modules.planning') && parent::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                Select::make('process_template_id')->label('Template')->relationship('template', 'name')->searchable()->preload()->required(),
                TextInput::make('code')->required()->maxLength(255),
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('sequence')->numeric()->required(),
                TextInput::make('minutes_per_thousand')->numeric()->required(),
                TextInput::make('work_center_code')->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('template.name')->label('template'),
                TextColumn::make('code')->searchable()->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('sequence')->searchable()->sortable(),
                TextColumn::make('minutes_per_thousand'),
                TextColumn::make('work_center_code')->searchable()->sortable(),
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
            'index' => Pages\ManageProcessTemplateStages::route('/'),
        ];
    }
}
