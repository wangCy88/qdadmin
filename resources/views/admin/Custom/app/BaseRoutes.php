<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseRoutes extends Model
{
    protected $table = 'base_routes';

    protected $fillable = ['route', 'name', 'upid'];
}
