<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use App\Traits\GeneratesAutomaticCode;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateUser extends CreateRecord
{
    use GeneratesAutomaticCode;

    protected static string $resource = UserResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')
                    ->label('Código')
                    ->required()
                    ->default(fn () => $this->generateCode(User::class))
                    ->maxLength(20),
                TextInput::make('name')
                    ->translateLabel(__('name'))
                    ->required()
                    ->maxLength(100)
                ->columnSpan(2),
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50)
                    ->columnSpan(2),
                TextInput::make('password')
                    ->translateLabel()
                    ->password()
                    ->required(fn ($livewire) => $livewire instanceof Pages\CreateUser)
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn ($state) => $state ? Hash::make($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->confirmed(),
                TextInput::make('password_confirmation')
                    ->translateLabel()
                    ->password()
                    ->requiredWith(statePaths: 'password')
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn ($state) => $state ? Hash::make($state) : null)
                    ->dehydrated(false),

                FileUpload::make('avatar')
                    ->avatar()
                    ->image()
                    ->directory('avatars')
                    ->imageEditor()
                    ->maxSize(1024) // Tamanho máximo em KB
                    ->nullable()
                ->columnSpan(2),
            ]);
    }
}
