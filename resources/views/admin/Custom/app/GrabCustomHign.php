<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrabCustomHign extends Model
{
    protected $table = 'grab_custom_high';

    protected $fillable = ['car', 'house', 'custom_id'];
}
