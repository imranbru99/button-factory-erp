<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DefectResource\Pages;
use App\Filament\Resources\DefectResource\RelationManagers;
use App\Models\Defect;
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

class DefectResource extends Resource
{
    protected static ?string $model = Defect::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-x-circle';

    protected static string|\UnitEnum|null $navigationGroup = 'Quality';

    protected static ?string $recordTitleAttribute = 'inspection_id';

    public static function canViewAny(): bool
    {
        return (bool) config('factory.modules.quality') && parent::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                Select::make('inspection_id')->label('Inspection')->relationship('inspection', 'number')->searchable()->preload()->required(),
                TextInput::make('defect_code')->required()->maxLength(255),
                TextInput::make('qty')->numeric()->required(),
                TextInput::make('photo_path')->maxLength(255),
                Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('inspection.number')->label('inspection'),
                TextColumn::make('defect_code')->searchable()->sortable(),
                TextColumn::make('qty'),
                TextColumn::make('photo_path')->searchable()->sortable(),
                TextColumn::make('notes'),
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
            'index' => Pages\ManageDefects::route('/'),
        ];
    }
}
