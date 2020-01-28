<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $table = 'organizations';

    protected $primaryKey = 'organization_recordid';
    
	public $timestamps = false;

	public function services()
    {
        $this->primaryKey='organization_recordid';
        return $this->hasMany('App\Service', 'service_organization', 'organization_recordid');
        
    }

    public function phones()
    {
        return $this->belongsToMany('App\Phone', 'organizations_phones', 'organization_recordid', 'phone_recordid');
    }

    public function location()
    {
        return $this->hasmany('App\Location', 'location_organization', 'organization_recordid');
    }

    public function contact()
    {
        return $this->hasmany('App\Contact', 'contact_organizations', 'organization_recordid');
    }

    public function details()
    {
        return $this->belongsToMany('App\Detail', 'organization_detail', 'organization_recordid', 'detail_recordid');
    }
    public function owner()
    {
        return $this->belongsToMany('App\User', 'organizations_users', 'organization_recordid', 'user_id');
    }
}
