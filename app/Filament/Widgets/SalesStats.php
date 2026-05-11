<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $companyId = auth()->user()->company_id;

        // 📅 Today
        $today = Order::where('company_id', $companyId)
            ->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
            ->sum('total_price');

        // 📅 Month
        $month = Order::where('company_id', $companyId)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('total_price');

        // 📅 Year
        $year = Order::where('company_id', $companyId)
            ->whereBetween('created_at', [now()->startOfYear(), now()->endOfYear()])
            ->sum('total_price');

        return [
            Stat::make('Today Sales', number_format($today) . ' EGP')
                ->description('Sales today')
                ->color('success'),

            Stat::make('This Month', number_format($month) . ' EGP')
                ->description('Monthly sales')
                ->color('primary'),

            Stat::make('This Year', number_format($year) . ' EGP')
                ->description('Yearly sales')
                ->color('warning'),
        ];
    }
}
