<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeSkillResource\Pages;
use App\Filament\Resources\EmployeeSkillResource\RelationManagers;
use App\Models\EmployeeSkill;
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

class EmployeeSkillResource extends Resource
{
    protected static ?string $model = EmployeeSkill::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

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
                TextInput::make('skill')->required()->maxLength(255),
                TextInput::make('level')->numeric()->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.name')->label('employee'),
                TextColumn::make('skill')->searchable()->sortable(),
                TextColumn::make('level')->searchable()->sortable(),
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
            'index' => Pages\ManageEmployeeSkills::route('/'),
        ];
    }
}
