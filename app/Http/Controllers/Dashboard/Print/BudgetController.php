<?php

namespace App\Http\Controllers\Dashboard\Print;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function printItemsBudget($id)
    {


        return view('print.budget.items-budget', [
            'request' => $id
        ]);

    }
}
