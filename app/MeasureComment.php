<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeasureComment extends Model
{
    //
    protected $table = 'measure_comment';
    protected $primaryKey = 'comment_uid';
    protected $guarded = [];

    public $timestamps = false;
}
