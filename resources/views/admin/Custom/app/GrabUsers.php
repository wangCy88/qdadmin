<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrabUsers extends Model
{
    protected $table = 'grab_users';

    protected $fillable = ['phone', 'name', 'id_number', 'comp_city', 'company', 'comp_code', 'comp_phone', 'wechat'];

    //grabUsersWallet
    public function grabUsersWallet()
    {
        return $this->hasOne('App\GrabUsers', 'user_id', 'user_id');
    }
}
