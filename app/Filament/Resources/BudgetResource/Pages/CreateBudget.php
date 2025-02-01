<?php

namespace App\Filament\Resources\BudgetResource\Pages;

use App\Filament\Resources\BudgetResource;
use App\Models\Budget;
use App\Traits\GeneratesAutomaticCode;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;

class CreateBudget extends CreateRecord
{
    use GeneratesAutomaticCode;

    protected static string $resource = BudgetResource::class;
    public function form(Form $form): Form
    {

        return $form
            ->schema([
                TextInput::make('code')
                    ->label('Código')
                    ->required()
                    ->default(fn () => $this->generateCode(Budget::class))
                    ->maxLength(20),

                Select::make('customer_id')
                    ->label('Client')
                    ->translateLabel()
                    ->relationship('customer', 'name')
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->translateLabel()
                            ->required()
                            ->unique()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->translateLabel()
                            ->required()
                            ->unique()
                            ->email()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->translateLabel()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('address')
                            ->translateLabel()
                            ->required()
                            ->maxLength(255),
                    ])
                    ->searchable()
                    ->preload(),

                TextInput::make('name')
                    ->translateLabel()
                    ->required()
                    ->translateLabel()
                    ->maxLength(255)
                    ->columnSpan('full'),

                RichEditor::make('description')
                    ->translateLabel()
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
