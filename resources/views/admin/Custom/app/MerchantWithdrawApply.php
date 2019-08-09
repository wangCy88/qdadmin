<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantWithdrawApply extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'merchant_withdraw_apply';
    protected $connection = 'apptest';
}
