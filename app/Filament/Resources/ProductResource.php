<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Str;
use Filament\Tables\Filters\SelectFilter;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationLabel = 'Produk';

    protected static ?string $modelLabel = 'Produk';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Produk';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama Produk')
                ->required()
                ->maxLength(255)
                ->placeholder('Masukkan nama produk'),

            Forms\Components\TextInput::make('price')
                ->label('Harga Produk')
                ->required()
                ->numeric()
                ->placeholder('Masukkan harga produk'),

            Forms\Components\TextInput::make('stock')
                ->label('Stok Produk')
                ->required()
                ->numeric()
                ->placeholder('Masukkan stok produk'),

            Forms\Components\Select::make('category_id')
                ->label('Kategori Produk')
                ->relationship('category', 'name')
                ->required()
                ->searchable(),

            Forms\Components\Select::make('brand_id')
                ->label('Merek Produk')
                ->relationship('brand', 'name')
                ->required()
                ->searchable(),

            Forms\Components\Select::make('status')
                ->label('Status Produk')
                ->options([
                    'active' => 'Tersedia',
                    'inactive' => 'Tidak Tersedia',
                ])
                ->default('active')
                ->required(),

            FileUpload::make('image')
                ->label('Gambar Produk')
                ->image()
                ->maxSize(1024) // Maksimal 1MB
                ->directory('products') // Simpan di folder storage/products
                ->nullable(),

            Forms\Components\Textarea::make('description')
                ->label('Deskripsi Produk')
                ->required()
                ->maxLength(1000)
                ->placeholder('Masukkan deskripsi produk'),
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

            Tables\Columns\TextColumn::make('created_at')
                ->label('Dibuat Pada')
                ->dateTime(),

            Tables\Columns\TextColumn::make('updated_at')
                ->label('Diperbarui Pada')
                ->dateTime(),
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
}
