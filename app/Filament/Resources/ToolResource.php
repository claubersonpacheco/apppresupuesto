<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ToolResource\Pages;
use App\Filament\Resources\ToolResource\RelationManagers;
use App\Models\Tool;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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

    protected static ?string $navigationGroup = 'Administración';

    protected static ?string $navigationIcon = 'heroicon-o-wrench';

    protected static ?int $navigationSort = 12;

    public static function getModelLabel(): string
    {
        return __('Tool');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->translateLabel()
                    ->disabled()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->translateLabel()
                    ->required()
                    ->maxLength(100),
                Select::make('category_id')
                    ->translateLabel()
                    ->relationship('category', 'name')
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->translateLabel()
                            ->required(),
                        Forms\Components\RichEditor::make('description')
                            ->translateLabel()
                            ->columnSpanFull(),
                    ]),
                Forms\Components\TextInput::make('brand')
                    ->translateLabel()
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('model')
                    ->translateLabel()
                    ->maxLength(50),
                Forms\Components\TextInput::make('serial_number')
                    ->translateLabel()
                    ->maxLength(50),
                Forms\Components\TextInput::make('condition')
                    ->translateLabel()
                    ->required()
                    ->maxLength(20),
                Forms\Components\DatePicker::make('purchase_date')
                    ->translateLabel()
                    ->required(),
                Forms\Components\TextInput::make('purchase_price')
                    ->translateLabel()
                    ->numeric(),
                FileUpload::make('invoice')
                    ->translateLabel()
                    ->image()
                    ->directory('tools/invoices')
                    ->imageEditor()
                    ->maxSize(1024) // Tamanho máximo em KB
                    ->nullable(),
                RichEditor::make('notes')
                    ->translateLabel()
                    ->columnSpanFull(),
                FileUpload::make('photo_path')
                    ->label("Photo")
                    ->translateLabel()
                    ->image()
                    ->directory('tools/images')
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
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand')
                    ->translateLabel()
                    ->searchable(),

                Tables\Columns\TextColumn::make('purchase_date')
                    ->translateLabel()
                    ->dateTime('d/m/Y')
                    ->sortable(),
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
