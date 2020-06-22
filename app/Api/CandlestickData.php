<?php

namespace App\Api;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\BLogic\TableOps\TimeIntervalOps;
use App\BLogic\TableOps\MarketCoinAsOps;

class CandlestickData
{
    public $time_interval_id=0;
    public $interval_count=1;

    private $marketName = "";

    public function __construct($json)
    {
    	if (isset($json['time_interval_id'])) {
    		$this->time_interval_id = $json['time_interval_id'];
    	}

    	if (isset($json['interval_count'])) {
    		$this->interval_count = (int)$json['interval_count'];
    	}

    	if (isset($json['market'])) {
    		$this->marketName = $json['market'];
    	}
    }

	public function json($sql, $populateRecordFunc) {
		$root = new JSONroot();

		if ($this->marketName === "") {
			$root->message = "ERROR: Missing market name parameter.";
			return $root;
		} else if ($this->time_interval_id == 0) {
			$root->message = "ERROR: Missing time interval id parameter.";
			return $root;			
		}

		$root->message = "SUCCESS";

		$start_time_interval_id = $this->time_interval_id - $this->interval_count + 1;

		$arr = array ($this->time_interval_id, $start_time_interval_id, $this->marketName);
        $query = DB::select($sql, $arr);

        if (count($query) == 0) {
        	return $root;
        }

		$jsonMarketCoin = $this->newJsonMarketCoin ($query[0]);		
        foreach ($query as $record) {

			if ($this->isNewMarketCoin ($jsonMarketCoin, $record)) {
				$this->addDataToJson ($root, $jsonMarketCoin);
				$jsonMarketCoin = $this->newJsonMarketCoin ($record);
			}

        	$jsonMarketCoin->data[] = $populateRecordFunc($record);
        }
		$this->addDataToJson ($root, $jsonMarketCoin);

        return $root;
	}

	function addDataToJson ($root, $jsonMarketCoin)
	{
		$root->data[] = $jsonMarketCoin;
	}

	function isNewMarketCoin ($jsonMarketCoin, $record)
	{
		if ($jsonMarketCoin->market !== $record->market_name ||
			$jsonMarketCoin->coin !== $record->coin_name ||
			$jsonMarketCoin->currency !== $record->coin_currency) {
			return true;
		}

		return false;
	}

	function newJsonMarketCoin ($record) {
		$jsonMarketCoin = new JSONmarketcoin();
		$jsonMarketCoin->market = $record->market_name;
		$jsonMarketCoin->coin = $record->coin_name;
		$jsonMarketCoin->currency = $record->coin_currency;
		return $jsonMarketCoin;
	}
}

class MarketCoin {
	public $market;
	public $coin;
	public $currency;

	function __construct($market, $coin, $currency)
	{
		$this->market = $market;
		$this->coin = $coin;
		$this->currency = $currency;
	}
}

class JSONmarketcoin {
	public $market;
	public $coin;
	public $currency;
	public $data = array();

	public function marketCoinCurrency ()
	{
		return strtolower($this->market . "-" . $this->coin . "-" . $this->currency);
	}
}

class JSONroot {
	public $message;
	public $data = array();
}
