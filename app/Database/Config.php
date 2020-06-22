<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'config';

    public function scopeAlias($query, $alias_group, $alias)
    {
        return $query->where('alias_group', '=', $alias_group)->where('alias', '=', $alias);
    }
}
