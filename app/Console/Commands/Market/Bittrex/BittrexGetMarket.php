<?php

namespace App\Console\Commands\Market\Bittrex;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use App\Vendor\Bittrex\BittrexClient;

use App\Database\Market;
use App\Database\MarketCoinAs;
use App\Database\Coin;
use App\Database\Tick;

// Bittrex market:

// Volume is the amount traded in that altcoin over the past 24 hours.
// In the case of BTC-DGB, this is the amount of DGB that has been traded in 24 hours.
// BaseVolume is the total value traded in the base currency, for example Bitcoin.

class BittrexGetMarket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bittrex:getMarket';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queries the market prices for bittrex';

    private $market;
    private $time;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        date_default_timezone_set('America/New_York');

        DB::beginTransaction();
        $this->market = Market::where('alias', '=', 'bittrex')->firstOrFail();
        $this->time = date('Y-m-d H:i:s');
        $this->populateTickTable ();

        DB::commit();        
    }

    function populateTickTable ()
    {
        $client = new BittrexClient();

        $dbCoinsList = $this->getDBCoins ();
        $marketSummaries = $client->getMarketSummaries();

        foreach ($marketSummaries as $marketSummary) {
            $marketName = $marketSummary->MarketName;

            $coinId = 0;
            if (!array_key_exists($marketName, $dbCoinsList)) {
                $coinId = $this->addCoinRecord ($marketSummary);
            } else {
                $coinId = $dbCoinsList[$marketName];
            }

            $this->addTick ($marketSummary, $coinId);
        }
    }

    function addCoinRecord ($marketSummary)
    {
        $arr = explode("-", $marketSummary->MarketName);
        $coinName = $arr[1];
        $currency = $arr[0];

        $coin = new Coin;
        $coin->market_id = $this->market->id;
        $coin->name = $coinName;
        $coin->currency = $currency;
        $coin->save();

        return $coin->id;
    }

    function addTick ($marketSummary, $coinId)
    {
        $tick = new Tick;
        $tick->coin_id = $coinId;
        $tick->time = $this->time;
        $tick->price = $marketSummary->Last;
        $tick->base_volume_24hrs = $marketSummary->BaseVolume;
        $tick->save();
    }

    function getDBCoins () 
    {
        $query = DB::select(
            "SELECT coin.id,
                    coin.name,
                    coin.currency
             FROM coin
             WHERE coin.market_id = :market_id",
             ["market_id" => $this->market->id]);

        $dbCoinsList = array();
        foreach ($query as $coin) {
            $coinCurrency = $coin->currency . "-" . $coin->name;
            $dbCoinsList[$coinCurrency] = $coin->id;
        }
        return $dbCoinsList;
    }
}
