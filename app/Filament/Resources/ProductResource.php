<?php

namespace App\Filament\Resources;

use App\Enum\ProductType;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationLabel = 'Servicios';
    protected static ?string $navigationGroup = 'Menu';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->label('Categoria')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Categoria Nombre')
                            ->required(),
                        RichEditor::make('description')
                            ->label('Categoria Descripción'),
                    ])
                    ->createOptionUsing(function (array $data): int {
                        $category = Category::create([
                            'name' => $data['name'],
                            'description' => $data['description'] ?? null,
                        ]);

                        return $category->id;
                    }),

                Forms\Components\TextInput::make('code')
                    ->label('Codigo')
                    ->required()
                    ->maxLength(20),
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),
                Forms\Components\MarkdownEditor::make('description')
                    ->label('Descripción')
                    ->columnSpan(2),
                Forms\Components\TextInput::make('price')
                    ->label('Precio')
                    ->required()
                    ->numeric()
                    ->rules('numeric|min:0'),

                Forms\Components\Select::make('product_type')
                    ->label('Tipo')
                    ->options([
                        ProductType::METROS->value => 'Metros',
                        ProductType::CENTIMETROS->value => 'Centímetros',
                        ProductType::UNIDADE->value => 'Unidade',
                        ProductType::LITROS->value => 'Litros',
                        ProductType::DIA->value => 'Dia',
                        ProductType::HORA->value => 'Hora',
                        ProductType::MINUTO->value => 'Minuto',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('price')->sortable(),
                Tables\Columns\TextColumn::make('product_type'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
