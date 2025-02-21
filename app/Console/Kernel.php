<?php

namespace App\Console;

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
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('abyss:recalc')->cron('0 12 * * *')->withoutOverlapping()->runInBackground();
        $schedule->command('abyss:checksys')->everyMinute()->withoutOverlapping()->runInBackground();
        $schedule->command('abyss:igdonations')->hourly()->withoutOverlapping()->runInBackground();
        $schedule->command('abyss:clearsearch')->daily()->withoutOverlapping()->runInBackground();
        $schedule->command('abyss:get-missing-metadata')->hourly()->withoutOverlapping()->runInBackground();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
