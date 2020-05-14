<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Functions\Airtable;
use App\Location;
use App\Service;
use App\Schedule;
use App\Phone;
use App\Address;
use App\Organization;
use App\Detail;
use App\Comment;
use App\Airtablekeyinfo;
use App\Locationaddress;
use App\Locationphone;
use App\Locationschedule;
use App\Accessibility;
use App\Airtables;
use App\Map;
use App\CSV_Source;
use App\Source_data;
use App\Services\Stringtoint;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use Sentinel;

class LocationController extends Controller
{

    public function airtable($api_key, $base_url)
    {

        $airtable_key_info = Airtablekeyinfo::find(1);
        if (!$airtable_key_info){
            $airtable_key_info = new Airtablekeyinfo;
        }
        $airtable_key_info->api_key = $api_key;
        $airtable_key_info->base_url = $base_url;
        $airtable_key_info->save();

        Location::truncate();
        Locationaddress::truncate();
        Locationphone::truncate();
        Locationschedule::truncate();

        // $airtable = new Airtable(array(
        //     'api_key'   => env('AIRTABLE_API_KEY'),
        //     'base'      => env('AIRTABLE_BASE_URL'),
        // ));
        $airtable = new Airtable(array(
            'api_key'   => $api_key,
            'base'      => $base_url,
        ));

        $request = $airtable->getContent( 'locations' );

        do {


            $response = $request->getResponse();

            $airtable_response = json_decode( $response, TRUE );

            foreach ( $airtable_response['records'] as $record ) {

                $location = new Location();
                $strtointclass = new Stringtoint();
                $location->location_recordid= $strtointclass->string_to_int($record[ 'id' ]);
                $location->location_name = isset($record['fields']['name'])?$record['fields']['name']:null;

                $location->location_organization = isset($record['fields']['organization'])? implode(",", $record['fields']['organization']):null;

                $location->location_organization = $strtointclass->string_to_int($location->location_organization);

                $location->location_alternate_name = isset($record['fields']['alternate_name'])?$record['fields']['alternate_name']:null;
                $location->location_transportation = isset($record['fields']['transportation'])?$record['fields']['transportation']:null;
                $location->location_latitude = isset($record['fields']['latitude'])?$record['fields']['latitude']:null;
                $location->location_longitude = isset($record['fields']['longitude'])?$record['fields']['longitude']:null;
                $location->location_description = isset($record['fields']['description'])?$record['fields']['description']:null;
                $location->location_services = isset($record['fields']['services'])? implode(",", $record['fields']['services']):null;  

                if(isset($record['fields']['services'])){
                    $i = 0;
                    foreach ($record['fields']['services']  as  $value) {

                        $locationservice=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $location->location_services = $location->location_services. ','. $locationservice;
                        else
                            $location->location_services = $locationservice;
                        $i ++;
                    }
                } 
               
                if(isset($record['fields']['phones'])){
                    $i = 0;
                    foreach ($record['fields']['phones']  as  $value) {

                        $location_phone = new Locationphone();
                        $location_phone->location_recordid=$location->location_recordid;
                        $location_phone->phone_recordid=$strtointclass->string_to_int($value);
                        $location_phone->save();
                        if($i != 0)
                            $location->location_phones = $location->location_phones. ','. $location_phone->phone_recordid;
                        else
                            $location->location_phones = $location_phone->phone_recordid;
                        $i ++;
                    }
                }

                $location->location_details = isset($record['fields']['details'])? implode(",", $record['fields']['details']):null;
            

                if(isset($record['fields']['schedule'])){
                    $i = 0;
                    foreach ($record['fields']['schedule']  as  $value) {
                        $locationschedule = new Locationschedule();
                        $locationschedule->location_recordid=$location->location_recordid;
                        $locationschedule->schedule_recordid=$strtointclass->string_to_int($value);
                        $locationschedule->save();
                        if($i != 0)
                            $location->location_schedule = $location->location_schedule. ','. $locationschedule->schedule_recordid;
                        else
                            $location->location_schedule = $locationschedule->schedule_recordid;
                        $i ++;
                    }
                } 

                $location->location_address = isset($record['fields']['address'])? implode(",", $record['fields']['address']):null;  

                if(isset($record['fields']['address'])){
                    $i=0;
                    foreach ($record['fields']['address']  as  $value) {
                        $locationaddress = new Locationaddress();
                        $locationaddress->location_recordid=$location->location_recordid;
                        $locationaddress->address_recordid=$strtointclass->string_to_int($value);
                        $locationaddress->save();
                        if($i != 0)
                            $location->location_address = $location->location_address. ','. $locationaddress->address_recordid;
                        else
                            $location->location_address = $locationaddress->address_recordid;
                        $i ++;
                    }
                }    
                
                $location ->save();

            }
            
        }
        while( $request = $response->next() );

        $date = date("Y/m/d H:i:s");
        $airtable = Airtables::where('name', '=', 'Locations')->first();
        $airtable->records = Location::count();
        $airtable->syncdate = $date;
        $airtable->save();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function csv(Request $request)
    {


        $path = $request->file('csv_file')->getRealPath();

        $data = Excel::load($path)->get();

        $filename =  $request->file('csv_file')->getClientOriginalName();
        $request->file('csv_file')->move(public_path('/csv/'), $filename);

        if ($filename!='locations.csv') 
        {
            $response = array(
                'status' => 'error',
                'result' => 'This CSV is not correct.',
            );
            return $response;
        }

        if (count($data) > 0) {
            $csv_header_fields = [];
            foreach ($data[0] as $key => $value) {
                $csv_header_fields[] = $key;
            }
            $csv_data = $data;
        }


        Location::truncate();

        foreach ($csv_data as $row) {
            
            $location = new Location();

            $location->location_recordid= $row['id'];
            $location->location_name = $row['name']!='NULL'?$row['name']:null;

            $location->location_organization = $row['organization_id'];

            $location->location_alternate_name = $row['alternate_name']!='NULL'?$row['alternate_name']:null;
            $location->location_description = $row['description']!='NULL'?$row['description']:null;
            $location->location_latitude = $row['latitude']!='NULL'?$row['latitude']:null;
            $location->location_longitude = $row['longitude']!='NULL'?$row['longitude']:null;
            $location->location_transportation = $row['transportation']!='NULL'?$row['transportation']:null;
           
                                     
            $location ->save();

           
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Locations')->first();
        $csv_source->records = Location::count();
        $csv_source->syncdate = $date;
        $csv_source->save();
    }

    public function csv_accessibility(Request $request)
    {


        $path = $request->file('csv_file')->getRealPath();

        $data = Excel::load($path)->get();

        $filename =  $request->file('csv_file')->getClientOriginalName();
        $request->file('csv_file')->move(public_path('/csv/'), $filename);

        if ($filename!='accessibility_for_disabilities.csv') 
        {
            $response = array(
                'status' => 'error',
                'result' => 'This CSV is not correct.',
            );
            return $response;
        }

        if (count($data) > 0) {
            $csv_header_fields = [];
            foreach ($data[0] as $key => $value) {
                $csv_header_fields[] = $key;
            }
            $csv_data = $data;
        }

        if ($csv_header_fields[0]!='id' || $csv_header_fields[1]!='location_id' || $csv_header_fields[2]!='accessibility' || $csv_header_fields[3]!='details') 
        {
            $response = array(
                'status' => 'error',
                'result' => 'This CSV field is not matched.',
            );
            return $response;
        }

        Accessibility::truncate();

        $size = '';
        foreach ($csv_data as $key => $row) {

            $accessibility = new Accessibility();

            $accessibility->accessibility_recordid =$row[$csv_header_fields[0]]!='NULL'?$row[$csv_header_fields[0]]:null;
            $accessibility->location_recordid = $row[$csv_header_fields[1]]!='NULL'?$row[$csv_header_fields[1]]:null;
            $accessibility->accessibility =$row[$csv_header_fields[2]]!='NULL'?$row[$csv_header_fields[2]]:null;
            ;   
            $accessibility->details =$row[$csv_header_fields[3]]!='NULL'?$row[$csv_header_fields[3]]:null;
            ;       
            $accessibility->save();

           
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Accessibility_for_disabilites')->first();
        $csv_source->records = Accessibility::count();
        $csv_source->syncdate = $date;
        $csv_source->save();
    }

    public function index()
    {
        $facilities = Location::orderBy('id', 'desc')->get();      
        $map = Map::find(1);

        return view('frontEnd.locations', compact('map', 'facilities'));
    }

    public function get_all_facilities(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $search_term = $request->search_term;

       
        $facilities = Location::orderBy('location_recordid', 'DESC');

        if ($search_term) {
            $facilities = $facilities
                ->where('location_name', 'LIKE', '%' . $search_term . '%')
                ->orWhere('location_description', 'LIKE', '%' . $search_term . '%')
                ->whereHas('organization', function (Builder $query) use ($search_term) {
                    $query->where('organization_name', 'LIKE', '%' . $search_term . '%');
                });
        }

        $filtered_count = $facilities->count();

        $facilities = $facilities->offset($start)->limit($length)->get();
        $total_count = Location::count();
        $result = [];
        $facility_info = [];
        foreach ($facilities as $facility) {
            
            $facility_info[0] = '';
            $facility_info[1] = $facility->location_recordid; 
            $facility_info[2] = str_limit($facility->location_name, 30, '...');
            if ($facility->organization) {
                $facility_info[3] = str_limit($facility->organization['organization_name'], 25, '...');
            } else {
                $facility_info[3] = '';
            }
            

            $facility_full_address_info = '';
            if (isset($facility->address[0])) {
                $facility_full_address_info = $facility_full_address_info . $facility->address[0]['address_1'];
                if ($facility->address[0]['address_city']) {
                    $facility_full_address_info = $facility_full_address_info . ', ' . $facility->address[0]['address_city'];
                }
                if ($facility->address[0]['address_state']) {
                    $facility_full_address_info = $facility_full_address_info . ', ' . $facility->address[0]['address_state'];
                }
                if ($facility->address[0]['address_zip_code']) {
                    $facility_full_address_info = $facility_full_address_info . ', ' . $facility->address[0]['address_zip_code'];
                }
            }

            $facility_info[4] = $facility_full_address_info;
            $facility_info[5] = str_limit($facility->location_description, 15, '...');

            array_push($result, $facility_info);
        }
        return response()->json(array('data' => $result, 'recordsTotal' => $total_count, 'recordsFiltered' => $filtered_count));
    }


    public function facility($id) 
    {
        $facility = Location::where('location_recordid', '=', $id)->first();
        $locations = Location::with('services', 'address', 'phones')->where('location_recordid', '=', $id)->get();
        $map = Map::find(1);

        $facility_services_recordid_list = explode(',', $facility->location_services);
        $facility_services = Service::whereIn('service_recordid', $facility_services_recordid_list)->orderBy('service_name')->paginate(10);

        $facility_organization_recordid_list = explode(',', $facility->location_organization);
        $facility_organizations = Organization::whereIn('organization_recordid', $facility_organization_recordid_list)->orderBy('organization_name')->paginate(10);

        $comment_list = Comment::where('comments_location', '=', $id)->get();
        
        return view('frontEnd.location', compact('facility', 'map', 'locations', 'facility_organizations', 'facility_services', 'comment_list'));

    }


    public function add_comment(Request $request, $id)
    {

        $facility = Location::find($id);
        $comment_content = $request->reply_content;
        $user = Sentinel::getUser();
        $date_time = date("Y-m-d h:i:sa");
        $comment = new Comment();

        $comment_recordids = Comment::select("comments_recordid")->distinct()->get();
        $comment_recordid_list = array();
        foreach ($comment_recordids as $key => $value) {
            $comment_recordid = $value->comments_recordid;
            array_push($comment_recordid_list, $comment_recordid);
        }
        $comment_recordid_list = array_unique($comment_recordid_list);
        $new_recordid = Comment::max('comments_recordid') + 1;
        if (in_array($new_recordid, $comment_recordid_list)) {
            $new_recordid = Comment::max('comments_recordid') + 1;
        }

        $comment->comments_recordid = $new_recordid;
        $comment->comments_content = $comment_content;
        $comment->comments_user = $user->id;
        $comment->comments_user_firstname = $user->first_name;
        $comment->comments_user_lastname = $user->last_name;
        $comment->comments_location = $id;
        $comment->comments_datetime = $date_time;
        $comment->save();

        $comment_list = Comment::where('comments_location', '=', $id)->get();

        return redirect('facility/' . $id);

    }

    public function create_in_organization($id)
    {
        $map = Map::find(1);
        $organization = Organization::where('organization_recordid', '=', $id)->select('organization_recordid', 'organization_name')->first();
        return view('frontEnd.facility-create-in-organization', compact('map', 'organization'));
    }

    public function create()
    {
        $map = Map::find(1);       
        $organization_names = Organization::select("organization_name")->distinct()->get();
        $organization_name_list = [];
        foreach ($organization_names as $key => $value) {
            $org_names = explode(", ", trim($value->organization_name));
            $organization_name_list = array_merge($organization_name_list, $org_names);
        }
        $organization_name_list = array_unique($organization_name_list);

        $service_info_list = Service::select('service_recordid', 'service_name')->orderBy('service_name')->distinct()->get();
        $schedule_info_list = Schedule::select('schedule_recordid', 'schedule_opens_at', 'schedule_closes_at')->whereNotNull('schedule_opens_at')->orderBy('schedule_opens_at')->distinct()->get();
        $address_info_list = Address::select('address_recordid', 'address_1', 'address_city', 'address_state_province', 'address_postal_code')->orderBy('address_1')->distinct()->get();
        $detail_info_list = Detail::select('detail_recordid', 'detail_value')->orderBy('detail_value')->distinct()->get();

        return view('frontEnd.facility-create', compact('map', 'organization_name_list', 'service_info_list', 'schedule_info_list', 'address_info_list', 'detail_info_list'));
    }


    public function add_new_facility(Request $request)
    {
        $facility = new Location;

        $facility_recordids = Location::select("location_recordid")->distinct()->get();
        $facility_recordid_list = array();
        foreach ($facility_recordids as $key => $value) {
            $facility_recordid = $value->location_recordid;
            array_push($facility_recordid_list, $facility_recordid);
        }
        $facility_recordid_list = array_unique($facility_recordid_list);

        $new_recordid = Location::max('location_recordid') + 1;
        if (in_array($new_recordid, $facility_recordid_list)) {
            $new_recordid = Contact::max('location_recordid') + 1;
        }
        $facility->location_recordid = $new_recordid;
        
        $facility->location_name = $request->location_name;
        $facility->location_alternate_name = $request->location_alternate_name;
        $facility->location_transportation = $request->location_transporation;
        $facility->location_description = $request->location_description;
        $facility->location_details = $request->location_details;

        $organization_name = $request->facility_organization;
        $facility_organization = Organization::where('organization_name', '=', $organization_name)->first();
        $facility_organization_id = $facility_organization["organization_recordid"];
        $facility->location_organization = $facility_organization_id;

        if ($request->facility_service) {
            $facility->location_services = join(',', $request->facility_service);
        } else {
            $facility->location_services = '';
        }
        $facility->services()->sync($request->facility_service);

        if ($request->service_schedules) {
            $facility->location_schedule = join(',', $request->facility_schedules);
        } else {
            $facility->location_schedule = '';
        }
        $facility->schedules()->sync($request->facility_schedules);

        if ($request->facility_address) {
            $facility->location_address = join(',', $request->facility_address);
        } else {
            $facility->location_address = '';
        }
        $facility->address()->sync($request->facility_address);

        $phone_recordids = Phone::select("phone_recordid")->distinct()->get();
        $phone_recordid_list = array();
        foreach ($phone_recordids as $key => $value) {
            $phone_recordid = $value->phone_recordid;
            array_push($phone_recordid_list, $phone_recordid);
        }
        $phone_recordid_list = array_unique($phone_recordid_list);

        $facility_phones = $request->facility_phones;
        $cell_phone = Phone::where('phone_number', '=', $facility_phones)->first();
        if ($cell_phone != null) {
            $cell_phone_id = $cell_phone["phone_recordid"];
            $facility->location_phones = $cell_phone_id;
        } else {
            $phone = new Phone;
            $new_recordid = Phone::max('phone_recordid') + 1;
            if (in_array($new_recordid, $phone_recordid_list)) {
                $new_recordid = Phone::max('phone_recordid') + 1;
            }
            $phone->phone_recordid = $new_recordid;
            $phone->phone_number = $facility_phones;
            $phone->phone_type = "voice";
            $facility->location_phones = $phone->phone_recordid;
            $phone->save();
        }

        $facility_phone_info_list = array();
        array_push($facility_phone_info_list, $facility->location_phones);
        $facility_phone_info_list = array_unique($facility_phone_info_list);
        $facility->phones()->sync($facility_phone_info_list);


        $facility->save();

        return redirect('facilities');
    }

    public function add_new_facility_in_organization(Request $request)
    {
        $facility = new Location;
        
        $facility->location_name = $request->location_name;

        $organization_name = $request->facility_organization;
        $facility_organization = Organization::where('organization_name', '=', $organization_name)->first();
        $facility_organization_id = $facility_organization["organization_recordid"];
        $facility->location_organization = $facility_organization_id;

        $facility->location_description = $request->location_description;

        $facility_recordids = Location::select("location_recordid")->distinct()->get();
        $facility_recordid_list = array();
        foreach ($facility_recordids as $key => $value) {
            $facility_recordid = $value->location_recordid;
            array_push($facility_recordid_list, $facility_recordid);
        }
        $facility_recordid_list = array_unique($facility_recordid_list);

        $new_recordid = Location::max('location_recordid') + 1;
        if (in_array($new_recordid, $facility_recordid_list)) {
            $new_recordid = Contact::max('location_recordid') + 1;
        }
        $facility->location_recordid = $new_recordid;

        $facility->save();

        return redirect('organization/'.$facility_organization_id);
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
        $process= Location::find($id);
        return response()->json($process);
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
        $facility = Location::where('location_recordid', '=', $id)->first();

        $organization_names = Organization::pluck("organization_name", "organization_recordid");
        $organization_id = $facility->location_organization;
        $organization_name_info = Organization::where('organization_recordid', '=', $organization_id)->select('organization_name')->first();
        $facility_organization_name = '';
        if ($organization_name_info) {
            $facility_organization_name = $organization_name_info["organization_name"];
        }

        $services_info_list = Service::select('service_recordid', 'service_name')->get();
        $facility_service_list = explode(',', $facility->location_services);

        $facility_organization_list = explode(',', $facility->location_organization);

        return view('frontEnd.location-edit', compact('facility', 'map', 'facility_organization_name', 'services_info_list', 'facility_service_list', 'organization_names', 'facility_organization_list'));
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

        $facility = Location::find($id);

        $facility->location_name = $request->location_name;
        $facility->location_organization = $request->facility_organization_name;
        $facility->location_description = $request->location_description;

        $facility->save();

        return redirect('facility/'.$id);
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
