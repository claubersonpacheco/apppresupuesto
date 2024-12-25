<?php

namespace App\Livewire;

use App\Enum\ProductType;
use App\Models\Budget;
use App\Models\BudgetItem;

use App\Models\Product;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Infolist;
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




class ListItemsBudget extends Component implements HasTable, HasForms, HasInfolists
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithInfolists;
    use CanBeToggled;

    public $budgetId;

    public Budget $budget;

    public $visibleColumns = [];
    public $tax = false;
    public $total = true;
    public $total_tax = false;



    public function mount($record)
    {
        $this->budgetId = $record->id;

        $budget = Budget::query()->findOrFail($record->id);

        $this->budget = $budget;

        if($budget){

            $this->tax = (bool) $budget->show_tax;
            $this->total = (bool) $budget->show_total;
            $this->total_tax = (bool) $budget->show_total_tax;

            logger('Valores iniciais:', [
                'tax' => $this->tax,
                'total' => $this->total,
                'total_tax' => $this->total_tax,
            ]);

        }

        $this->visibleColumns = [
            'tax' => $this->tax,
            'total' => $this->total,
            'total_tax' => $this->total_tax,
        ];



    }



    //-----header infolist
    public function headertInfolist(Infolist $infolist): Infolist
    {

        return $infolist
            ->record($this->budget->customer) // Verifique se $this->budget está corretamente inicializado
            ->schema([
                \Filament\Infolists\Components\Grid::make(3)
                    ->columnSpan([
                        'default' => 1,
                        'lg' => 1, // Define o span para cada coluna no layout maior
                    ])
                    // Cria um layout de grid com 3 colunas
                    ->schema([
                        Fieldset::make('Cliente')
                            ->schema([
                                TextEntry::make('code')
                                    ->inlineLabel()
                                    ->label('Codigo del Cliente:')
                                    ->columnSpan(2),
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
                            ->extraAttributes(['class' => 'bg-white p-4 dark:bg-gray-900 dark:text-white']) // Adiciona fundo branco
                            ->columnSpan(2),
                    ])
                ,
            ]);
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
            'tax' => $this->tax,
            'total' => $this->total,
            'total_tax' => $this->total_tax,
        ];

        $budget = Budget::find($this->budgetId);


        if ($budget) {
            $budget->update([
                'show_tax' =>  $this->tax,
                'show_total' => $this->total,
                'show_total_tax' =>  $this->total_tax,
            ]);
        }
    }

    public function toggleColumnVisibility(string $column)
    {
        $this->visibleColumns[$column] = !$this->visibleColumns[$column];

        // Atualiza a tabela e o Infolist
        $this->dispatch('refreshInfolist');
    }

    // aqui busco los datos para inserir en la tabla
    public function table(Table $table): Table
    {

        return $table
            ->query(BudgetItem::query()->where('budget_id', $this->budgetId ))
            ->columns([
                TextColumn::make('product.name')->label(__('Servicio')),
                TextColumn::make('description')->label(__('Descripción'))
                    ->html() // Permite renderizar HTML na coluna
                    ->wrap(),

                TextColumn::make('tax')->label('Iva %')

                    ->hidden(fn() => !$this->visibleColumns['tax']),

                TextColumn::make('total')->label('Valor s/Iva')

                    ->hidden(fn() => !$this->visibleColumns['total']),

                TextColumn::make('total_tax')->label('Valor c/Iva')

                    ->hidden(fn() => !$this->visibleColumns['total_tax']),

            ])
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
                                    ->label('Produto')
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
                                    ->label('Quantidade')
                                    ->numeric()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set, $state, $get) {
                                        $total = $get('price') * $state;
                                        $set('total', $total);
                                        $tax = (int) $get('tax') / 100;
                                        $set('total_tax', $total + ($total * $tax));
                                    }),

                                TextInput::make('price')
                                    ->label('Preço Unitário')
                                    ->required()
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
                                        $total = $state * $get('quantity');
                                        $set('total', $total);
                                        $tax = (int) $get('tax') / 100;
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
                                        $tax = (int) $state / 100;
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
                    ->label('Add Servicios'),
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
                                        $total = $get('price') * $state;
                                        $set('total', $total);
                                        $tax = (int) $get('tax') / 100;
                                        $set('total_tax', $total + ($total * $tax));
                                    }),

                                TextInput::make('price')
                                    ->label('Preço Unitário')
                                    ->required()
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
                                        $total = $state * $get('quantity');
                                        $set('total', $total);
                                        $tax = (int) $get('tax') / 100;
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
                                        $tax = (int) $state / 100;
                                        $set('total_tax', $total + ($total * $tax));
                                    })
                                    ->columns(1),


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
                \Filament\Infolists\Components\Grid::make(3)
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
