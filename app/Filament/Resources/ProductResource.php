<?php

namespace App\Filament\Resources;

use Illuminate\Support\Str;
use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductVariant;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Builder;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationLabel = 'Produk';

    protected static ?string $modelLabel = 'Produk';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Produk';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Produk')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Produk')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('price')
                            ->label('Harga Dasar')
                            ->numeric()
                            ->required(),

                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->required(),

                        Forms\Components\Select::make('brand_id')
                            ->label('Merek')
                            ->relationship('brand', 'name')
                            ->required(),

                        Forms\Components\FileUpload::make('main_image')
                            ->label('Gambar Utama')
                            ->image()
                            ->directory('products'),

                        Forms\Components\RichEditor::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Repeater::make('attributes')
                    ->relationship()
                    ->label('Varian Produk')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Label Varian')
                            ->required(),

                        Forms\Components\Repeater::make('values')
                            ->relationship()
                            ->label('Isi Varian')
                            ->simple(
                                Forms\Components\TextInput::make('value')
                                    ->label('Nilai')
                                    ->required(),
                            )
                            ->addActionLabel('Tambah Nilai')
                            ->columns(1)
                    ])
                    ->collapseAllAction(
                        fn(Action $action) => $action->label('Collapse all members'),
                    )
                    ->addActionLabel('Tambah Varian')
                    ->columns(1),

                Forms\Components\Repeater::make('variants')
                    ->relationship()
                    ->label('Varian')
                    ->schema([
                        Forms\Components\TextInput::make('sku')
                            ->label('SKU')
                            ->required(),

                        Forms\Components\TextInput::make('price')
                            ->label('Harga')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('stock')
                            ->label('Stok')
                            ->numeric()
                            ->required(),

                        Forms\Components\Select::make('attribute_combination')
                            ->label('Pilih Kombinasi Varian')
                            ->options(function ($get) {
                                $productId = $get('id') ?? $get('../../id');

                                $attributes = Attribute::with('values')
                                    ->where('product_id', $productId)
                                    ->get();


                                return static::generateCombinations($attributes);
                            })
                            ->required()
                            ->searchable(false)
                            ->afterStateHydrated(function ($component, $state) {
                                if (is_array($state)) {
                                    $component->state(json_encode(
                                        $state instanceof Collection
                                            ? $state->pluck('id')->toArray()
                                            : $state
                                    ));
                                }
                            })
                            ->dehydrateStateUsing(fn($state) => json_decode($state, true))
                            ->saveRelationshipsUsing(function (ProductVariant $variant, $state) {
                                $variant->attributeValues()->sync(json_decode($state, true));
                                $variant->update([
                                    'sku' => static::generateSku($variant->product, json_decode($state, true))
                                ]);
                            }),

                        Forms\Components\FileUpload::make('image')
                            ->label('Gambar Varian')
                            ->image()
                            ->directory('product-variants')
                    ])
                    ->addActionLabel('Tambah Varian')
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->sortable(),

            Tables\Columns\TextColumn::make('name')
                ->label('Nama Produk')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('category.name')
                ->label('Kategori Produk')
                ->sortable(),

            Tables\Columns\TextColumn::make('brand.name')
                ->label('Merek Produk')
                ->sortable(),

            Tables\Columns\TextColumn::make('price')
                ->label('Harga Produk')
                ->sortable()
                ->money('IDR'),

            Tables\Columns\TextColumn::make('stock')
                ->label('Stok Produk')
                ->sortable(),

            Tables\Columns\ImageColumn::make('image')
                ->label('Gambar Produk'),

            Tables\Columns\TextColumn::make('status')
                ->label('Status Produk')
                ->sortable(),

            Tables\Columns\TextColumn::make('variants.sku')
                ->label('Varian SKU'),

            Tables\Columns\TextColumn::make('variants.price')
                ->label('Harga Varian')
                ->money('IDR'),

            Tables\Columns\TextColumn::make('variants.stock')
                ->label('Stok Varian'),
        ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Kategori Produk')
                    ->relationship('category', 'name')
                    ->searchable(),

                SelectFilter::make('brand_id')
                    ->label('Merek Produk')
                    ->relationship('brand', 'name')
                    ->searchable(),

                SelectFilter::make('status')
                    ->label('Status Produk')
                    ->options([
                        'active' => 'Tersedia',
                        'inactive' => 'Tidak Tersedia',
                    ])
                    ->default('active'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    protected static function generateCombinations($attributes)
    {
        $groups = $attributes->map(fn($attr) => $attr->values->map(fn($val) => [
            'id' => $val->id,
            'label' => "{$attr->name}: {$val->value}"
        ]));

        $combinations = collect($groups->shift())
            ->crossJoin(...$groups)
            ->mapWithKeys(fn($items) => [
                json_encode(collect($items)->pluck('id')) =>
                collect($items)->pluck('label')->join(' + ')
            ]);

        return $combinations->all();
    }

    protected static function generateSku($product, $attributeValues)
    {
        $prefix = substr($product->name, 0, 3);
        $codes = AttributeValue::whereIn('id', $attributeValues)
            ->orderBy('attribute_id')
            ->get()
            ->map(fn($av) => strtoupper(substr($av->value, 0, 2)))
            ->implode('');

        return $prefix . '-' . $codes . '-' . Str::random(4);
    }
}
