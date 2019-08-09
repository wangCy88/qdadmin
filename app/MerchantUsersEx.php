<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantUsersEx extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'merchant_users_ex';
    protected $connection = 'apptest';
}
