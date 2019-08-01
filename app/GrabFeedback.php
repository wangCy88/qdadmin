<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrabFeedback extends Model
{
    protected $table = 'grab_feedback';

    protected $fillable = ['name', 'phone', 'mchid', 'type', 'remark', 'status', 'answer'];

    public function grabFeedbackType()
    {
        //return $this->hasOne('App\GrabFeedbackType','id', 'type');
        return $this->belongsTo('App\GrabFeedbackType', 'type', 'id');
    }

    public function grabUsersPre()
    {
        return $this->belongsTo('App\GrabUsersPre', 'user_id', 'id');
    }
}
