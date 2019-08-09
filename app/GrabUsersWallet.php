<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrabUsersWallet extends Model
{
    protected $table = 'grab_users_wallet';

    protected $fillable = ['card_ticket', 'points'];
}
