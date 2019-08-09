<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrabCustomHigh extends Model
{
    protected $table = 'grab_custom_high';

    protected $fillable = ['house', 'car', 'wages', 'withdraw_amount'];
}
