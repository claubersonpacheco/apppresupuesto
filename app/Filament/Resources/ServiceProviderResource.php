<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceProviderResource\Pages;
use App\Filament\Resources\ServiceProviderResource\RelationManagers;
use App\Models\ServiceProvider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceProviderResource extends Resource
{
    protected static ?string $model = ServiceProvider::class;

    protected static ?string $navigationIcon = 'heroicon-o-face-smile';

    public static function getModelLabel(): string
    {
        return __('Service Provider');
    }

    protected static ?string $navigationGroup = 'Menu';

    protected static ?int $navigationSort = 6;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->translateLabel()
                    ->required()
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('birth_date')
                ->translateLabel(),
                Forms\Components\TextInput::make('email')
                    ->translateLabel()
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->translateLabel()
                    ->tel()
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('service_type')
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('city')
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('state')
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('zip')
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('document')
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('bank_account')
                    ->translateLabel()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->translateLabel()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('service_type')
                    ->searchable(),


                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListServiceProviders::route('/'),
            'create' => Pages\CreateServiceProvider::route('/create'),
            'edit' => Pages\EditServiceProvider::route('/{record}/edit'),
        ];
    }
}
