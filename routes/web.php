<?php

use App\Http\Controllers\Dashboard\Print\BudgetController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard/budget/{id}/print', [BudgetController::class, 'printItemsBudget'] )->name('budget.print');
