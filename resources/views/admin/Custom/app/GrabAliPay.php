<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrabAliPay extends Model
{
    protected $table = 'grab_ali_pay';

    protected $fillable = ['user_id', 'order_no', 'amount'];
}
