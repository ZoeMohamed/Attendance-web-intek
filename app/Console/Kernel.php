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
        Commands\GetDataAttend::class,
        Commands\GetDataAttendOut::class,
        Commands\PushNotification::class,
        Commands\GetDataToday::class,
        Commands\randomQuote::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('get:attend')
        //     ->dailyAt('23:00')->withoutOverlapping();
        $schedule->command('get:attend')
            ->everyMinute()->withoutOverlapping();
        $schedule->command('getattend:out')
            ->everyMinute()->withoutOverlapping();
        $schedule->command('getattenda:today')
            ->everyMinute()->withoutOverlapping();
        $schedule->command('push:notification')
            ->everyMinute()->withoutOverlapping();

        $schedule->command('random:quote')
            ->dailyAt('07:00')->withoutOverlapping();
        // $schedule->command('push:notification')
        //     ->dailyAt('10:01')->withoutOverlapping();

        // $schedule->command('push:notification')
        //     ->dailyAt('13:29')->withoutOverlapping();
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
