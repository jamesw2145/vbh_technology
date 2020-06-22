<?php

namespace App\Api;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\BLogic\TableOps\TimeIntervalOps;
use App\Api\CandlestickData;

class Candlesticks
{
	public function json($json) {
		$data = new CandlestickData($json);

		$sql = "SELECT ti.id as time_interval_id,
				       ti.start_time,
				       ti.end_time,
				       co.name as coin_name,
				       co.currency as coin_currency,
				       c.start,
				       c.end,
				       c.low,
				       c.high,
				       c.base_volume_24hrs,
				       m.id as market_id,
				       m.alias as market_name
				from time_interval ti join candlestick c on ti.id = c.time_interval_id
				                      join coin co on co.id = c.coin_id
				                      join market m on m.id = co.market_id
				where ti.id <= ?
				  and ti.id >= ? 
				  and m.alias = ?
				order by co.name, co.currency, ti.id asc";

		return $data->json($sql, function($record) {
        	$jsonCandle = new JSONcandlestick();

        	$jsonCandle->time_interval_id = $record->time_interval_id;
        	$jsonCandle->start = $record->start;
        	$jsonCandle->end = $record->end;
        	$jsonCandle->low = $record->low;
        	$jsonCandle->high = $record->high;
        	$jsonCandle->base_volume_24hrs = $record->base_volume_24hrs;
        	$jsonCandle->start_time = $record->start_time;
        	$jsonCandle->end_time = $record->end_time;

        	return $jsonCandle;
		});
	}
}

class JSONcandlestick {
	public $time_interval_id;
	public $start;
	public $end;
	public $low;
	public $high;
	public $base_volume_24hrs;
	public $start_time;
	public $end_time;
}
