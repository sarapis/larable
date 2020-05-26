<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'session';
    protected $primaryKey = 'session_recordid';
	public $timestamps = false;

	public function user()
    {
        return $this->belongsTo('App\User', 'session_performed_by', 'id');
    }

    public function organization()
    {
        return $this->belongsTo('App\Organization', 'session_organization', 'organization_recordid');
    }
}
