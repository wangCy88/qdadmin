<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrabCustom extends Model
{
    protected $table = 'grab_custom';

    protected $fillable = ['phone', 'name', 'sex'];

    public function grabCustomHigh()
    {
        return $this->hasOne('App\GrabCustomHigh', 'custom_id', 'id');
    }

    public function grabCustomForm()
    {
        return $this->hasOne('App\GrabCustomForm' , 'id' , 'from');
    }
}
