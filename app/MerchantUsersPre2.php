<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantUsersPre2 extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'merchant_users_pre';
    protected $connection = 'apptest2';
}
