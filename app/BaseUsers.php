<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseUsers extends Model
{
    protected $table = 'base_users';

    protected $fillable = ['account', 'name', 'phone', 'password', 'sex', 'status', 'gid'];

    public function baseGroups()
    {
        return $this->hasOne('App\BaseGroups', 'id', 'gid');
    }
}
