<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrabJdbindcard extends Model
{
    //京东绑卡MODEL
    protected $table = 'grab_jd_bindcard';

    protected $fillable = ['out_trade_no', 'bank_card', 'bankid', 'bind_status', 'agreement_no'];
}
