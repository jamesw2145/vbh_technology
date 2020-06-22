<?php

namespace App\BLogic\TableOps;

use Illuminate\Support\Facades\DB;

class TimeIntervalOps
{
    public function __construct() {
    }

    /**
     * Converts minutes to a time_interval_type.alias.
     */
    public static function minutesToTimeIntervalAlias($minutes, $alias_group) {
        $seconds = $minutes * 60;

        $query = DB::select("SELECT alias
                             FROM time_interval_type
                             WHERE alias_group = :alias_group
                               AND duration_seconds = :duration_seconds",
                               ['alias_group' => $alias_group,
                                'duration_seconds' => $seconds]);
        return $query[0]->alias;
    }

    /**
     * Converts minutes to a time_interval_type.id.
     */
    public static function minutesToTimeIntervalAliasId($minutes, $alias_group) {
        $seconds = $minutes * 60;

        $query = DB::select("SELECT id
                             FROM time_interval_type
                             WHERE alias_group = :alias_group
                               AND duration_seconds = :duration_seconds",
                               ['alias_group' => $alias_group,
                                'duration_seconds' => $seconds]);
        return $query[0]->id;
    }

    /**
     * Gets the latest time_interval.id for a given type.
     * @param time_interval_type_alise is the time_interval_type.alias
     * @return time_interval.id
     */
    public static function getLatestTimeIntervalId($time_interval_type_alias) {
        $query = DB::select("SELECT max(time_interval.id) as max_id
                             FROM time_interval JOIN time_interval_type ON time_interval.time_interval_type_id = time_interval_type.id
                             WHERE time_interval_type.alias = :time_alias",
                             ['time_alias' => $time_interval_type_alias]);
        return $query[0]->max_id;
    }
    
    /**
     * Gets the latest time_interval for a given type.
     * @param time_interval_id is the time_interval.id
     * @return time_interval.id
     */
    public function getLatestTimeInterval($time_interval_id) {
        $query = DB::select("SELECT *
                             FROM time_interval
                             WHERE id = :time_interval_id",
                             ['time_interval_id' => $time_interval_id]);
        return $query[0];
    }

    /**
     * Retrieves older time interval ids from the time_interval_id using the lenths found in the arr_lengths array.
     * @param time_interval_id is the starting time_interval_id to retrieve older time intervals.     
     * @param time_interval_type_alias is the time_interval_type.alias
     * @param arr_lengths is an array of lengths starting with the newest first. ie: [20, 50, 10, 200]
     * @return array of time_interval.id's that correspond to the arr_lengths of older time_intervals.
     */
    public function getOlderTimeIntervals($time_interval_id, $time_interval_type_alias, $arr_lengths) {
        $length = count($arr_lengths);
        $oldest = $arr_lengths[$length-1];


        $query = DB::select("SELECT time_interval.id
                            from time_interval left join time_interval_type on time_interval.time_interval_type_id = time_interval_type.id
                            where time_interval_type.alias = :time_alias
                              and time_interval.id <= :time_interval_id
                            order by time_interval.id desc
                            limit :oldest",
                            ['time_alias' => $time_interval_type_alias, 'time_interval_id' => $time_interval_id, 'oldest' => $oldest]);

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

    /**
     * Retrieves older time interval id from the time_interval_id using the lenth found in the offset_length.
     * @param time_interval_id is the starting time_interval_id to retrieve older time intervals.     
     * @param time_interval_type_alias is the time_interval_type.alias
     * @param offset_length how far back to get a time_interval from.
     * @return time_interval.id
     */
    public function getOlderTimeIntervalId($time_interval_id, $time_interval_type_alias, $offset_length) {

        $query = DB::select("SELECT time_interval.id
                            from time_interval left join time_interval_type on time_interval.time_interval_type_id = time_interval_type.id
                            where time_interval_type.alias = :time_alias
                              and time_interval.id < :time_interval_id
                            order by time_interval.id asc
                            limit :offset_length",
                            ['time_alias' => $time_interval_type_alias, 'time_interval_id' => $time_interval_id, 'offset_length' => $offset_length]);

        return $query[0]->id;
    }

    /** Determines if the time interval has all of the secondard data that calculates off the time interval completed? Ie, if the candlestick
     * and moving averages, and any other future data calculated from it, has been completed.
     */
    public function isTimeIntervalDataComplete($time_interval_id) {
        $query = DB::select("SELECT count(*) as not_completed
                             from batch_run_type
                             where batch_run_type.type not in (select batch_run.type from batch_run where time_interval_id = :id)",
                             ['id' => $time_interval_id]);
        if ($query[0]->not_completed > 0) {
            return false;
        }

        return true;
    }

}
