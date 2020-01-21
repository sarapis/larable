<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Map;
use App\Organization;
use Sentinel;


class AccountController extends Controller
{
    public function account($id)
    {
        $map = Map::find(1);
        $user = Sentinel::getUser();
        $user_organizationid_list = explode(',', $user->user_organization);
        $organization_list = Organization::whereIn('organization_recordid', $user_organizationid_list)->select('organization_recordid', 'organization_name')->orderby('organization_recordid')->get();
        return view('frontEnd.account', compact('map', 'user', 'organization_list'));
    }

    public function edit($id) {
    	$map = Map::find(1);
        $user_info = User::where('id', '=', $id)->first(); 
        $organization_list = Organization::select('organization_recordid', 'organization_name')->get();
        $account_organization_list = explode(',', $user_info->user_organization);

        return view('frontEnd.account-edit', compact('user_info', 'map', 'organization_list', 'account_organization_list'));
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->account_email;     
        $user->user_organization = join(',', $request->account_organizations);
        $user->save();

        return redirect('account/'.$id);
    }
}
