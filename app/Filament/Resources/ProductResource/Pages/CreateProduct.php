<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Enum\ProductType;
use App\Filament\Resources\ProductResource;
use App\Models\Budget;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Filament\Actions;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected static ?string $title = 'Nuevo Servício';

    public function form(Form $form): Form
    {

        $prefix = Setting::getPrefix() ?? 'FS'; // Define o prefixo padrão

        $currentYear = substr(date('Y'), -2); // Pega os dois últimos dígitos do ano (ex: '24' para 2024)

        $codePattern = $prefix . $currentYear; // Concatena o prefixo e o ano (ex: 'FS24')

        $lastBudget = Product::where('code', 'LIKE', $codePattern . '%') // Busca os códigos que começam com 'FS24'
        ->orderBy('id', 'desc')
            ->first();

        $nextCodeNumber = '0001'; // Define o número inicial padrão

        if ($lastBudget) {
            // Extrai a parte numérica após o prefixo e o ano (ex: se for 'FS240001', pega '0001')
            $lastCode = intval(substr($lastBudget->code, strlen($prefix) + 2)); // +2 para contar os dois dígitos do ano

            // Incrementa o número e preenche com zeros à esquerda até 4 dígitos
            $nextCodeNumber = str_pad($lastCode + 1, 4, '0', STR_PAD_LEFT);
        }

        $nextCode = $prefix . $currentYear . $nextCodeNumber; // Concatena o prefixo, o ano e o número sequencial (ex: 'FS240001')


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
                    ->default($nextCode) // Define o próximo código como padrão
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
                    ->numeric(),
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
