<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrabJdDeal extends Model
{
    //京东交易MODEL
    protected $table = 'grab_jd_deal';

    protected $fillable = ['order_no', 'user_id', 'amount', 'status', 'type'];
}
