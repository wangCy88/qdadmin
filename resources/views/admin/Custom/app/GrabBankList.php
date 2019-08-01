<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrabBankList extends Model
{
    //
    protected $table = 'grab_bank_list';

    protected $fillable = ['smallName', 'bankAbribge', 'bankName'];
}
