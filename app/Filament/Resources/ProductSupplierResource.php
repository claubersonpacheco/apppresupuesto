<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductSupplierResource\Pages;
use App\Filament\Resources\ProductSupplierResource\RelationManagers;
use App\Models\ProductSupplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductSupplierResource extends Resource
{
    protected static ?string $model = ProductSupplier::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    public static function getModelLabel(): string
    {
        return __('Product Supplier');
    }

    protected static ?string $navigationGroup = 'Menu';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->translateLabel()
                    ->required()
                    ->disabled()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->translateLabel()
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->translateLabel()
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('service_type')
                    ->label("Service type")
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
                    ->label("Bank account")
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\Toggle::make('client')
                    ->translateLabel()
                    ->label('¿Es cliente?')
                    ->required(),
                Forms\Components\TextInput::make('code_client')
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('service_type')
                    ->translateLabel()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
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
            'index' => Pages\ListProductSuppliers::route('/'),
            'create' => Pages\CreateProductSupplier::route('/create'),
            'edit' => Pages\EditProductSupplier::route('/{record}/edit'),
        ];
    }
}
