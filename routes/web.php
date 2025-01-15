<?php

use App\Http\Controllers\Dashboard\Print\BudgetController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard/budget/{id}/print', [BudgetController::class, 'print'] )->name('budget.print');
Route::get('/dashboard/budget/{id}/generate-pdf', [BudgetController::class, 'generatePDF'] )->name('budget.pdf');
Route::get('/dashboard/teste', [\App\Http\Controllers\TexteController::class, 'index'])->name('teste');
