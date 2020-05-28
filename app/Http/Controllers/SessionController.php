<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Map;
use App\Session;
use App\Sessioninteraction;
use App\Organization;
use Sentinel;

class SessionController extends Controller
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
        //
    }


    public function create_in_organization($id)
    {
        $map = Map::find(1);
        $organization = Organization::where('organization_recordid', '=', $id)->select('organization_recordid', 'organization_name')->first();
        $disposition_list = ['Success', 'Limited Success', 'Unable to Connect'];
        $method_list = ['Web and Call', 'Web', 'Email', 'Call', 'SMS'];
        
        return view('frontEnd.session-create-in-organization', compact('map', 'organization', 'disposition_list', 'method_list'));
    }


    public function add_new_session_in_organization(Request $request)
    {
        $session = new Session;

        $new_recordid = Session::max('session_recordid') + 1;
        $session->session_recordid = $new_recordid;

        $user = Sentinel::getUser();
        $date_time = date("Y-m-d h:i:sa");
        
        $session->session_name = $request->session_name;
        $session->session_method = $request->session_method;
        $session->session_disposition = $request->session_disposition;
        $session->session_notes = $request->session_notes;
        $session->session_edits = $request->session_records_edited;

        $session_organization_id = $request->session_organization;
        $session->session_organization = $session_organization_id;

        $session->session_performed_by = $user->id;
        $session->session_performed_at = $date_time;

        $session->save();

        return redirect('organization/'.$session_organization_id);
    }

    public function add_interaction(Request $request) 
    {
        $interaction = new Sessioninteraction;
        $session_recordid = $request->session_recordid;
        $interaction->interaction_session = $request->session_recordid;

        $new_recordid = Sessioninteraction::max('interaction_recordid') + 1;
        $interaction->interaction_recordid = $new_recordid;

        $interaction->interaction_method = $request->interaction_method;
        $interaction->interaction_disposition = $request->interaction_disposition;
        $interaction->interaction_notes = $request->interaction_notes;
        $interaction->interaction_records_edited = $request->interaction_records_edited;
        $date_time = date("Y-m-d h:i:sa");
        $interaction->interaction_timestamp = $date_time;

        $interaction->save();

        return redirect('session/'. $session_recordid);
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


    public function session($id) 
    {
        $map = Map::find(1);
        $session = Session::where('session_recordid', '=', $id)->first();
        $disposition_list = ['Success', 'Limited Success', 'Unable to Connect'];
        $method_list = ['Web and Call', 'Web', 'Email', 'Call', 'SMS'];
        $interaction_list = Sessioninteraction::where('interaction_session', '=', $id)->get();
        return view('frontEnd.session', compact('session', 'map', 'disposition_list', 'method_list', 'interaction_list'));
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
