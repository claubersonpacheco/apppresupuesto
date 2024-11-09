<?php

namespace App\Filament\Resources\BudgetResource\Pages;

use App\Filament\Resources\BudgetResource;
use App\Models\Budget;
use App\Models\Setting;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;

class CreateBudget extends CreateRecord
{
    protected static string $resource = BudgetResource::class;

    protected static ?string $title = 'Nuevo Presupuesto';


    public function form(Form $form): Form
    {

        $prefix = Setting::getPrefix() ?? 'FS'; // Define o prefixo padrão

        $currentYear = substr(date('Y'), -2); // Pega os dois últimos dígitos do ano (ex: '24' para 2024)

        $codePattern = $prefix . $currentYear; // Concatena o prefixo e o ano (ex: 'FS24')

        $lastBudget = Budget::where('code', 'LIKE', $codePattern . '%') // Busca os códigos que começam com 'FS24'
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
                TextInput::make('code')
                    ->label('Código')
                    ->required()
                    ->default($nextCode) // Define o próximo código como padrão
                    ->maxLength(20),

                Select::make('customer_id')
                    ->label('Cliente')
                    ->relationship('customer', 'name')
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->unique()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->required()
                            ->unique()
                            ->email()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('address')
                            ->required()
                            ->maxLength(255),
                    ]),

                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan('full'),

                RichEditor::make('description')
                    ->maxLength(255)
                    ->columnSpan('full'),


            ]);
    }

    protected function getRedirectUrl(): string
    {
        // Redirecionar para a página de visualização do item recém-criado
        return $this->getResource()::getUrl('items', ['record' => $this->record->getKey()]);
    }
}
