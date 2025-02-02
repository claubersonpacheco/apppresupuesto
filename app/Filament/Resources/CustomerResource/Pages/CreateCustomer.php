<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Models\Customer;
use App\Traits\GeneratesAutomaticCode;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    use GeneratesAutomaticCode;

    protected static string $resource = CustomerResource::class;

    //protected static ?string $title = 'Nuevo Cliente';

    public function form(Form $form): Form
    {
        return $form

            ->schema([
                Grid::make([
                    'default' => 1,
                    'md' => 3
                ])->schema([
                    TextInput::make('code')
                       ->translateLabel()
                        ->required()
                        ->default(fn () =>
                            $this->generateCode(Customer::class)
                        ) // Define o próximo código como padrão
                        ->maxLength(20),
                    Forms\Components\TextInput::make('name')
                        ->translateLabel()
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('email')
                        ->translateLabel()
                        ->required()
                        ->email()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('phone')
                        ->translateLabel()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('document')
                        ->translateLabel(),
                    Forms\Components\TextInput::make('address')
                        ->translateLabel()
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                ])
            ]);
    }

    protected function getRedirectUrl(): string
    {
        // Redirecionar para a página de visualização do item recém-criado
        return $this->getResource()::getUrl('index');
    }
}
