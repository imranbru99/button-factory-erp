<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormulaComponentResource\Pages;
use App\Filament\Resources\FormulaComponentResource\RelationManagers;
use App\Models\FormulaComponent;
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

class FormulaComponentResource extends Resource
{
    protected static ?string $model = FormulaComponent::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-swatch';

    protected static string|\UnitEnum|null $navigationGroup = 'Catalog';

    protected static ?string $recordTitleAttribute = 'formula_id';

    public static function canViewAny(): bool
    {
        return (bool) config('factory.modules.catalog') && parent::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                Select::make('formula_id')->label('Formula')->relationship('formula', 'name')->searchable()->preload()->required(),
                Select::make('material_id')->label('Material')->relationship('material', 'name')->searchable()->preload()->required(),
                TextInput::make('role')->required()->maxLength(255),
                TextInput::make('percentage')->numeric()->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('formula.name')->label('formula'),
                TextColumn::make('material.name')->label('material'),
                TextColumn::make('role')->searchable()->sortable(),
                TextColumn::make('percentage'),
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
            'index' => Pages\ManageFormulaComponents::route('/'),
        ];
    }
}
