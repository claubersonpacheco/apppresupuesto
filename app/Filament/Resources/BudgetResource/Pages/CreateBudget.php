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

    protected static ?string $title = 'Nuevo Presupuesto';

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
