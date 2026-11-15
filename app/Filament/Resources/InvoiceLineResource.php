<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceLineResource\Pages;
use App\Filament\Resources\InvoiceLineResource\RelationManagers;
use App\Models\InvoiceLine;
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

class InvoiceLineResource extends Resource
{
    protected static ?string $model = InvoiceLine::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-minus';

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    protected static ?string $recordTitleAttribute = 'invoice_id';

    public static function canViewAny(): bool
    {
        return (bool) config('factory.modules.finance') && parent::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                Select::make('invoice_id')->label('Invoice')->relationship('invoice', 'number')->searchable()->preload()->required(),
                TextInput::make('description')->required()->maxLength(255),
                TextInput::make('qty')->numeric()->required(),
                TextInput::make('unit_price')->numeric()->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice.number')->label('invoice'),
                TextColumn::make('description')->searchable()->sortable(),
                TextColumn::make('qty'),
                TextColumn::make('unit_price'),
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
            'index' => Pages\ManageInvoiceLines::route('/'),
        ];
    }
}
