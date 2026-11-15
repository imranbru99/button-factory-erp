<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveRequestResource\Pages;
use App\Filament\Resources\LeaveRequestResource\RelationManagers;
use App\Models\LeaveRequest;
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

class LeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

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
                TextInput::make('type')->required()->maxLength(255),
                DatePicker::make('starts_on')->required(),
                DatePicker::make('ends_on')->required(),
                TextInput::make('status')->required()->maxLength(255),
                Textarea::make('reason')->columnSpanFull(),
                Select::make('approved_by')->label('Approver')->relationship('approver', 'name')->searchable()->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.name')->label('employee'),
                TextColumn::make('type')->searchable()->sortable(),
                TextColumn::make('starts_on'),
                TextColumn::make('ends_on'),
                TextColumn::make('status')->searchable()->sortable(),
                TextColumn::make('reason'),
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
            'index' => Pages\ManageLeaveRequests::route('/'),
        ];
    }
}
