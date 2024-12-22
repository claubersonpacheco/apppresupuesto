<?php

namespace App\Livewire;

use App\Enum\ProductType;
use App\Models\Budget;
use App\Models\BudgetItem;

use App\Models\Category;
use App\Models\Product;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Infolist;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;
use Filament\Infolists\Contracts\HasInfolists;



class ListItemsBudget extends Component implements HasTable, HasForms, HasInfolists
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithInfolists;

    public $budgetId;

    public $budget;

    //utilizo un evento para actualizar el database
    protected $listeners = ['refreshInfolist' => '$refresh'];

    public function mount($record)
    {
        $this->budgetId = $record->id;
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

    public array $visibleColumns = [
        'product.name' => true,
        'description' => true,
        'tax' => false,
        'total' => true,
        'total_tax' => false,
    ];


    // aqui busco los datos para inserir en la tabla
    public function table(Table $table): Table
    {

        return $table
            ->query(BudgetItem::query())
            ->columns([
                TextColumn::make('product.name')->label(__('Servicio')),
                TextColumn::make('description')->label(__('Descripción'))
                    ->html() // Permite renderizar HTML na coluna
                    ->wrap(),
                TextColumn::make('tax')->label('Iva %')
                ->toggleable(isToggledHiddenByDefault: true)
                    ->hidden(fn() => !$this->visibleColumns['tax']),
                TextColumn::make('total')->label('Valor s/Iva')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->hidden(fn() => !$this->visibleColumns['total']),
                TextColumn::make('total_tax')->label('Valor c/Iva')
                    ->toggleable(isToggledHiddenByDefault: true),

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
                    ->label('Add Items'),
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


    public function toggleColumnVisibility(string $columnKey)
    {
        $this->visibleColumns[$columnKey] = !$this->visibleColumns[$columnKey];
    }

    // Método para gerar PDF
    public function generatePDF()
    {
        // Filtrar colunas visíveis
        $columnsToPrint = array_keys(array_filter($this->visibleColumns));

        // Obter os itens com as colunas visíveis
        $items = BudgetItem::all()->map(function ($item) use ($columnsToPrint) {
            return $item->only($columnsToPrint);
        });

        // Geração do PDF (ajuste o caminho da view e os dados conforme necessário)
        $pdf = Pdf::loadView('pdf.budget_items', [
            'items' => $items,
            'columnsToPrint' => $columnsToPrint,
        ]);

        // Retornar o download ou exibir no navegador
        return response()->streamDownload(
            fn() => print($pdf->stream()),
            'budget_items.pdf'
        );
    }


    public function render(): View
    {
        return view('livewire.list-items-budget');
    }

}
