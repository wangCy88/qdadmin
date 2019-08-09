<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantUsersPre extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'merchant_users_pre';
    protected $connection = 'apptest';

    public function merchantUsers()
    {
        return $this->hasOne('App\MerchantUsers', 'phone', 'phone');
    }

    public function merchantChannelConfig()
    {
        return $this->hasOne('App\MerchantChannelConfig', 'id', 'channel');
    }

    public function merchantWithdrawApply()
    {
        return $this->hasOne('App\MerchantWithdrawApply', 'phone', 'phone');
    }

    public function merchantUserStep()
    {
        return $this->hasOne('App\MerchantUserStep', 'phone', 'phone');
    }
}
