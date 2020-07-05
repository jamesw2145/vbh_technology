<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeasureHDR extends Model
{
    //
    protected $table = 'measure_hdr';
    protected $primaryKey = 'measure_hdr_uid';
    protected $guarded = [];

    public $timestamps = false;
}
