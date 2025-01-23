<?php

namespace App\Livewire;

use App\Enum\ProductType;
use App\Models\Budget;
use App\Models\BudgetItem;

use App\Models\Product;
use App\Models\StatusHistory;
use App\Models\User;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\ColorEntry;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Grid as InfoGrid;

use Filament\Pages\Actions\ButtonAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Tables\Columns\Concerns\CanBeToggled;

use Illuminate\Support\Facades\Log;




class ListItemsBudget extends Component implements HasTable, HasForms, HasInfolists
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithInfolists;
    use CanBeToggled;

    public $budgetId;

    public $budget;

    public $visibleColumns = [];

    public $service, $qtd, $price, $description , $tax , $total ,$total_tax;

    public function mount($record)
    {
        $this->budgetId = $record->id;


        $this->budget = Budget::query()->findOrFail($record->id);


        if ($this->budget) {
            $this->service = (bool)$this->budget->show_service;
            $this->qtd = (bool)$this->budget->show_qtd;
            $this->price = (bool)$this->budget->show_price;
            $this->description = (bool)$this->budget->show_description;
            $this->tax = (bool)$this->budget->show_tax;
            $this->total = (bool)$this->budget->show_total;
            $this->total_tax = (bool)$this->budget->show_total_tax;

        }

        $this->visibleColumns = [
            'service' => $this->service,
            'description' => $this->description,
            'qtd' => $this->qtd,
            'price' => $this->price,
            'tax' => $this->tax,
            'total' => $this->total,
            'total_tax' => $this->total_tax,
        ];

    }



    public function headertInfolist(Infolist $infolist): Infolist
    {
        $this->budget = Budget::with(['customer', 'latestStatus'])->findOrFail($this->budgetId);

        return $infolist
            ->record($this->budget)
            ->schema([
                \Filament\Infolists\Components\Grid::make(3) // Grid principal com três colunas
                ->schema([
                    // Primeira Coluna: Cliente
                    Fieldset::make('Cliente')
                        ->schema([
                            TextEntry::make('customer.code')
                                ->label('Código del Cliente:')
                                ->suffixAction(
                                    Action::make('copiar')
                                        ->icon('heroicon-m-clipboard')

                                        ->action(function ($record) {
                                            $this->dispatch('clipboard:copied', $record->code);
                                        })
                                ),


                            TextEntry::make('created_at')
                                ->label('Fecha Presupuesto:')
                                ->date('d/m/y'),
                            TextEntry::make('customer.name')
                                ->label('Nombre:')
                                ->extraAttributes(['class' => 'border-b'])
                                ->suffixAction(
                                    Action::make('copiar')
                                        ->icon('heroicon-m-clipboard')

                                        ->action(function ($record) {
                                            $this->dispatch('clipboard:copied', $record->customer->name);
                                        })
                                ),
                            TextEntry::make('customer.document')
                                ->label('Dni/Nif:')
                                ->extraAttributes(['class' => 'border-b'])
                                ->suffixAction(
                                    Action::make('copiar')
                                        ->icon('heroicon-m-clipboard')

                                        ->action(function ($record) {
                                            $this->dispatch('clipboard:copied', $record->customer->document);
                                        })
                                ),

                            TextEntry::make('customer.email')
                                ->label('Correo Eletronico:')
                                ->suffixAction(
                                    Action::make('copiar')
                                        ->icon('heroicon-m-clipboard')

                                        ->action(function ($record) {
                                            $this->dispatch('clipboard:copied', $record->customer->email);
                                        })
                                )
                                ->extraAttributes(['class' => 'border-b']),
                            TextEntry::make('customer.phone')
                                ->label('Teléfono:')
                                ->suffixAction(
                                    Action::make('copiar')
                                        ->icon('heroicon-m-clipboard')

                                        ->action(function ($record) {
                                            $this->dispatch('clipboard:copied', $record->customer->phone);
                                        })
                                )
                                ->extraAttributes(['class' => 'border-b']),
                            TextEntry::make('customer.address')
                                ->label('Direccion:')
                                ->suffixAction(
                                    Action::make('copiar')
                                        ->icon('heroicon-m-clipboard')

                                        ->action(function ($record) {
                                            $this->dispatch('clipboard:copied', $record->customer->address);
                                        })
                                )
                                ->extraAttributes(['class' => 'border-b'])
                                ->columnSpan(2),
                        ])
                        ->extraAttributes(['class' => 'bg-white p-4 dark:bg-gray-900 dark:text-white border'])
                        ->columnSpan(2), // Coluna 1 do Grid

                    // Segunda Coluna: Status
                    Fieldset::make('Status')
                        ->schema([
                            TextEntry::make('status')
                                ->label('Status')

                                ->getStateUsing(function ($record) {
                                    if ($record->latestStatus) {
                                        $statusOptions = StatusHistory::getStatusOptions();
                                        return $statusOptions[$record->latestStatus->status] ?? 'Status desconhecido';
                                    }
                                    return 'Status desconhecido';
                                })
                                ->suffixAction(
                                    Action::make('alterarStatus')
                                        ->form([
                                            Select::make('status_id')
                                                ->label('Novo Status')
                                                ->options(StatusHistory::getStatusOptions())
                                                ->required(),
                                            Textarea::make('comments')
                                                ->label('Comentários')
                                                ->placeholder('Adicione seus comentários...')
                                                ->required(),
                                        ])
                                        ->icon('heroicon-m-pencil-square')
                                        ->action(function (array $data) {
                                            $idBudget = $this->budgetId;
                                            $budget = Budget::find($idBudget);

                                            if ($budget) {
                                                $budget->statusHistories()->create([
                                                    'status' => $data['status_id'],
                                                    'comments' => $data['comments'],
                                                    'changed_by' => auth()->id(),
                                                    'budget_id' => $idBudget,
                                                ]);
                                            } else {
                                                dd('Budget não encontrado');
                                            }
                                        })
                                        ->modalHeading('Alterar Status')
                                )
                                ->extraAttributes(function ($record) {
                                    $status = $record->latestStatus ? $record->latestStatus->status : 'STATUS_UNKNOWN';
                                    $statusColor = $this->getStatusColor($status);
                                    return ['class' => "$statusColor py-2 px-3 rounded-md"];
                                })
                                ->color('white')
                                ->columnSpan(2),

                            TextEntry::make('latestStatus.created_at')
                                ->label('Fecha:')
                                ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('d/m/y - H:i:s'))
                                ->columnSpan(2),
                            TextEntry::make('latestStatus.comments')
                                ->label('Comentarios:')
                                ->extraAttributes(['class' => 'bg-danger-500 text-white'])
                                ->columnSpan(2),
                        ])
                        ->extraAttributes(['class' => 'bg-white p-2 dark:bg-gray-900 dark:text-white'])
                        ->columnSpan(1), // Coluna 2 do Grid
                ]),
            ]);
    }


    private function getStatusColor($status)
    {
        // Aqui você define as cores com base no status
        switch ($status) {
            case '1':
                return 'status-open';   // Azul para "Aberto"
            case '2':
                return 'status-sent'; // Amarelo para "Enviado"
            case '3':
                return 'status-pending'; // Laranja para "Pendiente"
            case '4':
                return 'status-rejected';    // Vermelho para "Rechazado"
            case '5':
                return 'status-approved';  // Verde para "Aprovado"
            case '6':
                return 'status-in-process'; // Roxo para "Em processo"
            case '7':
                return 'status-completed';   // Cinza para "Finalizado"
            default:
                return 'status-default';   // Cor padrão para status desconhecido
        }
    }

    public function updateBudgetTotal()
    {
        $budget = Budget::find($this->budgetId);

        if ($budget) {
            $budgetTotal = $budget->items()->sum('total'); // Sumando los totales de los itens
            $budgetTotalTax = $budget->items()->sum('total_tax');
            $budgetTax = $budgetTotalTax - $budgetTotal;

            $budget->update([
                'total' => $budgetTotal,
                'total_tax' => $budgetTotalTax,
                'tax' => $budgetTax,
            ]); // Atualizando o total do orçamento
        }
    }

    public function updateVisibleColumns()
    {
        $this->visibleColumns = [
            'service' => $this->service,
            'description' => $this->description,
            'qtd' => $this->qtd,
            'price' => $this->price,
            'tax' => $this->tax,
            'total' => $this->total,
            'total_tax' => $this->total_tax,
        ];

        $budget = Budget::find($this->budgetId);


        if ($budget) {
            $budget->update([
                'show_service' => $this->service,
                'show_description' => $this->description,
                'show_qtd' => $this->qtd,
                'show_price' => $this->price,
                'show_tax' => $this->tax,
                'show_total' => $this->total,
                'show_total_tax' => $this->total_tax,
            ]);
        }
    }

    public function toggleColumnVisibility(string $column)
    {
        $this->visibleColumns[$column] = !$this->visibleColumns[$column];

        $this->dispatch('refreshInfolist');
    }

    // aqui busco los datos para inserir en la tabla
    public function table(Table $table): Table
    {

        return $table
            ->query(
                BudgetItem::query()
                    ->where('budget_id', $this->budgetId)
                    ->orderBy('sort_order') // Ordena pela coluna `sort_order`
            )
            ->columns([
                TextColumn::make('product.name')->label(__('Servicio'))
                    ->hidden(fn() => !$this->visibleColumns['service']),
                TextColumn::make('description')->label(__('Descripción'))
                    ->hidden(fn() => !$this->visibleColumns['description'])
                    ->html() // Permite renderizar HTML na coluna
                    ->wrap(),
                TextColumn::make('quantity')->label(__('Cant'))
                    ->hidden(fn() => !$this->visibleColumns['qtd']),
                TextColumn::make('price')->label(__('Unid Precio'))
                    ->hidden(fn() => !$this->visibleColumns['price']),

                TextColumn::make('tax')->label('Iva %')
                    ->hidden(fn() => !$this->visibleColumns['tax']),

                TextColumn::make('total')->label('Valor s/Iva')
                    ->hidden(fn() => !$this->visibleColumns['total']),

                TextColumn::make('total_tax')->label('Valor c/Iva')
                    ->hidden(fn() => !$this->visibleColumns['total_tax']),

            ])
            ->reorderable('sort_order') // Ativa o recurso de arrastar e soltar
            ->filters([
                //
            ])
            ->headerActions([

                CreateAction::make()
                    ->model(BudgetItem::class)
                    ->form([
                        Grid::make(4)
                            ->schema([
                                Hidden::make('budget_id')
                                    ->default(fn() => $this->budgetId) // Defina uma função para obter o `budget_id` atual
                                    ->required(),

                                Select::make('product_id')
                                    ->label(__('Service'))
                                    ->options(Product::all()->pluck('name', 'id'))
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set, $state) {
                                        $product = Product::find($state);
                                        if ($product) {
                                            $set('price', $product->price ?? 0);
                                            $set('description', $product->description ?? ''); // Define a descrição inicial
                                        }
                                    })
                                    ->columns(1),

                                TextInput::make('quantity')
                                    ->label(__('Quantity'))
                                    ->numeric()
                                    ->rules('numeric|min:0')
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set, $state, $get) {
                                        if ($state < 0) {
                                            $set('quantity', 0); // Define como 0 se for negativo
                                            return; // Sai da execução
                                        }

                                        $total = $get('price') * $state;
                                        $set('total', $total);
                                        $tax = (int)$get('tax') / 100;
                                        $set('total_tax', $total + ($total * $tax));
                                    }),

                                TextInput::make('price')
                                    ->label(__('Price Unit'))
                                    ->rules('numeric|min:0')
                                    ->required()
                                    ->numeric()
                                    ->rules('numeric|min:0')
                                    ->reactive()
                                    ->default(fn($get) => $get('product_id') ? Product::find($get('product_id'))->price : null)
                                    ->afterStateHydrated(function (callable $set, $state, $get) {
                                        if ($get('product_id')) {
                                            $product = Product::find($get('product_id'));
                                            if ($product) {
                                                $set('price', $product->price);
                                            }
                                        }
                                    })
                                    ->afterStateUpdated(function (callable $set, $state, $get) {
                                        if ($state < 0) {
                                            $set('price', 0); // Define como 0 se for negativo
                                            return; // Sai da execução
                                        }
                                        $total = $state * $get('quantity');
                                        $set('total', $total);
                                        $tax = (int)$get('tax') / 100;
                                        $set('total_tax', $total + ($total * $tax));
                                    }),

                                Select::make('tax')
                                    ->label('IVA')
                                    ->options([
                                        '0' => '0',
                                        '5' => '5%',
                                        '10' => '10%',
                                        '15' => '15%',
                                        '21' => '21%',
                                    ])
                                    ->reactive()
                                    ->default('0')
                                    ->afterStateUpdated(function (callable $set, $state, $get) {
                                        $total = $get('total');
                                        $tax = (int)$state / 100;
                                        $set('total_tax', $total + ($total * $tax));
                                    })
                                    ->columns(1),

                                RichEditor::make('description')
                                    ->label('Descripción del servicio')
                                    ->reactive()
                                    ->columnSpan(4),

                                TextInput::make('total')
                                    ->label('Total s/Iva')
                                    ->disabled()
                                    ->required(),

                                TextInput::make('total_tax')
                                    ->label('Total c/Iva')
                                    ->disabled()
                                    ->required(),
                            ])
                    ])
                    ->after(function () {
                        $this->updateBudgetTotal(); // Recalcula o total após criar o item
                        $this->dispatch('refreshInfolist');
                    })
                    ->label('Agregar Servícios'),
            ])
            ->actions([
                EditAction::make()
                    ->model(BudgetItem::class)
                    ->form([
                        Grid::make(4)
                            ->schema([
                                Hidden::make('budget_id')
                                    ->default(fn() => $this->budgetId) // Defina uma função para obter o `budget_id` atual
                                    ->required(),

                                Select::make('product_id')
                                    ->label('Produto')
                                    ->options(Product::all()->pluck('name', 'id'))
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn(callable $set, $state) => $set('price', Product::find($state)->price))
                                    ->columns(1),


                                TextInput::make('quantity')
                                    ->label('Quantidade')
                                    ->numeric()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set, $state, $get) {

                                        if ($state < 0) {
                                            $set('quantity', 0); // Define como 0 se for negativo
                                            return; // Sai da execução
                                        }

                                        $total = $get('price') * $state;
                                        $set('total', $total);
                                        $tax = (int)$get('tax') / 100;
                                        $set('total_tax', $total + ($total * $tax));
                                    }),

                                TextInput::make('price')
                                    ->label('Preço Unitário')
                                    ->required()
                                    ->numeric()
                                    ->rules('numeric|min:0')
                                    ->reactive()
                                    ->default(fn($get) => $get('product_id') ? Product::find($get('product_id'))->price : null)
                                    ->afterStateHydrated(function (callable $set, $state, $get) {
                                        if ($get('product_id')) {
                                            $product = Product::find($get('product_id'));
                                            if ($product) {
                                                $set('price', $product->price);
                                            }
                                        }
                                    })
                                    ->afterStateUpdated(function (callable $set, $state, $get) {
                                        if ($state < 0) {
                                            $set('price', 0); // Define como 0 se for negativo
                                            return; // Sai da execução
                                        }
                                        $total = $state * $get('quantity');
                                        $set('total', $total);
                                        $tax = (int)$get('tax') / 100;
                                        $set('total_tax', $total + ($total * $tax));
                                    }),


                                Select::make('tax')
                                    ->label('IVA')
                                    ->options([
                                        '0' => '0',
                                        '5' => '5%',
                                        '10' => '10%',
                                        '15' => '15%',
                                        '21' => '21%',
                                    ])
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set, $state, $get) {
                                        $total = $get('total');
                                        $tax = (int)$state / 100;
                                        $set('total_tax', $total + ($total * $tax));
                                    })
                                    ->columns(1),
                                RichEditor::make('description')
                                    ->label('Descripción del servicio')
                                    ->reactive()
                                    ->columnSpan(4),

                                TextInput::make('total')
                                    ->label('Total s/Iva')
                                    ->disabled()
                                    ->required(),

                                TextInput::make('total_tax')
                                    ->label('Total c/Iva')
                                    ->disabled()
                                    ->required(),
                            ])

                    ])
                    ->after(function () {
                        $this->updateBudgetTotal(); // Recalcula o total após criar o item
                        $this->dispatch('refreshInfolist');

                    }),
                DeleteAction::make()
                    ->after(function () {
                        // Recalcule o total após a exclusão
                        $this->updateBudgetTotal(); // Método para atualizar o total do orçamento
                        $this->dispatch('refreshInfolist'); // Dispara o evento para atualizar a lista
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }


    #[On('refreshInfolist')]
    public function productInfolist(Infolist $infolist): Infolist
    {

        return $infolist
            ->record($this->budget->refresh())
            ->schema([
                InfoGrid::make(3)
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
                                    ->label('Total IVA')
                                    ->hidden(fn() => !$this->visibleColumns['total_tax']),

                            ])->columnSpan(1),

                        Section::make()
                            ->schema([
                                TextEntry::make('total')
                                    ->label('Total Sin IVA')
                                    ->hidden(fn() => !$this->visibleColumns['total']),

                                TextEntry::make('total_tax')
                                    ->label('Total c/ IVA')
                                    ->hidden(fn() => !$this->visibleColumns['total_tax']),
                            ])->columnSpan(1),

                    ])
                ,
            ]);
    }

    public function render(): View
    {
        return view('livewire.list-items-budget');
    }


}
