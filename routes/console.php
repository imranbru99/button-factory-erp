<?php

use App\Jobs\CheckReorderPointsJob;
use App\Jobs\DailyProductionReportJob;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new CheckReorderPointsJob)->hourly();
Schedule::job(new DailyProductionReportJob)->dailyAt('18:00');
