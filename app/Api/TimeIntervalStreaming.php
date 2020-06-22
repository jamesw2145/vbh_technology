<?php

namespace App\Api;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TimeIntervalStreaming
{
    private $lastSentTimeIntervalId = 0;

    function __construct()
    {
    }

    /** This will query to see if there are any new time interval's to process and if so will return them.
     * @return FALSE if no new time intervals, or JSONtimeinterval of new time interval.
     */
    public function retrieveStreamingData () {   
        $nextTimeIntervalId = $this->nextTimeIntervalId ();
        if ($nextTimeIntervalId == 0) {
            return FALSE;
        }

        Log::info("TimeIntervalStreaming: retrieveStreamingData -- nextTimeIntervalId: [" . $nextTimeIntervalId . "] lastSentTimeIntervalId: [" . $this->lastSentTimeIntervalId . "]");

        $record = $this->getTimeInterval ($nextTimeIntervalId);
        $jsonti = new JSONtimeinterval();
        $jsonti->time_interval_id = $nextTimeIntervalId;
        $jsonti->start_time = $record->start_time;
        $jsonti->end_time = $record->end_time;
        $jsonti->minutes = 15;

    	return $jsonti;
    }

    function nextTimeIntervalId () 
    {
        $query = DB::select("SELECT max(time_interval.id) as max_id
                             FROM time_interval");        
        $max_id = $query[0]->max_id;

        if ($max_id > $this->lastSentTimeIntervalId) {
            $this->lastSentTimeIntervalId = $max_id;
            return $max_id;
        }

        return 0;
    }

    function getTimeInterval ($time_interval_id) {
        $query = DB::select("SELECT *
                             FROM time_interval
                             WHERE id = :time_interval_id",
                             ['time_interval_id' => $time_interval_id]);
        return $query[0];
    }
}

class JSONtimeinterval {
	public $time_interval_id;
	public $start_time;
	public $end_time;
	public $minutes;
}
