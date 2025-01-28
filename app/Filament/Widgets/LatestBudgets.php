<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BudgetResource;
use App\Models\Budget;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestBudgets extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                BudgetResource::getEloquentQuery()
            )
            ->defaultPaginationPageOption(10)
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Servicio')
                    ->searchable(),

                Tables\Columns\TextColumn::make('customer.name')
                    ->translateLabel()
                    ->sortable(),

                Tables\Columns\TextColumn::make('latestStatus.status_label')
                    ->badge()
                    ->label('Status')
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
                    ->dateTime()
                    ->sortable(),

            ])
            ->actions([
                Tables\Actions\Action::make('manage_items')
                    ->label('Servicios')
                    ->url(fn (Budget $record): string => "/dashboard/budgets/{$record->getKey()}/items")
                    ->icon('heroicon-o-cog'), // Adicione um ícone apropriado
                Tables\Actions\EditAction::make(),
            ]);
    }
}
