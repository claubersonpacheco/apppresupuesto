<?php

namespace App\Filament\Widgets;

use App\Models\Budget;
use App\Models\Customer;
use App\Models\StatusHistory;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $pollingInterval = null;
    protected function getStats(): array
    {
        return [
            Stat::make('Total Customers', Customer::count())
                ->label('Total Clientes')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Total Presupuestos', StatusHistory::where('status', StatusHistory::STATUS_PENDING)->count()  )
                ->label('Presupuestos Pendientes')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([6,3,8, 5, 3,0 ,7]),
        ];
    }
}
