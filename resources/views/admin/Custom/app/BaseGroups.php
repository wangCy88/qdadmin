<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseGroups extends Model
{
    protected $table = 'base_groups';

    protected $fillable = ['name', 'routes'];
}
