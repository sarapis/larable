<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $table = 'emails';
    protected $primaryKey = 'email_recordid';
	public $timestamps = false;
}
