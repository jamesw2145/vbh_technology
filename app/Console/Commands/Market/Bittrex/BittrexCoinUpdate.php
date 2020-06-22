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

class BittrexCoinUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bittrex:coinUpdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update coin information for bittrex';

    private $market;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        date_default_timezone_set('America/New_York');

        DB::beginTransaction();
        $this->market = Market::where('alias', '=', 'bittrex')->firstOrFail();
        $this->updateCoinInformation ();
        DB::commit();        
    }

    function updateCoinInformation ()
    {
        $client = new BittrexClient();

        $dbCoinsList = $this->getDBCoins ();
        $markets = $client->getmarkets();

        foreach ($markets as $market) {
            $marketName = $market->MarketName;

            $coinId = 0;
            if (array_key_exists($marketName, $dbCoinsList)) {
                $coinId = $dbCoinsList[$marketName];
                $this->updateCoinRecord ($coinId, $market);
            }
        }
    }

    function updateCoinRecord ($coinId, $marketSummary)
    {
        $coin = Coin::find ($coinId);
        $coin->minimum_trade_size = $marketSummary->MinTradeSize;
        $coin->is_active = $this->isActrive ($marketSummary);
        $coin->is_going_offline = $this->isGoingOffline ($marketSummary);
        $coin->save();
    }

    function isGoingOffline ($marketSummary)
    {
        if (strpos($marketSummary->Notice, "market will be removed on") !== false) {
            return true;
        }

        return false;
    }

    function isActrive ($marketSummary)
    {
        if ($marketSummary->IsActive && !$marketSummary->IsRestricted) {
            return 1;
        }
        return 0;
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
