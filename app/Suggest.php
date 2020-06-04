<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Suggest extends Model
{
    protected $table = 'suggest';
    protected $primaryKey = 'seggest_recordid';
	public $timestamps = false;
}
