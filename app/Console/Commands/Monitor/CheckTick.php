<?php

namespace App\Console\Commands\Monitor;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;

class CheckTick extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:checktick';

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

        $lastTickSeconds = $this->lastTickTimeSeconds ();
        $nowSeconds = strtotime("now");
        $diffSeconds = $nowSeconds - $lastTickSeconds;
        print ("$diffSeconds\n");
    }

    function lastTickTimeSeconds ()
    {
        $sql = "SELECT time
                from tick
                order by id desc
                limit 1";
        $result = DB::select ($sql);

        if (count ($result) == 0) {
            return 0;
        }

        $lastTickTime = $result[0]->time;
        return strtotime($lastTickTime);
    }
}
