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


    // public function create_in_organization($id)
    // {
    //     $map = Map::find(1);
    //     $organization = Organization::where('organization_recordid', '=', $id)->select('organization_recordid', 'organization_name')->first();
    //     $disposition_list = ['Success', 'Limited Success', 'Unable to Connect'];
    //     $method_list = ['Web and Call', 'Web', 'Email', 'Call', 'SMS'];
    //     $session_status_list = ['Success', 'Partial Success', 'Unable to verify', 'Out of business'];
        
    //     return view('frontEnd.session-create-in-organization', compact('map', 'organization', 'disposition_list', 'method_list', 'session_status_list'));
    // }

    public function create_in_organization($id)
    {

        $session = new Session;
        $new_recordid = Session::max('session_recordid') + 1;
        $session->session_recordid = $new_recordid;
        $user = Sentinel::getUser();
        $date_time = date("Y-m-d h:i:sa");
        $session->session_name = 'session' . $new_recordid;
        $session->session_organization = $id;

        if ($user) {
            $session->session_performed_by = $user->id;
        }
        
        $session->session_performed_at = $date_time;
        $session->session_edits = '0';

        $session->save();

        $map = Map::find(1);
        
        return redirect('session/'. $new_recordid);
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

        if ($user) {
            $session->session_performed_by = $user->id;
        }
        
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

        $session = Session::where('session_recordid', '=', $session_recordid)->first();
        $session_original_edits = $session->session_edits;
        $session_new_edits = $session_original_edits + $request->interaction_records_edited;
        $session->session_edits = $session_new_edits;
        $session->save();

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

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $map = Map::find(1);
        $session = Session::where('session_recordid', '=', $id)->first();
        $session_status_list = ['Success', 'Partial Success', 'Unable to verify', 'Out of business'];
        return view('frontEnd.session-edit', compact('session', 'map', 'session_status_list'));
    }

    public function session_start(Request $request) 
    {
        $map = Map::find(1);
        $id = $request->input('session_id');
        $session = Session::where('session_recordid', '=', $id)->first();
        $disposition_list = ['Success', 'Limited Success', 'Unable to Connect'];
        $session_start_time = $request->input('session_start_time');
        $session->session_start = $session_start_time;
        $session->session_start_datetime = date("Y-m-d H:i:sa"); 
        $session->save();
        return 'success';
    }

    public function session_end(Request $request) 
    {
        $map = Map::find(1);
        $id = $request->input('session_id');
        $session = Session::where('session_recordid', '=', $id)->first();
        $disposition_list = ['Success', 'Limited Success', 'Unable to Connect'];
        $method_list = ['Web and Call', 'Web', 'Email', 'Call', 'SMS'];
        $interaction_list = Sessioninteraction::where('interaction_session', '=', $id)->get();
        $session_end_time = $request->input('session_end_time');
        $session->session_end = $session_end_time;
        $session->session_end_datetime = date("Y-m-d H:i:sa"); 

        $date1 = strtotime($session->session_start_datetime);  
        $date2 = strtotime(date("Y-m-d h:i:sa"));
        $diff = abs($date2 - $date1);  
        $years = floor($diff / (365*60*60*24));  
        $months = floor(($diff - $years * 365*60*60*24)/(30*60*60*24));
        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/(60*60*24));
        $hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24) / (60*60));
        $minutes = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);  
        $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60));
        $duration = $years . " years,  ". $months . " months, " . $days . " days, ". $hours . " hours, " . $minutes . " minutes, ". $seconds . " seconds";
        $session->session_duration = $duration;
        $session->save();

        return response()->json($duration);
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
        $session = Session::where('session_recordid', '=', $id)->first();
        $session->session_notes = $request->session_notes;
        $session->session_verification_status = $request->session_status;
        $session->save();
        return redirect('session/'. $id);
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
