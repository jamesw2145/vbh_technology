<?php

namespace App\BLogic\TableOps;

use Illuminate\Support\Facades\DB;

class MarketCoinAsOps
{
    public function __construct() {
    }

    /**
     * @return true if coin is active.
     */
    public static function isCoinActive($market, $coin, $currency) {
        $query = DB::select("SELECT active            
                             FROM market_coin_as join coin on market_coin_as.coin_id = coin.id
                                                 join market on market_coin_as.market_id = market.id
                             WHERE coin.name = :coin_name
                               AND coin.currency = :coin_currency
                               AND market.alias = :market_alias",
                               ['coin_name' => strtoupper($coin),
                                'coin_currency' => strtoupper($currency),
                                'market_alias' => strtolower($market)]);
        if (isset($query[0])) {
            if ($query[0]->active == 'Y') {
                return 1;
            }
        }

        return 0;
    }

}
