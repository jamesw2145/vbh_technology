<?php

namespace App\Console\Commands\UnitTest;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use App\BLogic\TableOps\MarketOps;
use App\BLogic\TableOps\MarketModel;


class TestMarketOps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unittest:marketops';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unit test the MarketOps.';

    private $marketops;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct ()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle () {
        date_default_timezone_set('America/New_York');

        $this->marketOps = new MarketOpsTestImpl ();

        $this->test ("btc->btc", 1, "btc", "btc", 1);
        $this->test ("usd->usd", 1, "usd", "usd", 1);
        $this->test ("eth->eth", 1, "eth", "eth", 1);
        $this->test ("usdt->usdt", 1, "usdt", "usdt", 1);

        $this->test ("usd->btc", 3371.7, "usd", "btc", 1);
        $this->test ("eth->btc", 33.094, "eth", "btc", 1);
        $this->test ("usdt->btc", 3412.65, "usdt", "btc", 1);

        $this->test ("btc->usd", 1, "btc", "usd", 3371.7, 0.01);
        $this->test ("btc->eth", 1, "btc", "eth", 33.075);
        $this->test ("btc->usdt", 1, "btc", "usdt", 3412.65, 0.01);
    }

    function test ($msg, $price, $currencyFrom, $currencyTo, $shouldBePrice, $tolerance=0.001)
    {
        $results = $this->marketOps->convertWithMarketId (1, $price, $currencyFrom, $currencyTo);
        $this->shouldBe ($msg, $results, $shouldBePrice, $tolerance);
    }

    function shouldBe ($msg, $price, $shouldBePrice, $tolerance)
    {
        $calculated = abs ($shouldBePrice - $price);

        $passed = "PASSED";
        if ($calculated > $tolerance) {
            $passed = "FAILED";
        }
        print ("$msg [resuls=$price, shouldBePrice=$shouldBePrice, tolerance=$tolerance] : $passed\n");
    }
}

class MarketOpsTestImpl extends MarketOps
{
    public function __construct () {
        $model = new MarketModel();
        $model->id = 1;
        $model->alias = "bittrex";
        $model->usd_to_btc = 0.000296586;
        $model->eth_to_btc = 0.0302338;
        $model->usdt_to_btc = 0.000293027;
        $model->btc_to_usd = 3371.7;
        $this->model[] = $model;
    }
}
