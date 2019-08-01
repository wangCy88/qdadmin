<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrabFeedbackType extends Model
{
    protected $table = 'grab_feedback_type';

    protected $fillable = ['type_name'];
}
