<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class UserResource extends Resource
{

    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';


    protected static ?string $navigationGroup = 'Administración';

    protected static ?int $navigationSort = 11;

    public static function getModelLabel(): string
    {
        return __('User');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->translateLabel(__('name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),
                Forms\Components\TextInput::make('password')
                    ->translateLabel()
                    ->password()
                    ->required(fn ($livewire) => $livewire instanceof Pages\CreateUser)
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn ($state) => $state ? Hash::make($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->confirmed(),
                Forms\Components\TextInput::make('password_confirmation')
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
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->disk('public')
                    ->url(fn ($record) => Storage::url($record->avatar)),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),

                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/y'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
