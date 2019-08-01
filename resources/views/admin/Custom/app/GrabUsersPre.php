<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrabUsersPre extends Model
{
    protected $table = 'grab_users_pre';

    protected $fillable = ['phone', 'mchid', 'password', 'brand', 'version', 'imei', 'mac', 'location', 'reg_ip', 'upid'];

    public function grabUsers()
    {
        return $this->hasOne('App\GrabUsers', 'user_id', 'id');
    }

    public function grabUsersWallet()
    {
        return $this->hasOne('App\GrabUsersWallet', 'user_id', 'id');
    }
}
