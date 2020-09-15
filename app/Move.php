<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Move extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'money_moves';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'user_id', 'coin_id', 'tag', 'type', 'amount'
    ];
}
