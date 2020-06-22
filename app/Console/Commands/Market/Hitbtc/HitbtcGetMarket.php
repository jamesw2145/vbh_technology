<?php

namespace App\Console\Commands\Market\Hitbtc;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;

use App\Vendor\Hitbtc\HitBtcAPIPublic;

use App\Database\Market;
use App\Database\Tick;

class HitbtcGetMarket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hitbtc:getMarket';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queries the market prices for hitbtc';

    private $marketSummaries = array();


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
    public function handle()
    {
        date_default_timezone_set('America/New_York');
        $time = date('Y-m-d H:i:s');

        DB::beginTransaction();

        $this->getMarketData();

        $market = "hitbtc";
        $market = Market::where('alias', '=', $market)->firstOrFail();

        $query = DB::select(
            "SELECT coin.id,
                    coin.name,
                    coin.currency
             FROM coin left join market_coin_as on coin.id=market_coin_as.coin_id
             WHERE market_coin_as.market_id = :market_id
               AND (market_coin_as.active = 'Y' OR market_coin_as.building='Y')",
             ["market_id" => $market->id]);       

        foreach ($query as $coin) {
            $marketSum = $this->getMarketSummary($coin->name, $coin->currency);
            if ($marketSum == null)
                continue;

            $tick = new Tick;
            $tick->market_id = $market->id;
            $tick->coin_id = $coin->id;
            $tick->time = $time;
            $tick->price = $marketSum['last'];
            $tick->base_volume_24hrs = $marketSum['volumeQuote'];
            $tick->save();
        }

        DB::commit();
    }

    private function getMarketData() {
        $client = new HitBtcAPIPublic();
        $tickers = $client->getTicker();
        foreach ($tickers as $tick) {
            $symbol = $tick['symbol'];
            $this->marketSummaries[$symbol] = $tick;
        }
    }

    private function getMarketSummary($name, $currency) {
        $symbol = $name . $currency;
        if (isset($this->marketSummaries[$symbol]))
            return $this->marketSummaries[$symbol];
        return null;
    }

}
