<?php

namespace App\Console;

use App\Jobs\CallApiAndSaveCurrencyData;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 1/min is minimum meaningful frequency for cron
        // For faster speed there should be daemon
        // And as mentioned in process itself this is not even good solution
        $schedule->call(function () {
                CallApiAndSaveCurrencyData::dispatch();
            })
            ->everyMinute()
            ->evenInMaintenanceMode();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
