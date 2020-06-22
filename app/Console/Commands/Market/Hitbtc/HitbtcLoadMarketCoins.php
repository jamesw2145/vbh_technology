<?php

namespace App\Console\Commands\Market\Hitbtc;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use App\Vendor\Hitbtc\HitBtcAPIPublic;

use App\Database\Market;
use App\Database\MarketCoinAs;
use App\Database\Coin;

class HitbtcLoadMarketCoins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hitbtc:loadMarketCoins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inserts new coins as BTC/USD currency and updates the market_coin_as table.';

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
        $client = new HitBtcAPIPublic();
        $market = Market::where('alias', '=', 'hitbtc')->firstOrFail();

        $symbols = $client->getSymbols();
        foreach ($symbols as $symbol) {
            $coinBaseCurrency = $symbol['quoteCurrency'];
            $coinName = $symbol['baseCurrency'];
            $IsActive = 1;

            if ($coinBaseCurrency != 'USD' && $coinBaseCurrency != 'BTC')
                continue;

            $coin = DB::table('coin')->where('name', '=', $coinName)->where('currency', '=', $coinBaseCurrency)->get();
            $coin_id=0;
            if ($coin->IsEmpty()) {
                $coin = new Coin;
                $coin->name = $coinName;
                $coin->currency = $coinBaseCurrency;
                $coin->save();
                $coin_id = $coin->id;
                print ("coin_id: $coin_id INSERT\n");
            } else {
                $coin_id=$coin[0]->id;
                print ("coin_id: $coin_id FOUND\n");
            }

            $market_coin_as = DB::table('market_coin_as')->where('coin_id', '=', $coin_id)->where('market_id', '=', $market->id)->get();
            if ($market_coin_as->IsEmpty()) {
                print ("market_coin_as: NEW\n");                
                $market_coin_as = new MarketCoinAs;
                $market_coin_as->coin_id = $coin_id;
                $market_coin_as->market_id = $market->id;
                $market_coin_as->building = 'Y';
                $market_coin_as->active = 'N';                    
                $market_coin_as->save();
            } 
        }
    }
}
