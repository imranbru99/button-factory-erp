<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\FactoryAlertNotification;
use App\Services\Insights\InsightService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DailyProductionReportJob implements ShouldQueue
{
    use Queueable;

    public function handle(InsightService $insights): void
    {
        $stats = $insights->snapshot();
        $body = "Output {$stats['output_today']} pcs. Scrap {$stats['scrap_rate']}%. OEE {$stats['oee']}%. Open issues {$stats['open_issues']}.";

        User::query()->whereIn('role', ['super_admin', 'production_manager'])->where('is_active', true)->get()
            ->each(fn (User $user) => $user->notify(new FactoryAlertNotification('Daily production report', $body, url('/command'))));
    }
}
