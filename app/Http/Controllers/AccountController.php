<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Map;


class AccountController extends Controller
{
    public function account()
    {
        $map = Map::find(1);
        return view('frontEnd.account', compact('map'));
    }
}
