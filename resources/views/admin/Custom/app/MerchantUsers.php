<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantUsers extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'merchant_users';
    protected $connection = 'apptest';

    public function merchantUsersEx()
    {
        return $this->hasOne('App\merchantUsersEx', 'id', 'id');
    }
}
