<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'coins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'cu_conver'
    ];
}
