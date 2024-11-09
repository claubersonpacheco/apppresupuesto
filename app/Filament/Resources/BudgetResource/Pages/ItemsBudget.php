<?php

namespace App\Filament\Resources\BudgetResource\Pages;

use App\Filament\Resources\BudgetResource;
use App\Models\Budget;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Fieldset;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Page;
use Filament\Infolists\Components\Grid;
use Livewire\Attributes\On;


class ItemsBudget extends Page
{
    use InteractsWithForms;

    protected static ?string $title = 'Items Presupuesto';

    public $record = null;

    public ?array $products = [];

    protected static ?string $model = Budget::class;
    protected static string $resource = BudgetResource::class;
    protected static string $view = 'filament.resources.budget-resource.pages.items-budget';

    public Budget $budget;

    public Product $product;

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
            ->color('warning'),
            Action::make('Voltar')
                ->url('/dashboard/budgets')
                ->icon('heroicon-o-arrow-uturn-left')
        ];
    }

    public function headertInfolist(Infolist $infolist): Infolist
    {

        return $infolist
            ->record($this->budget->customer) // Verifique se $this->budget está corretamente inicializado
            ->schema([
                Grid::make(3)
                    ->columnSpan([
                        'default' => 1,
                        'lg' => 1, // Define o span para cada coluna no layout maior
                    ])
                    // Cria um layout de grid com 3 colunas
                    ->schema([
                        Fieldset::make('Cliente')
                            ->schema([
                                TextEntry::make('created_at')
                                    ->inlineLabel()
                                    ->label('Fecha Presupuesto:')
                                    ->columnSpan(2)
                                    ->date('d/m/y'),
                                TextEntry::make('name')
                                    ->label('Nombre:')
                                    ->inlineLabel()
                                    ->alignLeft()
                                ,

                                TextEntry::make('email')
                                    ->label('E-mail:')
                                    ->inlineLabel(),
                                TextEntry::make('phone')
                                    ->label('Teléfono:')
                                    ->inlineLabel(),
                                TextEntry::make('address')
                                    ->label('Direccion:')
                                    ->inlineLabel(),

                            ])
                            ->extraAttributes(['class' => 'bg-white p-4 dark:bg-black ']) // Adiciona fundo branco
                            ->columnSpan(2),
                    ])
                ,
            ]);
    }


    #[On('refreshInfolist')]
    public function productInfolist(Infolist $infolist): Infolist
    {

        return $infolist
            ->record($this->budget->refresh())
            ->schema([
                Grid::make(3)
                    ->columnSpan([
                        'default' => 1,
                        'lg' => 1, // Define o span para cada coluna no layout maior
                    ])// Cria um layout de grid com 3 colunas
                    ->schema([
                        Section::make()
                            ->schema([
                                TextEntry::make('description')
                                    ->markdown()
                                ->label('Observación')
                            ])->columnSpan(1),
                        Section::make()
                            ->schema([
                                TextEntry::make('tax')
                                    ->label('Total IVA'),
                            ])->columnSpan(1),
                        Section::make()
                            ->schema([
                                TextEntry::make('total')
                                    ->label('Total Sin IVA'),
                                TextEntry::make('total_tax')
                                    ->label('Total c/ IVA'),
                            ])->columnSpan(1),
                    ])
                ,
            ]);
    }

}

