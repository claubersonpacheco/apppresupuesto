<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Enum\ProductType;
use App\Filament\Resources\ProductResource;
use App\Models\Budget;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Traits\GeneratesAutomaticCode;
use Filament\Actions;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    use GeneratesAutomaticCode;

    protected static string $resource = ProductResource::class;

    public function form(Form $form): Form
    {

        return $form
            ->schema([

                Select::make('category_id')
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

                TextInput::make('code')
                    ->label('Código')
                    ->required()
                    ->default(fn () => $this->generateCode(Product::class)) // Define o próximo código como padrão
                    ->maxLength(20),
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),
                MarkdownEditor::make('description')
                    ->label('Descripción')
                    ->columnSpan(2),
                TextInput::make('price')
                    ->label('Precio')
                    ->required()
                    ->numeric()
                    ->rules('numeric|min:0'),
                Select::make('product_type')
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

    protected function getRedirectUrl(): string
    {
        // Redirecionar para a página de visualização do item recém-criado
        return $this->getResource()::getUrl('index');
    }
}
