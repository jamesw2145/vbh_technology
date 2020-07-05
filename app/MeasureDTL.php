<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeasureDTL extends Model
{
    //
    protected $table = 'measure_dtl';
    protected $primaryKey = 'measure_dtl_uid';
    protected $guarded = [];

    public $timestamps = false;
}
