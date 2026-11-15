<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
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

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-stop';

    protected static string|\UnitEnum|null $navigationGroup = 'Catalog';

    protected static ?string $recordTitleAttribute = 'name';

    public static function canViewAny(): bool
    {
        return (bool) config('factory.modules.catalog') && parent::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
                Select::make('category_id')->label('Category')->relationship('category', 'name')->searchable()->preload(),
                TextInput::make('sku')->required()->maxLength(255),
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('name_bn')->maxLength(255),
                TextInput::make('slug')->maxLength(255),
                TextInput::make('slug_bn')->maxLength(255),
                TextInput::make('button_family')->required()->maxLength(255),
                TextInput::make('size_ligne')->numeric()->required(),
                TextInput::make('thickness_mm')->numeric()->required(),
                TextInput::make('hole_type')->required()->maxLength(255),
                TextInput::make('design_pattern')->required()->maxLength(255),
                TextInput::make('finish')->required()->maxLength(255),
                TextInput::make('color')->required()->maxLength(255),
                TextInput::make('color_code')->maxLength(255),
                TextInput::make('material_label')->maxLength(255),
                TextInput::make('strategy')->required()->maxLength(255),
                TextInput::make('standard_cost')->numeric()->required(),
                TextInput::make('list_price')->numeric()->required(),
                TextInput::make('min_stock')->numeric(),
                TextInput::make('reorder_qty')->numeric(),
                Toggle::make('is_published'),
                TextInput::make('image_path')->maxLength(255),
                TextInput::make('excerpt')->maxLength(255),
                Textarea::make('description')->columnSpanFull(),
                Textarea::make('description_bn')->columnSpanFull(),
                TextInput::make('meta_title')->maxLength(255),
                TextInput::make('meta_description')->maxLength(255),
                KeyValue::make('attributes')->columnSpanFull(),
                TagsInput::make('tags'),
                KeyValue::make('faq')->columnSpanFull(),
                Textarea::make('care_instructions')->columnSpanFull(),
                KeyValue::make('size_guide')->columnSpanFull(),
                TextInput::make('gtin')->maxLength(255),
                TextInput::make('mpn')->maxLength(255),
                KeyValue::make('json_ld')->columnSpanFull(),
                TextInput::make('image_alt')->maxLength(255),
                KeyValue::make('translations')->columnSpanFull(),
                TextInput::make('locale')->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name')->label('category'),
                TextColumn::make('sku')->searchable()->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('name_bn')->searchable()->sortable(),
                TextColumn::make('slug')->searchable()->sortable(),
                TextColumn::make('slug_bn')->searchable()->sortable(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
