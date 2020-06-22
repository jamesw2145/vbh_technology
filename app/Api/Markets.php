<?php

namespace App\Api;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class Markets
{
    public function json($jsonParameters) {

        $sqlQueryParameters = array ();
        $whereClause = "";
        if (isset($jsonParameters['market'])) {
            $whereClause = " WHERE alias = ?";
            $sqlQueryParameters[] = $jsonParameters['market'];
        }

        $root = new JSONroot();
        $root->message = "SUCCESS";

        $query = DB::select("SELECT alias, 
                                    usd_to_btc, 
                                    eth_to_btc,
                                    usdt_to_btc,
                                    btc_to_usd
                             FROM market
                             $whereClause
                             ORDER BY name ASC", $sqlQueryParameters);

        foreach ($query as $record) {
            $market = new JSONmarket();
            $market->name = $record->alias;
            $market->usd_to_btc = $record->usd_to_btc;
            $market->eth_to_btc = $record->eth_to_btc;
            $market->usdt_to_btc = $record->usdt_to_btc;
            $market->btc_to_usd = $record->btc_to_usd;
            $root->data[] = $market;
        }

        return $root;
    }
}

class JSONmarket {
    public $name;
    public $usd_to_btc;
    public $eth_to_btc;
    public $usdt_to_btc;
    public $btc_to_usd;
}

class JSONroot {
    public $message;
    public $data = array();
}
