<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $table = 'phones';

    protected $primaryKey = 'phone_recordid';
    
	public $timestamps = false;

    public function locations()
    {
        return $this->belongsToMany('App\Location', 'location_phone', 'phone_recordid', 'location_recordid');
    }

    public function services()
    {
        return $this->belongsToMany('App\Service', 'service_phone', 'phone_recordid', 'service_recordid');
    }

    public function organization()
    {
        return $this->belongsToMany('App\Organization', 'organizations_phones', 'phone_recordid', 'organization_recordid');
    }

    public function contact()
    {
        return $this->hasMany('App\Contact', 'contact_phones', 'phone_recordid');
    }

    public function schedule()
    {
        return $this->belongsTo('App\Schedule', 'phone_schedule', 'schedule_recordid');
    }
}
