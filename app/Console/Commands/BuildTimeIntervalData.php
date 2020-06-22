<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;

use App\Database\TimeInterval;

class BuildTimeIntervalData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crypto:buildTimeIntervalData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Builds the time_interval, candlestick, and moving_average tables.';

    private $time_interval_end_time;
    private $time_interval_id=0;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct ()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle () {
        date_default_timezone_set('America/New_York');        

        DB::beginTransaction();

        $this->addTimeIntervalIfNeedBe ();
        if ($this->time_interval_id == 0) {
          DB::rollback();
          return;
        }

        $this->buildCandleStick ();
        $this->buildMovingAverage ();
        $this->updateCoinPrices ();
        $this->updateMarketPrices ();
        $this->purgeTickRecords ();

        DB::commit();        
    }

    function purgeTickRecords ()
    {
        $sql = "DELETE FROM tick
                WHERE time <= :end_time";
        DB::select($sql, ['end_time' => $this->time_interval_end_time]);
    }

    function updateMarketPrices ()
    {
        $this->updateMarketPricesBtc ();
        $this->updateMarketPricesOthers ();
    }

    function updateMarketPricesBtc ()
    {
        $sql = "SELECT market_id,
                       coin.price,
                       coin.currency
                from coin inner join candlestick on candlestick.coin_id = coin.id
                          inner join market on coin.market_id = market.id
                where candlestick.time_interval_id = ?
                  and coin.name = 'BTC'
                  and coin.currency = 'USD'";
        $query = DB::select ($sql, [$this->time_interval_id]);

        foreach ($query as $record) {
            $market_id = $record->market_id;
            $price = $record->price;
            $currency = $record->currency;

            $btc_to_usd = $price;
            $usd_to_btc = 1 / $btc_to_usd;

            $sql = "UPDATE market
                    SET usd_to_btc = ?,
                        btc_to_usd = ?,
                        updated_at = now()
                    WHERE id = ?";
           DB::select ($sql, [$usd_to_btc, $btc_to_usd, $market_id]);
        }
    }

    function updateMarketPricesOthers ()
    {
        $sql = "SELECT market.id,
                       market.usd_to_btc,
                       market.btc_to_usd,
                       coin.name,
                       coin.currency,
                       coin.price
                from coin inner join candlestick on candlestick.coin_id = coin.id
                          inner join market on coin.market_id = market.id
                where candlestick.time_interval_id = ?
                  and coin.name IN ('ETH', 'USDT')
                  and coin.currency IN ('BTC', 'USD')";
        $query = DB::select ($sql, [$this->time_interval_id]);

        foreach ($query as $record) {
            $market_id = $record->id;
            $name = $record->name;
            $currency = $record->currency;
            $price = $record->price;
            $usd_to_btc = $record->usd_to_btc;
            $btc_to_usd = $record->btc_to_usd;

            $fieldName = "";
            $updateValue = 0;

            if ($name == "ETH" && $currency == "BTC") {
                $fieldName = "eth_to_btc";
                $updateValue = $price;
            } else if ($name == "USDT" && $currency == "USD") {
                $fieldName = "usdt_to_btc";                
                $updateValue = $price * $usd_to_btc;
            } else {
                continue;
            }

            $sql = "UPDATE market
                    SET $fieldName = ?,
                        updated_at = now()
                    WHERE id = ?";
           DB::select ($sql, [$updateValue, $market_id]);
        }
    }

    function updateMarketPricesOthersToUsd ()
    {
        $sql = "SELECT market_id,
                       coin.name,
                       coin.price
                from coin inner join candlestick on candlestick.coin_id = coin.id
                          inner join market on coin.market_id = market.id
                where candlestick.time_interval_id = ?
                  and coin.name IN ('USDT')
                  and coin.currency = 'USD'";
        $query = DB::select ($sql, [$this->time_interval_id]);

        foreach ($query as $record) {
            $fieldName = "";
            $updateValue = 0;

            $name = $record->name;
            $market_id = $record->market_id;
            $updateValue = $record->price;

            if ($name == "ETH") {
                $fieldName = "eth_to_btc";
            } else if ($name == "USDT") {
                $fieldName = "usdt_to_btc";
            } else {
                // Unknown coin.
                continue;
            }

            $sql = "UPDATE market
                    SET $fieldName = ?,
                        updated_at = now()
                    WHERE id = ?";
           DB::select ($sql, [$updateValue, $market_id]);
        }
    }

    function updateCoinPrices ()
    {
        $sql = "UPDATE coin inner join candlestick on candlestick.coin_id = coin.id
                set price = candlestick.end, 
                    coin.base_volume_24hrs = candlestick.base_volume_24hrs,
                    coin.updated_at = now()
                where candlestick.time_interval_id = ?";

        DB::select ($sql, [$this->time_interval_id]);
    }

    function buildCandleStick ()
    {
        $sql = "INSERT into candlestick
        (`time_interval_id`, `coin_id`, `start`, `end`, `low`, `high`, `base_volume_24hrs`, `created_at`, `updated_at`)
        select time_interval.id,
               coin.id,
               (select price from tick where tick.coin_id=coin.id and tick.time >= time_interval.start_time and tick.time <= time_interval.end_time order by id asc limit 1) as start_price,
               (select price from tick where  tick.coin_id=coin.id and tick.time >= time_interval.start_time and tick.time <= time_interval.end_time order by id desc limit 1) as end_price,
               (select min(price) from tick where tick.coin_id=coin.id and tick.time >= time_interval.start_time and tick.time <= time_interval.end_time) as min_price,
               (select max(price) from tick where tick.coin_id=coin.id and tick.time >= time_interval.start_time and tick.time <= time_interval.end_time) as max_price,
               (select avg(base_volume_24hrs) from tick where tick.coin_id=coin.id and tick.time >= time_interval.start_time and tick.time <= time_interval.end_time) as base_volume_24hrs,
               now(),
               now()
        FROM coin JOIN time_interval ON time_interval.id = ?
        WHERE coin.id in (select distinct coin_id from tick where tick.time >= time_interval.start_time and tick.time <= time_interval.end_time)
          AND coin.is_active = 1";

        DB::insert($sql, [$this->time_interval_id]);
    }

    private function buildMovingAverage () {
        $arr_lengths = array(10, 20, 50, 100, 200, 500, 1000, 2000);
        $arr_time_interval_ids = $this->getOlderTimeIntervals($arr_lengths);

        $sql = "INSERT into moving_average
            (`time_interval_id`, `coin_id`, `average_10`, `average_20`, `average_50`, `average_100`, `average_200`, `average_500`, `average_1000`, `average_2000`, `created_at`, `updated_at`)        
        SELECT 
         :time_interval_id,
         coin.id,         
         (select AVG(end)
        from time_interval left join candlestick on candlestick.time_interval_id = time_interval.id
        where time_interval.id >= :id10
          and candlestick.coin_id = coin.id
        ) as avg10,
        (select AVG(end)
        from time_interval left join candlestick on candlestick.time_interval_id = time_interval.id
        where time_interval.id >= :id20
          and candlestick.coin_id = coin.id
        ) as avg20,
        (select AVG(end)
        from time_interval left join candlestick on candlestick.time_interval_id = time_interval.id
        where time_interval.id >= :id50
          and candlestick.coin_id = coin.id
        ) as avg50,
        (select AVG(end)
        from time_interval left join candlestick on candlestick.time_interval_id = time_interval.id
        where time_interval.id >= :id100
          and candlestick.coin_id = coin.id
        ) as avg100,
        (select AVG(end)
        from time_interval left join candlestick on candlestick.time_interval_id = time_interval.id
        where time_interval.id >= :id200
          and candlestick.coin_id = coin.id
        ) as avg200, 
        (select AVG(end)
        from time_interval left join candlestick on candlestick.time_interval_id = time_interval.id
        where time_interval.id >= :id500
          and candlestick.coin_id = coin.id
        ) as avg500, 
        (select AVG(end)
        from time_interval left join candlestick on candlestick.time_interval_id = time_interval.id
        where time_interval.id >= :id1000
          and candlestick.coin_id = coin.id
        ) as avg1000, 
        (select AVG(end)
        from time_interval left join candlestick on candlestick.time_interval_id = time_interval.id
        where time_interval.id >= :id2000
          and candlestick.coin_id = coin.id
        ) as avg2000, 
       now(),
       now()        
        from coin 
        where coin.id in (select distinct coin_id from candlestick where candlestick.time_interval_id = :time_interval_id2)
          AND coin.is_active = 1";

        $query = DB::select($sql, [ 'id10' => $arr_time_interval_ids[0],
                                    'id20' => $arr_time_interval_ids[1],
                                    'id50' => $arr_time_interval_ids[2],
                                    'id100' => $arr_time_interval_ids[3],
                                    'id200' => $arr_time_interval_ids[4],
                                    'id500' => $arr_time_interval_ids[5],
                                    'id1000' => $arr_time_interval_ids[6],
                                    'id2000' => $arr_time_interval_ids[7],
                                    'time_interval_id' => $this->time_interval_id,
                                    'time_interval_id2' => $this->time_interval_id
                                ]);
    }

    /**
     * Retrieves older time interval ids from the time_interval_id using the lenths found in the arr_lengths array.
     * @param time_interval_id is the starting time_interval_id to retrieve older time intervals.     
     * @param arr_lengths is an array of lengths starting with the newest first. ie: [20, 50, 10, 200]
     * @return array of time_interval.id's that correspond to the arr_lengths of older time_intervals.
     */
    function getOlderTimeIntervals ($arr_lengths) {
        $length = count($arr_lengths);
        $oldest = $arr_lengths[$length-1];

        $query = DB::select("SELECT time_interval.id
                            from time_interval
                            order by time_interval.id desc
                            limit :oldest",
                            ['oldest' => $oldest]);

        $cnt=0;
        $arr = array();
        $length_index=0;
        $saved_time_interval_id = 0;
        foreach ($query as $record) {

            $saved_time_interval_id = $record->id;
            $cnt++;

            if ($arr_lengths[$length_index] == $cnt) {
                $arr[] = $record->id;
                $length_index++;

                if ($length_index == $length) {
                    break;
                }
            }            
        }

        for (;$length_index < $length; $length_index++) {
            $arr[] = $saved_time_interval_id;
        }

        return $arr;
    }

    function addTimeIntervalIfNeedBe ()
    {
        $arr = $this->getTimeIntervalTimes ();        
        $start_time = $arr['start_time'];
        $end_time = $arr['end_time'];
        $this->time_interval_end_time = $end_time;

        $count = TimeInterval::where('start_time', $start_time)->where('end_time', $end_time)->count();
        if ($count == 0) {
            $timeInterval = new TimeInterval;
            $timeInterval->start_time = $start_time;
            $timeInterval->end_time = $end_time;
            $timeInterval->save();
            $this->time_interval_id = $timeInterval->id;
        }
    }

    function getTimeIntervalTimes ()
    {
        $time = strtotime("-15 minutes");
        $subseconds = (date("i", $time) % 15) * 60 + date("s");

        $start = $time - $subseconds;
        $end = $start + 15 * 60;

        $start_time = date('Y-m-d H:i:s', $start);
        $end_time =  date('Y-m-d H:i:s', $end);

        $arr = array();
        $arr['start_time'] = $start_time;
        $arr['end_time'] = $end_time;
        return $arr;
    }

}
