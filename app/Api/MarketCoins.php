<?php

namespace App\Api;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\BLogic\TableOps\MarketOps;

class MarketCoins
{
	public function json ($jsonParameters) {

        $sqlQueryParameters = array ();
        $whereClause = "";
        if (isset($jsonParameters['market'])) {
            $whereClause = " WHERE market.alias = ?";
            $sqlQueryParameters[] = $jsonParameters['market'];
        }

        $marketOps = new MarketOps ();

        $minimumCoinValueUSD = $this->minimumCoinValueUSD ();

        $query = DB::select("SELECT market.id market_id,
                                    market.alias,
                                    coin.name,
                                    coin.currency,
                                    coin.price,
                                    coin.base_volume_24hrs,
                                    coin.minimum_trade_size,
                                    coin.is_active,
                                    coin.is_going_offline
                            FROM coin join market on coin.market_id = market.id
                            $whereClause
                            ", $sqlQueryParameters);

        $root = new JSONRoot();
        $root->message = "SUCCESS";

        foreach ($query as $record) {
            $market_id = $record->market_id;

            $base_volume_24hrs_in_usd = $marketOps->convertWithMarketId ($market_id, $record->base_volume_24hrs, $record->currency, "usd");

            if ($record->is_active == 0) {
                continue;
            }

        	$marketcoin = new JSONMarketCoin();
            $marketcoin->market = $record->alias;
        	$marketcoin->coin = $record->name;
        	$marketcoin->currency = $record->currency;
            $marketcoin->price = $record->price;
            $marketcoin->base_volume_24hrs = $record->base_volume_24hrs;
            $marketcoin->minimum_trade_size = $record->minimum_trade_size;
            $marketcoin->is_going_offline = $record->is_going_offline;
            
            if ($record->minimum_trade_size === NULL) {
                continue;
            }

            $marketcoin->price_in_btc = $marketOps->convertWithMarketId ($market_id, $record->price, $record->currency, "btc");
            $marketcoin->base_volume_24hrs_in_btc = $marketOps->convertWithMarketId ($market_id, $record->base_volume_24hrs, $record->currency, "btc");

        	$root->data[] = $marketcoin;
        }

		return $root;
	}

    function minimumCoinValueUSD ()
    {
        $query = DB::select ("SELECT float_value FROM config WHERE name = 'minimum_coin_volume'");
        return $query[0]->float_value;
    }

}

class JSONRoot 
{
    public $message = "SUCCESS";
    public $data = array ();
}

class JSONMarketCoin
{
    public $market;
    public $coin;
    public $currency;
    public $price;
    public $price_in_btc;
    public $base_volume_24hrs;
    public $base_volume_24hrs_in_btc;
    public $minimum_trade_size;
    public $is_going_offline;
}
