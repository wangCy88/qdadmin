<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrabCardTicket extends Model
{
    protected $table = 'grab_card_ticket';

    protected $fillable = ['face_value', 'price', 'rebate', 'original_price'];
}
