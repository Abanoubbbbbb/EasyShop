<?php

namespace App\Traits;

trait HasDashboardFilter
{
    public string $period = 'month';

    public function getDateRange()
    {
        return match ($this->period) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'month' => [now()->startOfMonth(), now()->endOfMonth()],
            'year'  => [now()->startOfYear(), now()->endOfYear()],
        };
    }
}
