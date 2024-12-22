<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Models\Customer;
use App\Traits\GeneratesAutomaticCode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    use GeneratesAutomaticCode;

    protected static string $resource = CustomerResource::class;
    protected static ?string $title = 'Nuevo Cliente';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->disabled()
                    ->default(fn () => $this->generateCode(Customer::class))
                    ->maxLength(50),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),
                Forms\Components\TextInput::make('email')
                    ->label('Correo Eletronico')
                    ->required()
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('Telefono')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('document')
                    ->label('Documentación'),
                Forms\Components\TextInput::make('address')
                    ->label('Direccion')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
