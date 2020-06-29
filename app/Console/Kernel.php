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
        Commands\Market\Bittrex\BittrexGetMarket::class,
        Commands\Market\Bittrex\BittrexCoinUpdate::class,

        Commands\Market\Binance\BinanceGetMarket::class,

        Commands\BuildTimeIntervalData::class,
        Commands\PurgeOldData::class,

        Commands\WebSocketTimeInterval::class,

        // Unit Tests.
        Commands\UnitTest\TestMarketOps::class,

        // Monitor scripts.
        Commands\Monitor\CheckTick::class,

        //Create or Update user password with username
        Commands\CreateOrUpdateSuperUser::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('crypto:purge')->timezone('America/New_York')->dailyAt('00:10');

        $schedule->Command('bittrex:coinUpdate')->withoutOverlapping()->timezone('America/New_York')->everyFiveMinutes();

        $schedule->Command('crypto:buildTimeIntervalData')->withoutOverlapping()->timezone('America/New_York')->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
