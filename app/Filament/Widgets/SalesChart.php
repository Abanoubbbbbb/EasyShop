<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use App\Traits\HasDashboardFilter;


class SalesChart extends ChartWidget
{
    use HasDashboardFilter;    // 📌 الفلتر (هيظهر تلقائي في Filament)
    protected ?string $heading = 'Sales Overview';

    protected function getData(): array
    {
        $companyId = auth()->user()->company_id;

        $data = Order::selectRaw('DATE(created_at) as date, SUM(total_price) as total')
            ->where('company_id', $companyId)
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Sales',
                    'data' => $data->pluck('total'),
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
