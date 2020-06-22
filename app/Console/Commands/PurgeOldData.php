<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;

// The purging.
class PurgeOldData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crypto:purge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purges old data.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        date_default_timezone_set('America/New_York');

        $sql = "DELETE FROM moving_average WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 day)";
        DB::select($sql);

        $sql = "DELETE FROM candlestick WHERE created_at < DATE_SUB(NOW(), INTERVAL 21 day)";
        DB::select($sql);

        $sql = "DELETE FROM time_interval WHERE created_at < DATE_SUB(NOW(), INTERVAL 21 day)";
        DB::select($sql);
    }
}
