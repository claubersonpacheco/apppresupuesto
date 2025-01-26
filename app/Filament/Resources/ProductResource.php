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


    protected static ?string $navigationGroup = 'Menu';
    protected static ?int $navigationSort = 7;

    public static function getModelLabel(): string
    {
        return __('Service');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->translateLabel()
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Category name')
                            ->translateLabel()
                            ->required(),
                        RichEditor::make('description')
                            ->translateLabel()
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
                    ->translateLabel()
                    ->required()
                    ->maxLength(20),
                Forms\Components\TextInput::make('name')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),
                Forms\Components\MarkdownEditor::make('description')
                    ->translateLabel()
                    ->columnSpan(2),
                Forms\Components\TextInput::make('price')
                    ->translateLabel()
                    ->required()
                    ->numeric()
                    ->rules('numeric|min:0'),

                Forms\Components\Select::make('product_type')
                    ->label('Type')
                    ->translateLabel()
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
                Tables\Columns\TextColumn::make('code') ->translateLabel()->sortable()->searchable(),
                Tables\Columns\TextColumn::make('name') ->translateLabel()->sortable()->searchable(),
                Tables\Columns\TextColumn::make('price') ->translateLabel()->sortable(),
                Tables\Columns\TextColumn::make('product_type') ->translateLabel(),
                Tables\Columns\TextColumn::make('created_at') ->translateLabel()->dateTime('d/m/Y H:i:s'),
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
