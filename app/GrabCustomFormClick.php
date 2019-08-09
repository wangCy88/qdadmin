<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrabCustomFormClick extends Model
{
    protected $table = 'grab_custom_form_click';

    public function grabCustomFrom()
    {
        return $this -> hasOne('App\GrabCustomFrom' , 'id' , 'channel_id');
    }
}
