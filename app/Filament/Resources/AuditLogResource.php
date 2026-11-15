<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditLogResource\Pages;
use App\Filament\Resources\AuditLogResource\RelationManagers;
use App\Models\AuditLog;
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

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static string|\UnitEnum|null $navigationGroup = 'System';

    protected static ?string $recordTitleAttribute = 'user_id';

    public static function canViewAny(): bool
    {
        return (bool) config('factory.modules.system') && parent::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                Select::make('user_id')->label('User')->relationship('user', 'name')->searchable()->preload(),
                TextInput::make('auditable_type')->maxLength(255),
                TextInput::make('auditable_id')->numeric(),
                TextInput::make('event')->required()->maxLength(255),
                KeyValue::make('old_values')->columnSpanFull(),
                KeyValue::make('new_values')->columnSpanFull(),
                TextInput::make('ip')->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('user'),
                TextColumn::make('auditable_type')->searchable()->sortable(),
                TextColumn::make('auditable_id')->searchable()->sortable(),
                TextColumn::make('event')->searchable()->sortable(),
                TextColumn::make('old_values'),
                TextColumn::make('new_values'),
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
            'index' => Pages\ManageAuditLogs::route('/'),
        ];
    }
}
