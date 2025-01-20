<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ToolCategoriesResource\Pages;
use App\Filament\Resources\ToolCategoriesResource\RelationManagers;
use App\Models\ToolCategories;
use App\Models\ToolCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ToolCategoriesResource extends Resource
{
    protected static ?string $model = ToolCategory::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Categoria Herramientas';
    protected static ?string $breadcrumb = 'Categoria Herramientas';
    protected static ?string $navigationGroup = 'Administración';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->required(),
                Forms\Components\RichEditor::make('description')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
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
            'index' => Pages\ListToolCategories::route('/'),
            'create' => Pages\CreateToolCategories::route('/create'),
            'edit' => Pages\EditToolCategories::route('/{record}/edit'),
        ];
    }
}
