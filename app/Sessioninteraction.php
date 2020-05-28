<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sessioninteraction extends Model
{
    protected $table = 'session_interaction';
    protected $primaryKey = 'interaction_recordid';
	public $timestamps = false;

	public function session()
    {
        return $this->belongsTo('App\Session', 'interaction_session', 'session_recordid');
    }
}
