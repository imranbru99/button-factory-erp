<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OvertimeEntryResource\Pages;
use App\Filament\Resources\OvertimeEntryResource\RelationManagers;
use App\Models\OvertimeEntry;
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

class OvertimeEntryResource extends Resource
{
    protected static ?string $model = OvertimeEntry::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-bolt';

    protected static string|\UnitEnum|null $navigationGroup = 'People';

    protected static ?string $recordTitleAttribute = 'employee_id';

    public static function canViewAny(): bool
    {
        return (bool) config('factory.modules.hr') && parent::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                Select::make('employee_id')->label('Employee')->relationship('employee', 'name')->searchable()->preload()->required(),
                DatePicker::make('work_date')->required(),
                TextInput::make('hours')->numeric()->required(),
                TextInput::make('rate_multiplier')->numeric()->required(),
                TextInput::make('status')->required()->maxLength(255),
                TextInput::make('notes')->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.name')->label('employee'),
                TextColumn::make('work_date'),
                TextColumn::make('hours'),
                TextColumn::make('rate_multiplier'),
                TextColumn::make('status')->searchable()->sortable(),
                TextColumn::make('notes')->searchable()->sortable(),
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
            'index' => Pages\ManageOvertimeEntrys::route('/'),
        ];
    }
}
