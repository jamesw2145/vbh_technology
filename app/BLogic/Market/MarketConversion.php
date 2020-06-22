<?php

namespace App\BLogic\Market;

use Illuminate\Support\Facades\DB;

class MarketConversion
{
    private $_btcToUsd;
    private $_usdToBtc;

    public function __construct() {
        $query = DB::select("SELECT BTC_to_USD, USD_to_BTC from markets_overall");
        $this->_btcToUsd = $query[0]->BTC_to_USD;
        $this->_usdToBtc = $query[0]->USD_to_BTC;
    }

    public function valueToUsd($value, $currency) {
        if ($currency == "USD")
            return $value;
        return $this->btcToUsd($value);
    }

    public function valueToBtc($value, $currency) {
        if ($currency == "BTC")
            return $value;
        return $this->usdToBtc($value);
    }

    public function btcToUsd($btc) {
        return $btc * $this->_usdToBtc;
    }

    public function usdToBtc($usd) {
        return $usd * $this->_btcToUsd;
    }

    public function formatAsUsdBtc($value, $currency) {
        $usd = $this->valueToUsd($value, $currency);
        $btc = $this->valueToBtc($value, $currency);

        return "\$" . sprintf("%.2f", $usd) . " (" . sprintf("%.7f", $btc) . " BTC)";
    }

}
