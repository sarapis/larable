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
        $organization_info = Organization::where('organization_recordid', '=', $request->suggest_organization)->first();
        $suggest->suggest_content = $request->suggest_content;        
        $suggest->suggest_username = $request->suggest_name;

        $new_suggest_email_info = $request->suggest_email;
        $existing_email_info = Email::where('email_info', '=', $new_suggest_email_info)->first();
        if ($existing_email_info) {
            $suggest->suggest_user_email = $existing_email_info->email_recordid;
        } else {
            $suggest->suggest_user_email = Email::max('email_recordid') + 1;
        }

        $suggest->suggest_user_phone = $request->suggest_phone;
        $suggest->suggest_created_at = $date_time;
        
        $from = env('MAIL_FROM_ADDRESS');
        $name = env('MAIL_FROM_NAME');
        $from_phone = env('MAIL_FROM_PHONE');        

        $email = new \SendGrid\Mail\Mail();
        $email->setFrom($from, $name);
        $subject = 'Suggested Change Submission for Larable';
        $email->setSubject($subject);
        $contact_email = $request->suggest_email;
        $username = $request->suggest_name;
        $email->addTo($contact_email, $username);
        $body = $request->suggest_content;

        $message = '<html><body>';
        $message .= '<h1 style="color:#424242;">Thanks for your suggestion!</h1>';
        $message .= '<p style="color:#424242;font-size:18px;">The following was submitted at Larable</p>';
        $message .= '<p style="color:#424242;font-size:12px;">ID: '. $new_recordid .'</p>';
        $message .= '<p style="color:#424242;font-size:12px;">Timestamp: '. $date_time .'</p>';
        $message .= '<p style="color:#424242;font-size:12px;">Organization: '. $organization_info->organization_name .'</p>';
        $message .= '<p style="color:#424242;font-size:12px;">Body: '. $body .'</p>';
        $message .= '<p style="color:#424242;font-size:12px;">From: '. $name .'</p>';
        $message .= '<p style="color:#424242;font-size:12px;">Email: '. $from .'</p>';
        $message .= '<p style="color:#424242;font-size:12px;">Phone: '. $from_phone .'</p>';
        $message .= '</body></html>';

        $email->addContent("text/html", $message);
        $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
        $response = $sendgrid->send($email);

        // var_dump($response);
        // exit;

        $error = '';
        if ($response->statusCode() == 401) {
            $error = json_decode($response->body());
        }
        if ($error == '') {

            $suggest->save();

            $existing_email_list = Email::select('email_info')->pluck('email_info')->toArray();
            if (!in_array($contact_email, $existing_email_list)) {
                $email = new Email;  
                $new_recordid = Email::max('email_recordid') + 1;
                $email->email_recordid = $new_recordid;
                $email->email_info = $contact_email;     
                $email->save();
            }
            return redirect('suggest_create')->with('success', 'Your suggestion has been received.');
        } else {
            return redirect()->back()->with('error', $error);
        }
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
