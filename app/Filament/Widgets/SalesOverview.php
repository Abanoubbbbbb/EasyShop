<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use App\Traits\HasDashboardFilter;



class SalesOverview extends StatsOverviewWidget
{
    use HasDashboardFilter;    // 📌 الفلتر (هيظهر تلقائي في Filament)
    public ?string $filter = 'month';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'month' => 'This Month',
            'year'  => 'This Year',
        ];
    }

    private function getDateRange()
    {
        return match ($this->filter) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'month' => [now()->startOfMonth(), now()->endOfMonth()],
            'year'  => [now()->startOfYear(), now()->endOfYear()],
            default  => [now()->startOfMonth(), now()->endOfMonth()],
        };
    }

    protected function getStats(): array
    {
        $companyId = auth()->user()->company_id;

        [$from, $to] = $this->getDateRange();

        // 💰 Sales
        $sales = Order::where('company_id', $companyId)
            ->whereBetween('created_at', [$from, $to])
            ->sum('total_price');

        // 📦 Orders
        $orders = Order::where('company_id', $companyId)
            ->whereBetween('created_at', [$from, $to])
            ->count();

        // 📈 Profit
        $profit = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.company_id', $companyId)
            ->whereBetween('orders.created_at', [$from, $to])
            ->selectRaw('SUM((sale_price - cost_price) * quantity) as profit')
            ->value('profit');

        return [
            Stat::make('Total Sales', number_format($sales ?? 0) . ' EGP')
                ->description(ucfirst($this->filter) . ' sales')
                ->color('success'),

            Stat::make('Total Profit', number_format($profit ?? 0) . ' EGP')
                ->description('Net profit')
                ->color('primary'),

            Stat::make('Orders', $orders)
                ->description('Total orders')
                ->color('warning'),
        ];
    }
}
