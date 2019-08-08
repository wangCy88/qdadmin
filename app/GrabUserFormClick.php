<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrabUserFormClick extends Model
{
    protected $table = 'grab_user_form_click';

    public function grabUserFrom()
    {
        return $this -> hasOne('App\GrabUserFrom' , 'id' , 'channel_id');
    }
}
