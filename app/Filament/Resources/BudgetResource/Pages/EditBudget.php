<?php

namespace App\Filament\Resources\BudgetResource\Pages;

use App\Filament\Resources\BudgetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBudget extends EditRecord
{
    protected static string $resource = BudgetResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make(__('Back'))
            ->url('/dashboard/budgets')
            ->icon('heroicon-o-arrow-uturn-left')
        ];
    }
}
