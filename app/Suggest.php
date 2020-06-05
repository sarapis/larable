<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Suggest extends Model
{
    protected $table = 'suggest';
    protected $primaryKey = 'seggest_recordid';
	public $timestamps = false;

	public function organization()
    {
        return $this->belongsTo('App\Organization', 'suggest_organization', 'organization_recordid');
    }
}
