<?php

namespace App\Jobs;

use App\Services\Inventory\ReorderService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CheckReorderPointsJob implements ShouldQueue
{
    use Queueable;

    public function handle(ReorderService $reorder): void
    {
        $reorder->scan();
    }
}
