<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Functions\Airtable;
use App\Location;
use App\Service;
use App\Organization;
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
            $facility_info[2] = $facility->location_name;
            $facility_info[3] = $facility->organization['organization_name'];

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
            $facility_info[5] = $facility->location_description;

            array_push($result, $facility_info);
        }
        return response()->json(array('data' => $result, 'recordsTotal' => $total_count, 'recordsFiltered' => $filtered_count));
    }


    public function facility($id) 
    {
        $facility = Location::where('location_recordid', '=', $id)->first();
        $map = Map::find(1);

        $organization_id = $facility->location_organization;
        $organization_name_info = Organization::where('organization_recordid', '=', $organization_id)->select('organization_name')->first();
        $organization_name = $organization_name_info["organization_name"];

        $facility_services_recordid_list = explode(',', $facility->location_services);
        $facility_services = Service::whereIn('service_recordid', $facility_services_recordid_list)->orderBy('service_name')->paginate(10);
        
        return view('frontEnd.location', compact('facility', 'map', 'organization_id', 'organization_name', 'facility_services'));

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
        return view('frontEnd.location-edit', compact('facility', 'map'));
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
        $location = Location::find($id);
        $location->location_name = $request->facility_location_name;
        $location->save();
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
