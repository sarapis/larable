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

    public function change_password($id) {
        $map = Map::find(1);
        $user_info = User::where('id', '=', $id)->first(); 

        return view('frontEnd.account-change-password', compact('user_info', 'map'));
    }

    public function update_password(Request $request, $id) 
    {
        $user = User::find($id);
        $new_password = $request->new_password;
        $confirm_password = $request->confirm_password;
        if ($new_password == $confirm_password) {
            Sentinel::update($user, array('password' => $new_password));
            return redirect('account/'.$id);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if ($request->first_name) {
          $user->first_name = $request->first_name;
        }
        if ($request->last_name) {
          $user->last_name = $request->last_name;
        }
        if ($request->account_email) {
          $user->email = $request->account_email;
        }

        if ($request->account_organizations) {
          $user->user_organization = join(',', $request->account_organizations);
        } else {
            $user->user_organization = '';
        }

        $user->save();
        
        $user->organizations()->sync($request->account_organizations);
        

        return redirect('account/'.$id);
    }
}
