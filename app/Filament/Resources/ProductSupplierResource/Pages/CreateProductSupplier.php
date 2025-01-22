<?php

namespace App\Filament\Resources\ProductSupplierResource\Pages;

use App\Filament\Resources\ProductSupplierResource;
use App\Models\ProductSupplier;
use App\Traits\GeneratesAutomaticCode;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;

class CreateProductSupplier extends CreateRecord
{
    protected static string $resource = ProductSupplierResource::class;

    use GeneratesAutomaticCode;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')
                    ->required()
                    ->default(fn () => $this->generateCode(ProductSupplier::class))

                    ->maxLength(255),
                TextInput::make('name')
                    ->required()
                    ->columnSpan(2)
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                TextInput::make('service_type')
                    ->maxLength(255),
                TextInput::make('address')
                    ->maxLength(255),
                TextInput::make('city')
                    ->maxLength(255),
                TextInput::make('state')
                    ->maxLength(255),
                TextInput::make('zip')
                    ->maxLength(255),
                TextInput::make('document')
                    ->maxLength(255),
                TextInput::make('bank_account')
                    ->maxLength(255),
                Toggle::make('client')
                    ->required(),
                TextInput::make('code_client')
                    ->maxLength(255),
            ]);
    }

    protected function getRedirectUrl(): string
    {
        // Redirecionar para a página de visualização do item recém-criado
        return $this->getResource()::getUrl('index');
    }

}
