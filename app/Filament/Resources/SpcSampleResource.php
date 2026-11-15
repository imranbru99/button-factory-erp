<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpcSampleResource\Pages;
use App\Filament\Resources\SpcSampleResource\RelationManagers;
use App\Models\SpcSample;
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

class SpcSampleResource extends Resource
{
    protected static ?string $model = SpcSample::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static string|\UnitEnum|null $navigationGroup = 'Quality';

    protected static ?string $recordTitleAttribute = 'work_order_stage_id';

    public static function canViewAny(): bool
    {
        return (bool) config('factory.modules.quality') && parent::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                Select::make('work_order_stage_id')->label('Stage')->relationship('stage', 'name')->searchable()->preload(),
                TextInput::make('characteristic')->required()->maxLength(255),
                TextInput::make('value')->numeric()->required(),
                DateTimePicker::make('sampled_at')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('stage.name')->label('stage'),
                TextColumn::make('characteristic')->searchable()->sortable(),
                TextColumn::make('value'),
                TextColumn::make('sampled_at'),
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
            'index' => Pages\ManageSpcSamples::route('/'),
        ];
    }
}
