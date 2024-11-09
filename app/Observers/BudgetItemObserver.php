<?php

namespace App\Observers;

use App\Models\Budget;
use App\Models\BudgetItem;

class BudgetItemObserver
{
    public function saving(BudgetItem $item)
    {
        // Verifica se o produto existe para evitar erros
        if ($item->product) {
            // Calcula o total do item (quantidade * preço unitário)
            $total = $item->quantity * $item->product->price;

            // Atualiza o total e o total com IVA
            $item->total = $total;
            $item->total_tax = $total + (($total * $item->tax) / 100);
        } else {
            // Opcional: lidar com a situação onde o produto não é encontrado
            $item->total = 0;
            $item->total_tax = 0;
        }
    }

}
