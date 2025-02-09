<?php

namespace App\Providers;

use App\Models\Budget;
use App\Models\BudgetItem;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;


use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['es','en','pt_br']); // also accepts a closure
        });

    }
}
