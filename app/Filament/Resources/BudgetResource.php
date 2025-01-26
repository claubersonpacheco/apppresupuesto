<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BudgetResource\Pages;
use App\Filament\Resources\BudgetResource\RelationManagers;
use App\Models\Budget;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BudgetResource extends Resource
{
    protected static ?string $model = Budget::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Menu';
    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return __('Budget');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('Code')
                    ->translateLabel()
                    ->required()
                    ->disabled()
                    ->maxLength(20),

                Forms\Components\Select::make('customer_id')
                    ->label('Client')
                    ->translateLabel()
                    ->relationship('customer', 'name')
                    ->disabled(),


                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255)
                    ->columnSpan('full'),

                Forms\Components\RichEditor::make('description')
                    ->label('Description')
                    ->translateLabel()
                    ->maxLength(255)
                    ->columnSpan('full'),
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
                    ->label('Service')
                    ->translateLabel()
                    ->searchable(),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Client')
                    ->translateLabel()
                    ->sortable(),

                Tables\Columns\TextColumn::make('latestStatus.status_label')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state, $record) => match ($record->latestStatus?->status) {
                        1 => 'primary',    // Aberto
                        5 => 'success',    // Aprovado
                        3 => 'warning',    // Pendiente
                        4 => 'danger',     // Rechazado
                        6 => 'secondary',  // En proceso
                        7 => 'gray',       // Finalizado
                        2 => 'info',       // Enviado
                        default => 'gray', // Cor padrão caso não corresponda a nenhum status
                    })
                    ->formatStateUsing(fn ($state, $record) => $record->latestStatus?->status_label ?? 'Unknown'),


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
                Tables\Actions\Action::make('manage_items')
                    ->label('Service')
                    ->translateLabel()
                    ->url(fn (Budget $record): string => self::getUrl('items', ['record' => $record]))
                    ->icon('heroicon-o-cog'), // Adicione um ícone apropriado
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
            'index' => Pages\ListBudgets::route('/'),
            'create' => Pages\CreateBudget::route('/create'),
            'edit' => Pages\EditBudget::route('/{record}/edit'),
            'items' => Pages\ItemsBudget::route('/{record}/items'),
        ];
    }
}
