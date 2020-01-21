<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Map;
use App\Organization;
use Sentinel;


class AccountController extends Controller
{
    public function account()
    {
        $map = Map::find(1);
        $user = Sentinel::getUser();
        $user_organizationid_list = explode(',', $user->user_organization);
        $organization_list = Organization::whereIn('organization_recordid', $user_organizationid_list)->select('organization_recordid', 'organization_name')->orderby('organization_recordid')->get();
        return view('frontEnd.account', compact('map', 'user', 'organization_list'));
    }
}
