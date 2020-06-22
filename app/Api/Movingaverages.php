<?php

namespace App\Api;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\BLogic\TableOps\TimeIntervalOps;
use App\Api\CandlestickData;

class Movingaverages
{
	public function json($json) {
		$data = new CandlestickData($json);

		$sql = "SELECT ti.id as time_interval_id,
				       ti.start_time,
				       ti.end_time,
				       co.name as coin_name,
				       co.currency as coin_currency,
					   ma.average_10,
					   ma.average_20,
					   ma.average_50,
					   ma.average_100,
					   ma.average_200,				       
					   ma.average_500,
					   ma.average_1000,
					   ma.average_2000,
				       m.id as market_id,
				       m.alias as market_name
				from time_interval ti join moving_average ma on ti.id = ma.time_interval_id
				                      join coin co on co.id = ma.coin_id
				                      join market m on m.id = co.market_id
				where ti.id <= ?
				  and ti.id >= ?
				  and m.alias = ?
				order by co.name, co.currency, ti.id asc";

		return $data->json($sql, function($record) {
        	$ma = new JSONmovingAverage();

        	$ma->time_interval_id = $record->time_interval_id;
			$ma->average_10 = $record->average_10;
			$ma->average_20 = $record->average_20;
			$ma->average_50 = $record->average_50;
			$ma->average_100 = $record->average_100;
			$ma->average_200 = $record->average_200;
			$ma->average_500 = $record->average_500;
			$ma->average_1000 = $record->average_1000;
			$ma->average_2000 = $record->average_2000;
			$ma->start_time = $record->start_time;
			$ma->end_time = $record->end_time;

        	return $ma;
		});
	}
}

class JSONmovingAverage {
	public $time_interval_id;	
	public $average_10;
	public $average_20;
	public $average_50;
	public $average_100;
	public $average_200;
	public $average_500;
	public $average_1000;
	public $average_2000;
	public $start_time;
	public $end_time;
}
