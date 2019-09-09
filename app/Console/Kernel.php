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
//        $schedule->command('water:off 0')
//            ->hourlyAt(10);
//        $schedule->command('water:on 1')
//            ->hourlyAt(15);
//        $schedule->command('water:on 2')
//            ->hourlyAt(20);
//        $schedule->command('water:on 3')
//            ->hourlyAt(25);
//        $schedule->command('water:off 0')
//            ->hourlyAt(30);
//        $schedule->command('water:check')
//            ->cron('*/3 * * * *');
        $schedule->command('mqtt:checkOnline')
            ->cron('*/3 * * * *');
        $schedule->command('weather:index')
            ->cron('2  */1 * * *');
        $schedule->command('weather:index')
            ->cron('30 */1 * * *');
        $schedule->command('mqtt:save')
            ->cron('30 */1 * * *');
        $schedule->command('telegram:weather')
            ->cron('1  7   * * *');
        $schedule->command('schedule run')
            ->cron('* * * * *');

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
