<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Filament\Resources\Customer\ExpensesResource\RelationManagers\ExpensesRelationManager;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Menu';

    public static function getModelLabel(): string
    {
        return __('Customer');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->translateLabel()
                    ->required()
                    ->disabled()
                    ->maxLength(20),
                Forms\Components\TextInput::make('name')
                    ->translateLabel()
                    ->required()
                    ->maxLength(100)
                    ->columnSpan(2),
                Forms\Components\TextInput::make('email')
                    ->translateLabel()
                    ->required()
                    ->email()
                    ->maxLength(100),
                Forms\Components\TextInput::make('phone')
                    ->translateLabel()
                    ->required()
                    ->maxLength(15),
                Forms\Components\TextInput::make('document')
                    ->translateLabel(),
                Forms\Components\TextInput::make('address')
                    ->translateLabel()
                    ->required()
                    ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->translateLabel()->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->translateLabel()->sortable()->searchable(),
                Tables\Columns\TextColumn::make('phone')->translateLabel()->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->translateLabel()->dateTime(),
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
            //ExpensesRelationManager::class,
            // Outros relation managers se necessário
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return self::getModel()::count();
    }
}
