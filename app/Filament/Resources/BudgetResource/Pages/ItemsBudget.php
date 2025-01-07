<?php

namespace App\Filament\Resources\BudgetResource\Pages;

use App\Filament\Resources\BudgetResource;
use App\Models\Budget;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Resources\Pages\Page;

class ItemsBudget extends Page
{
    use InteractsWithForms;

    protected static ?string $title = 'Items Presupuesto';

    public $record = null;

    protected static ?string $model = Budget::class;
    protected static string $resource = BudgetResource::class;
    protected static string $view = 'filament.resources.budget-resource.pages.items-budget';

    public Budget $budget;
    public function mount(): void
    {
        $this->budget = Budget::query()->findOrFail($this->record);
    }

       protected function getHeaderActions(): array
    {
        return [

            Action::make('Imprimir')
                ->url(route('budget.print', $this->record))
                ->icon('heroicon-o-printer')
                ->color('warning')
                ->openUrlInNewTab(),
            Action::make('Generar PDF')
            ->url(route('budget.pdf', $this->record))
                ->icon('heroicon-o-printer')
                ->color('success'),
            Action::make('Voltar')
                ->url('/dashboard/budgets')
                ->icon('heroicon-o-arrow-uturn-left'),

        ];
    }

}

