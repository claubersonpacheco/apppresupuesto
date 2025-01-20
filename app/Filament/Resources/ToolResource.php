<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ToolResource\Pages;
use App\Filament\Resources\ToolResource\RelationManagers;
use App\Models\Tool;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class ToolResource extends Resource
{
    protected static ?string $model = Tool::class;
    protected static ?string $navigationLabel = 'Herramientas';
    protected static ?string $breadcrumb = 'Herramientas';
    protected static ?string $navigationGroup = 'Administración';

    protected static ?string $navigationIcon = 'heroicon-o-wrench';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(100),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                Forms\Components\TextInput::make('brand')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('model')
                    ->maxLength(50),
                Forms\Components\TextInput::make('serial_number')
                    ->maxLength(50),
                Forms\Components\TextInput::make('condition')
                    ->required()
                    ->maxLength(20),
                Forms\Components\DatePicker::make('purchase_date')
                    ->required(),
                Forms\Components\TextInput::make('purchase_price')
                    ->numeric(),
                Forms\Components\TextInput::make('storage_location')
                    ->maxLength(50),
                Forms\Components\RichEditor::make('notes')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('photo_path')
                    ->image()
                    ->directory('images')
                    ->imageEditor()
                    ->maxSize(1024) // Tamanho máximo em KB
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand')
                    ->searchable(),

                Tables\Columns\TextColumn::make('purchase_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('purchase_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('photo_path')
                    ->disk('public')
                    ->url(fn ($record) => Storage::url($record->photo_path)),

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
            'index' => Pages\ListTools::route('/'),
            'create' => Pages\CreateTool::route('/create'),
            'edit' => Pages\EditTool::route('/{record}/edit'),
        ];
    }
}
