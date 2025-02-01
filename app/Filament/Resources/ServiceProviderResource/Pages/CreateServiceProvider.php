<?php

namespace App\Filament\Resources\ServiceProviderResource\Pages;

use App\Filament\Resources\ServiceProviderResource;
use App\Models\ServiceProvider;
use App\Traits\GeneratesAutomaticCode;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceProvider extends CreateRecord
{
    protected static string $resource = ServiceProviderResource::class;

    use GeneratesAutomaticCode;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')
                    ->translateLabel()
                    ->required()
                    ->default(fn () => $this->generateCode(ServiceProvider::class))
                    ->maxLength(255),
                TextInput::make('name')
                    ->translateLabel()
                    ->required()
                    ->columnSpan(2)
                    ->maxLength(255),
                DatePicker::make('birth_date')
                    ->translateLabel(),
                TextInput::make('email')
                    ->translateLabel()
                    ->email()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->translateLabel()
                    ->tel()
                    ->maxLength(255),
                TextInput::make('service_type')
                    ->translateLabel()
                    ->maxLength(255),
                TextInput::make('address')
                    ->translateLabel()
                    ->maxLength(255),
                TextInput::make('city')
                    ->translateLabel()
                    ->maxLength(255),
                TextInput::make('state')
                    ->translateLabel()
                    ->maxLength(255),
                TextInput::make('zip')
                    ->translateLabel()
                    ->maxLength(255),
                TextInput::make('document')
                    ->translateLabel()
                    ->maxLength(255),
                TextInput::make('bank_account')
                    ->translateLabel()
                    ->maxLength(255),
            ]);
    }

    protected function getRedirectUrl(): string
    {
        // Redirecionar para a página de visualização do item recém-criado
        return $this->getResource()::getUrl('index');
    }
}
