<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Filament\Resources\SettingResource\RelationManagers;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';

    protected static ?string $navigationLabel = 'Ajustes';
    protected static ?string $breadcrumb = 'ajustes';
    protected static ?string $navigationGroup = 'Administración';

    protected static ?int $navigationSort = 10;

    public static function getModelLabel(): string
    {
        return __('Settings');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255)
                ->columnSpan(2),
                Forms\Components\TextInput::make('document')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),
                Forms\Components\FileUpload::make('logo')
                    ->translateLabel()
                    ->directory('logo')
                    ->image()
                    ->imageEditor(),
                Forms\Components\FileUpload::make('logo_impress')
                    ->translateLabel()
                    ->directory('logo/impress')
                    ->image()
                    ->imageEditor(),

                Forms\Components\FileUpload::make('favicon')
                    ->directory('logo')
                    ->image()
                    ->imageEditor(),
                Forms\Components\TextInput::make('address')
                    ->translateLabel()
                    ->maxLength(100)
                ->columnSpan(2),
                Forms\Components\TextInput::make('city')
                    ->translateLabel()
                    ->maxLength(100),
                Forms\Components\TextInput::make('postal_code')
                    ->translateLabel()
                    ->maxLength(10),

                Forms\Components\TextInput::make('email')
                    ->translateLabel()
                    ->email()
                    ->maxLength(255),

                Forms\Components\TextInput::make('send_email')
                    ->translateLabel()
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('whatsapp')
                    ->maxLength(255),
                Forms\Components\TextInput::make('prefix')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('title')
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
