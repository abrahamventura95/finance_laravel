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
        'coin_id', 'user_id', 'tag', 'type', 'amount'
    ];
}
