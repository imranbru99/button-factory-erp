<?php

namespace App\Filament\Widgets;

use App\Services\Insights\InsightService;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FactoryOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $stats = app(InsightService::class)->snapshot();

        return [
            Stat::make('Output today', number_format($stats['output_today']))
                ->description($stats['clock'].' Dhaka')
                ->color('success'),
            Stat::make('Scrap rate', $stats['scrap_rate'].'%')
                ->description('Completed stages today')
                ->color($stats['scrap_rate'] > 8 ? 'danger' : 'success'),
            Stat::make('On-time delivery', $stats['on_time'].'%')
                ->description($stats['overdue_sales'].' overdue orders')
                ->color('warning'),
            Stat::make('Machine OEE', $stats['oee'].'%')
                ->description($stats['open_issues'].' open issues')
                ->color('info'),
        ];
    }
}
