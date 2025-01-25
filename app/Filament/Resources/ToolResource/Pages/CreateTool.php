<?php

namespace App\Filament\Resources\ToolResource\Pages;

use App\Filament\Resources\ToolResource;
use App\Models\Tool;
use App\Traits\GeneratesAutomaticCode;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;

class CreateTool extends CreateRecord
{
    protected static string $resource = ToolResource::class;
    use GeneratesAutomaticCode;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')
                    ->translateLabel()
                    ->default(fn () => $this->generateCode(Tool::class)) // Define o próximo código como padrão
                    ->required()
                    ->maxLength(255),
                TextInput::make('name')
                    ->translateLabel()
                    ->required()
                    ->maxLength(100),
                Select::make('category_id')
                    ->translateLabel()
                    ->relationship('category', 'name')
                    ->required(),
                TextInput::make('brand')
                    ->translateLabel()
                    ->required()
                    ->maxLength(50),
                TextInput::make('model')
                    ->translateLabel()
                    ->maxLength(50),
                TextInput::make('serial_number')
                    ->translateLabel()
                    ->maxLength(50),
                TextInput::make('condition')
                    ->translateLabel()
                    ->required()
                    ->maxLength(20),
                DatePicker::make('purchase_date')
                    ->translateLabel()
                    ->required(),
                TextInput::make('purchase_price')
                    ->translateLabel()
                    ->numeric(),
                TextInput::make('storage_location')
                    ->translateLabel()
                    ->maxLength(50),
                RichEditor::make('notes')
                    ->translateLabel()
                    ->columnSpanFull(),
                FileUpload::make('photo_path')
                    ->label("Photo")
                    ->translateLabel()
                    ->image()
                    ->directory('images')
                    ->imageEditor()
                    ->maxSize(1024) // Tamanho máximo em KB
                    ->nullable(),
            ]);
    }
}
