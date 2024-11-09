<?php

namespace App\Filament\Resources\BudgetResource\Pages;

use App\Filament\Resources\BudgetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBudget extends EditRecord
{
    protected static string $resource = BudgetResource::class;

    protected static ?string $title = 'Editar Presupuesto';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Voltar')
            ->url('/dashboard/budgets')
            ->icon('heroicon-o-arrow-uturn-left')
        ];
    }
}
