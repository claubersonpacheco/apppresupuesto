<?php

use App\Http\Controllers\Dashboard\Print\BudgetController;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Cache;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard/budget/{id}/email', [BudgetController::class, 'sendEmail'] )->name('budget.email');
Route::get('/dashboard/budget/{id}/print', [BudgetController::class, 'print'] )->name('budget.print');
Route::get('/dashboard/budget/{id}/generate-pdf', [BudgetController::class, 'generatePDF'] )->name('budget.pdf');
Route::get('/dashboard/teste', [\App\Http\Controllers\TexteController::class, 'index'])->name('teste');

Route::get('/active-users', function () {
    $users = Cache::remember('active_users', 60, function () {
        return User::where('active', 1)->get();
    });

    return view('active_users', ['users' => $users]);
});
