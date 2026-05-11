<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use App\Traits\HasDashboardFilter;

class ProfitChart extends ChartWidget
{
    use HasDashboardFilter;    // 📌 الفلتر (هيظهر تلقائي في Filament)
    protected ?string $heading = 'Profit Overview';

    protected function getData(): array
    {
        $companyId = auth()->user()->company_id;

        // 📅 آخر 7 أيام أرباح
        $data = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.company_id', $companyId)
            ->where('orders.created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(orders.created_at) as date,
                         SUM((sale_price - cost_price) * quantity) as profit')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Profit',
                    'data' => $data->pluck('profit'),
                    'borderColor' => '#22c55e',
                    'backgroundColor' => '#22c55e33',
                ],
            ],
            'labels' => $data->pluck('date'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
