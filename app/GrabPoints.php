<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrabPoints extends Model
{
    //
    protected $table = 'grab_points';

    protected $fillable = ['face_value', 'price', 'rebate', 'original_price'];
}
