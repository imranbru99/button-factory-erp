<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NcrResource\Pages;
use App\Filament\Resources\NcrResource\RelationManagers;
use App\Models\Ncr;
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

class NcrResource extends Resource
{
    protected static ?string $model = Ncr::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-exclamation';

    protected static string|\UnitEnum|null $navigationGroup = 'Quality';

    protected static ?string $recordTitleAttribute = 'number';

    public static function canViewAny(): bool
    {
        return (bool) config('factory.modules.quality') && parent::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                TextInput::make('number')->maxLength(255),
                Select::make('inspection_id')->label('Inspection')->relationship('inspection', 'number')->searchable()->preload(),
                Select::make('work_order_id')->label('Workorder')->relationship('workOrder', 'number')->searchable()->preload(),
                TextInput::make('status')->required()->maxLength(255),
                Textarea::make('description')->columnSpanFull()->required(),
                TextInput::make('disposition')->maxLength(255),
                Select::make('opened_by')->label('Opener')->relationship('opener', 'name')->searchable()->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')->searchable()->sortable(),
                TextColumn::make('inspection.number')->label('inspection'),
                TextColumn::make('workOrder.number')->label('workOrder'),
                TextColumn::make('status')->searchable()->sortable(),
                TextColumn::make('description'),
                TextColumn::make('disposition')->searchable()->sortable(),
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
            RelationManagers\CapasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNcrs::route('/'),
            'create' => Pages\CreateNcr::route('/create'),
            'edit' => Pages\EditNcr::route('/{record}/edit'),
        ];
    }
}
