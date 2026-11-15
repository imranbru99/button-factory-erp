<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuotationLineResource\Pages;
use App\Filament\Resources\QuotationLineResource\RelationManagers;
use App\Models\QuotationLine;
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

class QuotationLineResource extends Resource
{
    protected static ?string $model = QuotationLine::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-minus';

    protected static string|\UnitEnum|null $navigationGroup = 'Sales';

    protected static ?string $recordTitleAttribute = 'quotation_id';

    public static function canViewAny(): bool
    {
        return (bool) config('factory.modules.sales') && parent::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                Select::make('quotation_id')->label('Quotation')->relationship('quotation', 'number')->searchable()->preload()->required(),
                Select::make('product_id')->label('Product')->relationship('product', 'name')->searchable()->preload()->required(),
                TextInput::make('qty')->numeric()->required(),
                TextInput::make('unit_price')->numeric()->required(),
                TextInput::make('finish')->maxLength(255),
                TextInput::make('size_ligne')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('quotation.number')->label('quotation'),
                TextColumn::make('product.name')->label('product'),
                TextColumn::make('qty')->searchable()->sortable(),
                TextColumn::make('unit_price'),
                TextColumn::make('finish')->searchable()->sortable(),
                TextColumn::make('size_ligne')->searchable()->sortable(),
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
            'index' => Pages\ManageQuotationLines::route('/'),
        ];
    }
}
