<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CapaResource\Pages;
use App\Filament\Resources\CapaResource\RelationManagers;
use App\Models\Capa;
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

class CapaResource extends Resource
{
    protected static ?string $model = Capa::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-path';

    protected static string|\UnitEnum|null $navigationGroup = 'Quality';

    protected static ?string $recordTitleAttribute = 'ncr_id';

    public static function canViewAny(): bool
    {
        return (bool) config('factory.modules.quality') && parent::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                Select::make('ncr_id')->label('Ncr')->relationship('ncr', 'number')->searchable()->preload()->required(),
                Textarea::make('action')->columnSpanFull()->required(),
                Select::make('owner_id')->label('Owner')->relationship('owner', 'name')->searchable()->preload(),
                DatePicker::make('due_on'),
                TextInput::make('status')->required()->maxLength(255),
                Textarea::make('effectiveness_notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ncr.number')->label('ncr'),
                TextColumn::make('action'),
                TextColumn::make('owner.name')->label('owner'),
                TextColumn::make('due_on'),
                TextColumn::make('status')->searchable()->sortable(),
                TextColumn::make('effectiveness_notes'),
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
            'index' => Pages\ManageCapas::route('/'),
        ];
    }
}
