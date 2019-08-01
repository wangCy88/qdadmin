<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantUserStep extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'merchant_user_step';
    protected $connection = 'apptest';
}
