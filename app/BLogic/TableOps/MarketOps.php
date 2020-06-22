<?php

namespace App\BLogic\TableOps;

use Illuminate\Support\Facades\DB;

class MarketOps
{
    protected $model = array();

    public function __construct () {
        $this->loadMarkets ();
    }

    public function convertWithMarketId ($marketId, $price, $currencyFrom, $currencyTo)
    {
        $model = $this->getMarketWithId ($marketId);
        return $this->convert ($model, $price, $currencyFrom, $currencyTo);
    }

    public function convertWithMarketAlias ($marketAlias, $price, $currencyFrom, $currencyTo)
    {
        $model = $this->getMarketWithAlias ($marketAlias);
        return $this->convert ($model, $price, $currencyFrom, $currencyTo);        
    }

    function convert ($marketModel, $price, $currencyFrom, $currencyTo)
    {
        $btc = $this->convertToBtc ($marketModel, $price, $currencyFrom);
        return $this->convertTo ($marketModel, $btc, $currencyTo);
    }

    function convertTo ($marketModel, $btc, $currencyTo) 
    {
        if (strtolower($currencyTo) === "usd") {
            return $btc / $marketModel->usd_to_btc;

        } else if (strtolower($currencyTo) === "eth") {
            return $btc / $marketModel->eth_to_btc;

        } else if (strtolower($currencyTo) === "btc") {
            return $btc;

        } else if (strtolower($currencyTo) === "usdt") {
            return $btc / $marketModel->usdt_to_btc;
        }
        return 0;
    }

    function convertToBtc ($marketModel, $price, $currencyFrom)
    {   
        if (strtolower($currencyFrom) === "usd") {
            return $price * $marketModel->usd_to_btc;

        } else if (strtolower($currencyFrom) === "eth") {
            return $price * $marketModel->eth_to_btc;

        } else if (strtolower($currencyFrom) === "btc") {
            return $price;

        } else if (strtolower($currencyFrom) === "usdt") {
            return $price * $marketModel->usdt_to_btc;
        }
        return 0;
    }

    function loadMarkets ()
    {
        $query = DB::select("SELECT market.id,
                                    market.alias,
                                    market.usd_to_btc,
                                    market.eth_to_btc,
                                    market.usdt_to_btc,
                                    market.btc_to_usd
                            FROM market");

        foreach ($query as $record) {
            $data = new MarketModel ();
            $data->id = $record->id;
            $data->alias = $record->alias;
            $data->usd_to_btc = $record->usd_to_btc;
            $data->eth_to_btc = $record->eth_to_btc;
            $data->usdt_to_btc = $record->usdt_to_btc;
            $data->btc_to_usd = $record->btc_to_usd;
            $this->model[] = $data;
        }        
    }

    function getMarketWithId ($marketId)
    {
        foreach ($this->model as $model) {
            if ($model->id == $marketId) {
                return $model;
            }
        }

        return FALSE;
    }

    function getMarketWithAlias ($marketAlias)
    {
        foreach ($this->model as $model) {
            if ($model->alias == $marketAlias) {
                return $model;
            }
        }

        return FALSE;
    }

}

class MarketModel
{
    public $id;
    public $alias;
    public $usd_to_btc;
    public $eth_to_btc;
    public $usdt_to_btc;
    public $btc_to_usd;
}
