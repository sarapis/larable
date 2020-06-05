<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Map;
use App\Organization;
use App\Suggest;
use App\Email;

class SuggestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $map = Map::find(1);
        $organizations = Organization::select("organization_recordid", "organization_name")->distinct()->get();

        return view('frontEnd.suggest-create', compact('map', 'organizations'));
    }

    public function add_new_suggestion(Request $request)
    {
        $suggest = new Suggest;

        $new_recordid = Suggest::max('suggest_recordid') + 1;
        $suggest->suggest_recordid = $new_recordid;
        
        $date_time = date("Y-m-d h:i:sa");

        $suggest->suggest_organization = $request->suggest_organization;
        $suggest->suggest_content = $request->suggest_content;
        $suggest->suggest_username = $request->suggest_name;
        $suggest->suggest_user_email = $request->suggest_email;
        $suggest->suggest_user_phone = $request->suggest_phone;
        $suggest->suggest_created_at = $date_time;

        $suggest->save();

        return redirect('suggest_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
