<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrabBorrowOrder extends Model
{
    //
    protected $table = 'grab_borrow_order';

    public function grabCustom()
    {
        return $this->hasOne('App\GrabCustom', 'id', 'custom_id');
    }

    public function grabCustomHigh()
    {
        return $this->hasOne('App\GrabCustomHigh', 'custom_id', 'custom_id');
    }

    public function grabUsers()
    {
        return $this->hasOne('App\GrabUsers', 'id', 'user_id');
    }

    public function grabOrderAccount()
    {
        return $this->hasOne('App\GrabOrderAccount', 'id', 'account');
    }
}
