<?php

namespace App\Observers;

use App\Models\Budget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BudgetObserver
{
    /**
     * Handle the Budget "creating" event.
     */
    public function creating(Budget $budget): void
    {
        Log::info('Código antes de salvar: ' . $budget->code);

        $budget->user_id = Auth::user()->id;
    }

}
