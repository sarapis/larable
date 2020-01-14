<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Functions\Airtable;
use App\Service;
use App\Servicelocation;
use App\Servicephone;
use App\Servicedetail;
use App\Serviceaddress;
use App\Serviceorganization;
use App\Servicecontact;
use App\Organization;
use App\Servicetaxonomy;
use App\Alt_taxonomy;
use App\Serviceschedule;
use App\Location;
use App\Contact;
use App\Address;
use App\Phone;
use App\Airtables;
use App\CSV_Source;
use App\Source_data;
use App\Taxonomy;
use App\Airtablekeyinfo;
use App\Detail;
use App\Map;
use App\Layout;
use App\Metafilter;
use App\Services\Stringtoint;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function test_airtable($api_key, $base_url)
    {
        // var_dump($api_key);
        // var_dump($base_url);
        // var_dump("this is test function for auto sync");
        var_dump($api_key);
        var_dump($base_url);
        $response_text = "this is test function for auto sync";
        echo $response_text;

        return $response_text;
    }

    public function airtable($api_key, $base_url)
    {
        $airtable_key_info = Airtablekeyinfo::find(1);
        if (!$airtable_key_info){
            $airtable_key_info = new Airtablekeyinfo;
        }
        $airtable_key_info->api_key = $api_key;
        $airtable_key_info->base_url = $base_url;
        $airtable_key_info->save();

        Service::truncate();
        Servicelocation::truncate();
        Serviceaddress::truncate();
        Servicephone::truncate();
        Servicedetail::truncate();
        Serviceorganization::truncate();
        Servicecontact::truncate();
        Servicetaxonomy::truncate();
        Serviceschedule::truncate();

        // $airtable = new Airtable(array(
        //     'api_key'   => env('AIRTABLE_API_KEY'),
        //     'base'      => env('AIRTABLE_BASE_URL'),
        // ));
        $airtable = new Airtable(array(
            'api_key'   => $api_key,
            'base'      => $base_url,
        ));

        $request = $airtable->getContent( 'services' );
        $size = '';
        do {


            $response = $request->getResponse();

            $airtable_response = json_decode( $response, TRUE );

            foreach ( $airtable_response['records'] as $record ) {

                $service = new Service();
                $strtointclass = new Stringtoint();
                $service->service_recordid= $strtointclass->string_to_int($record[ 'id' ]);
                
                $service->service_name = isset($record['fields']['Name'])?$record['fields']['Name']:null;

                if(isset($record['fields']['Organization'])){
                    $i = 0;
                    foreach ($record['fields']['Organization']  as  $value) {
                        $service_organization = new Serviceorganization();
                        $service_organization->service_recordid=$service->service_recordid;
                        $service_organization->organization_recordid=$strtointclass->string_to_int($value);
                        $service_organization->save();
                        $serviceorganization=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $service->service_organization = $service->service_organization. ','. $serviceorganization;
                        else
                            $service->service_organization = $serviceorganization;
                        $i ++;
                    }
                }

                $service->service_alternate_name = isset($record['fields']['Alternate Name'])?$record['fields']['Alternate Name']:null;
                $service->service_description = isset($record['fields']['Description'])?$record['fields']['Description']:null;

                if(isset($record['fields']['locations'])){
                    $i = 0;
                    foreach ($record['fields']['locations']  as  $value) {
                        $service_location = new Servicelocation();
                        $service_location->service_recordid=$service->service_recordid;
                        $service_location->location_recordid=$strtointclass->string_to_int($value);
                        $service_location->save();
                        $servicelocation=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $service->service_locations = $service->service_locations. ','. $servicelocation;
                        else
                            $service->service_locations = $servicelocation;
                        $i ++;
                    }
                }
                
                $service->service_url = isset($record['fields']['url'])?$record['fields']['url']:null;
                $service->service_email = isset($record['fields']['email'])?$record['fields']['email']:null;
                $service->service_status = isset($record['fields']['status'])?$record['fields']['status']:null;

                if(isset($record['fields']['taxonomy'])){
                    $i = 0;
                    foreach ($record['fields']['taxonomy']  as  $value) {
                        $service_taxonomy = new Servicetaxonomy();
                        $service_taxonomy->service_recordid=$service->service_recordid;
                        $service_taxonomy->taxonomy_recordid=$strtointclass->string_to_int($value);
                        $service_taxonomy->save();
                        $servicetaxonomy=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $service->service_taxonomy = $service->service_taxonomy. ','. $servicetaxonomy;
                        else
                            $service->service_taxonomy = $servicetaxonomy;
                        $i ++;
                    }
                }

                $service->service_application_process = isset($record['fields']['application_process'])?$record['fields']['application_process']:null;
                $service->service_wait_time = isset($record['fields']['wait_time'])?$record['fields']['wait_time']:null;
                $service->service_fees = isset($record['fields']['fees'])?$record['fields']['fees']:null;
                $service->service_accreditations = isset($record['fields']['accreditations'])?$record['fields']['accreditations']:null;
                $service->service_licenses = isset($record['fields']['licenses'])?$record['fields']['licenses']:null;


                if(isset($record['fields']['phones'])){
                    $i = 0;
                    foreach ($record['fields']['phones']  as  $value) {
                        $service_phone = new Servicephone();
                        $service_phone->service_recordid=$service->service_recordid;
                        $service_phone->phone_recordid=$strtointclass->string_to_int($value);
                        $service_phone->save();
                        $servicephone=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $service->service_phones = $service->service_phones. ','. $servicephone;
                        else
                            $service->service_phones = $servicephone;
                        $i ++;
                    }
                }


                if(isset($record['fields']['schedule'])){
                    $i = 0;
                    foreach ($record['fields']['schedule']  as  $value) {
                        $service_schedule = new Serviceschedule();
                        $service_schedule->service_recordid=$service->service_recordid;
                        $service_schedule->schedule_recordid=$strtointclass->string_to_int($value);
                        $service_schedule->save();
                        $serviceschedule=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $service->service_schedule = $service->service_schedule. ','. $serviceschedule;
                        else
                            $service->service_schedule = $serviceschedule;
                        $i ++;
                    }
                }

                if(isset($record['fields']['contacts'])){
                    $i = 0;
                    foreach ($record['fields']['contacts']  as  $value) {
                        $service_contact = new Servicecontact();
                        $service_contact->service_recordid=$service->service_recordid;
                        $service_contact->contact_recordid=$strtointclass->string_to_int($value);
                        $service_contact->save();
                        $servicecontact=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $service->service_contacts = $service->service_contacts. ','. $servicecontact;
                        else
                            $service->service_contacts = $servicecontact;
                        $i ++;
                    }
                }

                if(isset($record['fields']['details'])){
                    $i = 0;
                    foreach ($record['fields']['details']  as  $value) {
                        $service_detail = new Servicedetail();
                        $service_detail->service_recordid=$service->service_recordid;
                        $service_detail->detail_recordid=$strtointclass->string_to_int($value);
                        $service_detail->save();
                        $servicedetail=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $service->service_details = $service->service_details. ','. $servicedetail;
                        else
                            $service->service_details = $servicedetail;
                        $i ++;
                    }
                }

                if(isset($record['fields']['address'])){
                    $i = 0;
                    foreach ($record['fields']['address']  as  $value) {
                        $service_addresses = new Serviceaddress();
                        $service_addresses->service_recordid=$service->service_recordid;
                        $service_addresses->address_recordid=$strtointclass->string_to_int($value);
                        $service_addresses->save();
                        $serviceaddress=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $service->service_address = $service->service_address. ','. $serviceaddress;
                        else
                            $service->service_address = $serviceaddress;
                        $i ++;
                    }
                }

                $service->service_metadata = isset($record['fields']['metadata'])? $record['fields']['metadata']:null;
       

                $service->service_airs_taxonomy_x = isset($record['fields']['AIRS Taxonomy-x'])? implode(",", $record['fields']['AIRS Taxonomy-x']):null;          
                
                $service ->save();

            }
           
        }
        while( $request = $response->next() );

        $date = date("Y/m/d H:i:s");
        $airtable = Airtables::where('name', '=', 'Services')->first();
        $airtable->records = Service::count();
        $airtable->syncdate = $date;
        $airtable->save();
    }

    public function csv(Request $request)
    {


        $path = $request->file('csv_file')->getRealPath();

        $data = Excel::load($path)->get();

        $filename =  $request->file('csv_file')->getClientOriginalName();
        $request->file('csv_file')->move(public_path('/csv/'), $filename);

        if ($filename!='services.csv') 
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


        Service::truncate();
        Serviceorganization::truncate();

        $size = '';
        foreach ($csv_data as $row) {
            
       
            $service = new Service();

            $service->service_recordid= $row['id'];
            $service->service_name = $row['name']!='NULL'?$row['name']:null;

            if($row['organization_id']){

                    $service_organization = new Serviceorganization();
                    $service_organization->service_recordid=$service->service_recordid;
                    $service_organization->organization_recordid=$row['organization_id'];
                    $service_organization->save();

                    $service->service_organization = $row['organization_id'];

            }

            $service->service_alternate_name = $row['alternate_name']!='NULL'?$row['alternate_name']:null;
            $service->service_description = $row['description']!='NULL'?$row['description']:null;
            $service->service_application_process = $row['application_process']!='NULL'?$row['application_process']:null;
            $service->service_url = $row['url']!='NULL'?$row['url']:null;
            $service->service_program = $row['program_id']!='NULL'?$row['program_id']:null;

            $service->service_email = $row['email']!='NULL'?$row['email']:null;
            $service->service_status = $row['status']!='NULL'?$row['status']:null;

            $service->service_wait_time = $row['wait_time']!='NULL'?$row['wait_time']:null;
            $service->service_fees = $row['fees']!='NULL'?$row['fees']:null;
            $service->service_accreditations = $row['accreditations']!='NULL'?$row['accreditations']:null;
            $service->service_licenses = $row['licenses']!='NULL'?$row['licenses']:null;
        
            
            $service ->save();

           
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Services')->first();
        $csv_source->records = Service::count();
        $csv_source->syncdate = $date;
        $csv_source->save();
    }

    public function csv_services_location(Request $request)
    {


        $path = $request->file('csv_file')->getRealPath();

        $data = Excel::load($path)->get();

        $filename =  $request->file('csv_file')->getClientOriginalName();
        $request->file('csv_file')->move(public_path('/csv/'), $filename);

        if ($filename!='services_at_location.csv') 
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


        Servicelocation::truncate();

        foreach ($csv_data as $key => $row) {

            $service_location = new Servicelocation();

            $service_location->location_recordid = $row['location_id']!='NULL'?$row['location_id']:null;
            $service_location->service_recordid =$row['service_id']!='NULL'?$row['service_id']:null;
                      
            $service_location->save();

           
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Services_location')->first();
        $csv_source->records = Servicelocation::count();
        $csv_source->syncdate = $date;
        $csv_source->save();
    }

    public function index()
    {
        $services = Service::with('locations', 'organizations', 'locations', 'taxonomy', 'phone', 'schedules', 'contact', 'details', 'address')->orderBy('service_recordid', 'asc')->paginate(20);
        $source_data = Source_data::find(1);      

        return view('backEnd.tables.tb_services', compact('services', 'source_data'));
    }


    public function services()
    {
        // $service_state_filter = 'Verified';
        // $services = Service::with('locations')->orderBy('service_name')->where('service_status', '=', $service_state_filter); 
        $services = Service::with('locations')->orderBy('service_name');
        $locations = Location::with('services','organization');
        
        $sort_by_distance_clickable = false;
        $map = Map::find(1);
        $parent_taxonomy = [];
        $child_taxonomy = [];
        $checked_organizations = [];
        $checked_insurances = [];
        $checked_ages = [];
        $checked_languages = [];
        $checked_settings = [];
        $checked_culturals = [];
        $checked_transportations = [];
        $checked_hours= [];
        $meta_status = 'On';

        $metas = Metafilter::all();
        $count_metas = Metafilter::count();       
        

        if($meta_status == 'On' && $count_metas > 0){
            $address_serviceids = Service::pluck('service_recordid')->toArray();
            $taxonomy_serviceids = Service::pluck('service_recordid')->toArray();

            foreach ($metas as $key => $meta) {
                $values = explode(",", $meta->values);

                if($meta->facet == 'Postal_code'){
                    $address_serviceids = [];
                    if($meta->operations == 'Include')
                        $serviceids = Serviceaddress::whereIn('address_recordid', $values)->pluck('service_recordid')->toArray();
                    if($meta->operations == 'Exclude')
                        $serviceids = Serviceaddress::whereNotIn('address_recordid', $values)->pluck('service_recordid')->toArray();
                    
                    $address_serviceids = array_merge($serviceids, $address_serviceids);
                    
                }
                if($meta->facet == 'Taxonomy'){

                    if($meta->operations == 'Include')
                        $serviceids = Servicetaxonomy::whereIn('taxonomy_recordid', $values)->pluck('service_recordid')->toArray();
                    if($meta->operations == 'Exclude')
                        $serviceids = Servicetaxonomy::whereNotIn('taxonomy_recordid', $values)->pluck('service_recordid')->toArray();
                    $taxonomy_serviceids = array_merge($serviceids, $taxonomy_serviceids);

                }
                if($meta->facet == 'Service_status'){

                    if($meta->operations == 'Include')
                        $serviceids = Service::whereIn('service_recordid', $values)->pluck('service_recordid')->toArray();
                    if($meta->operations == 'Exclude')
                        $serviceids = Service::whereNotIn('service_recordid', $values)->pluck('service_recordid')->toArray();
                    $taxonomy_serviceids = array_merge($serviceids, $taxonomy_serviceids);
                }
            }

            // $services = $services->whereIn('service_recordid', $address_serviceids)->whereIn('service_recordid', $taxonomy_serviceids);

            if ($address_serviceids) {
                $services = $services->whereIn('service_recordid', $address_serviceids);
            }
            if ($taxonomy_serviceids) {
                $services = $services->whereIn('service_recordid', $taxonomy_serviceids);
            }

            $services_ids = $services->pluck('service_recordid')->toArray();
            $locations_ids = Servicelocation::whereIn('service_recordid', $services_ids)->pluck('location_recordid')->toArray();
            $locations = $locations->whereIn('location_recordid', $locations_ids);

        }

        $services = $services->paginate(10); 

        $service_taxonomy_info_list = []; 
        foreach ($services as $key => $service) {
            $service_taxonomy_recordid_list = explode(',', $service->service_taxonomy);
            foreach ($service_taxonomy_recordid_list as $key => $service_taxonomy_recordid) {
                $taxonomy = Taxonomy::where('taxonomy_recordid', '=', (int)($service_taxonomy_recordid))->first();
                if(isset($taxonomy)){
                    $service_taxonomy_name = $taxonomy->taxonomy_name;
                    $service_taxonomy_info_list[$service_taxonomy_recordid] = $service_taxonomy_name;    
                }
            }
        }

        $locations = $locations->get();

        //======================updated alt taxonomy tree======================

        $grandparent_taxonomies = Alt_taxonomy::all();
        
        $taxonomy_tree = [];
        if (count($grandparent_taxonomies) > 0)
        {
            foreach ($grandparent_taxonomies as $key => $grandparent) {
                $taxonomy_data['alt_taxonomy_name'] = $grandparent->alt_taxonomy_name;
                $terms = $grandparent->terms()->get();
                $taxonomy_parent_name_list = [];
                foreach ($terms as $term_key => $term) {
                    array_push($taxonomy_parent_name_list, $term->taxonomy_parent_name);
                }

                $taxonomy_parent_name_list = array_unique($taxonomy_parent_name_list);

                $parent_taxonomy = [];
                $grandparent_service_count = 0;
                foreach ($taxonomy_parent_name_list as $term_key => $taxonomy_parent_name) {
                    $parent_count = Taxonomy::where('taxonomy_parent_name', '=', $taxonomy_parent_name)->count();
                    $term_count = $grandparent->terms()->where('taxonomy_parent_name', '=', $taxonomy_parent_name)->count();
                    if ($parent_count == $term_count) {
                        $child_data['parent_taxonomy'] = $taxonomy_parent_name;
                        $child_taxonomies = Taxonomy::where('taxonomy_parent_name', '=', $taxonomy_parent_name)->get(['taxonomy_name', 'taxonomy_id']);
                        $child_data['child_taxonomies'] = $child_taxonomies;
                        array_push($parent_taxonomy, $child_data);
                    } else {
                        foreach($grandparent->terms()->where('taxonomy_parent_name', '=', $taxonomy_parent_name)->get() as $child_key => $child_term) {
                            $child_data['parent_taxonomy'] = $child_term;
                            $child_data['child_taxonomies'] = "";
                            array_push($parent_taxonomy, $child_data);
                        }
                    }
                }
                $taxonomy_data['parent_taxonomies'] = $parent_taxonomy;
                array_push($taxonomy_tree, $taxonomy_data);
            }
        }
        else {
            $parent_taxonomies = Taxonomy::whereNull('taxonomy_parent_name')->whereNotNull('taxonomy_services')->get();
            // $parent_taxonomy_data = [];
            // foreach($parent_taxonomies as $parent_taxonomy) {
            //     $child_data['parent_taxonomy'] = $parent_taxonomy->taxonomy_name;
            //     $child_data['child_taxonomies'] = $parent_taxonomy->childs;
            //     array_push($parent_taxonomy_data, $child_data);
            // }
            $taxonomy_tree['parent_taxonomies'] = $parent_taxonomies;
        }

        return view('frontEnd.services', compact('services', 'locations', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'meta_status', 'grandparent_taxonomies', 'sort_by_distance_clickable', 'service_taxonomy_info_list'))->with('taxonomy_tree', $taxonomy_tree);  
    }

    public function service($id)
    {
        $service = Service::where('service_recordid', '=', $id)->first();

        $service_phones_info = $service->service_phones;
        if(strpos($service_phones_info, ',') !== false){
            $service_phone_recordid_list = explode(',', $service_phones_info);
            $phone1_recordid = $service_phone_recordid_list[0];
            $phone2_recordid = $service_phone_recordid_list[1];
        } else{
            $phone1_recordid = $service_phones_info;
            $phone2_recordid = NULL;
        }
        $phone_number_info = '';
        if ($phone1_recordid) {
            $phone1_number = Phone::where('phone_recordid', '=', $phone1_recordid)->select('phone_number')->first();            
            $phone_number_info = $phone1_number->phone_number;
        } 
        if ($phone2_recordid) {
            $phone2_number = Phone::where('phone_recordid', '=', $phone2_recordid)->select('phone_number')->first();            
            if ($phone_number_info) {
                $phone_number_info = $phone_number_info.', '.$phone2_number->phone_number;
            } else {
                $phone_number_info = $phone2_number->phone_number;
            }
        } 


        $service_taxonomy_recordid_list = explode(',', $service->service_taxonomy);
        $service_taxonomy_info_list = [];
        foreach ($service_taxonomy_recordid_list as $key => $service_taxonomy_recordid) {
            $service_taxonomy_info = (object)[];
            $service_taxonomy_info->taxonomy_recordid = $service_taxonomy_recordid;
            
            $taxonomy = Taxonomy::where('taxonomy_recordid', '=', (int)($service_taxonomy_recordid))->first();
            if(isset($taxonomy)){
                $service_taxonomy_name = $taxonomy->taxonomy_name;
                $service_taxonomy_info->taxonomy_name = $service_taxonomy_name;    
            }
            
            array_push($service_taxonomy_info_list, $service_taxonomy_info);
        }

        $location = Location::with('organization', 'address')->where('location_services', 'like', '%'.$id.'%')->get();

        $contact_info = Contact::where('contact_recordid', '=', $service->service_contacts)->first();        
        $contact_phone = NULL;
        if ($contact_info) {
            $contact_phone = Phone::where('phone_recordid', '=', $contact_info->contact_phones)->first(); 
        }

        $map = Map::find(1);
        $parent_taxonomy = [];
        $child_taxonomy = [];
        $checked_organizations = [];
        $checked_insurances = [];
        $checked_ages = [];
        $checked_languages = [];
        $checked_settings = [];
        $checked_culturals = [];
        $checked_transportations = [];
        $checked_hours= [];

        //======================updated alt taxonomy tree======================

        $grandparent_taxonomies = Alt_taxonomy::all();
        
        $taxonomy_tree = [];
        if (count($grandparent_taxonomies) > 0)
        {
            foreach ($grandparent_taxonomies as $key => $grandparent) {
                $taxonomy_data['alt_taxonomy_name'] = $grandparent->alt_taxonomy_name;
                $terms = $grandparent->terms()->get();
                $taxonomy_parent_name_list = [];
                foreach ($terms as $term_key => $term) {
                    array_push($taxonomy_parent_name_list, $term->taxonomy_parent_name);
                }

                $taxonomy_parent_name_list = array_unique($taxonomy_parent_name_list);

                $parent_taxonomy = [];
                $grandparent_service_count = 0;
                foreach ($taxonomy_parent_name_list as $term_key => $taxonomy_parent_name) {
                    $parent_count = Taxonomy::where('taxonomy_parent_name', '=', $taxonomy_parent_name)->count();
                    $term_count = $grandparent->terms()->where('taxonomy_parent_name', '=', $taxonomy_parent_name)->count();
                    if ($parent_count == $term_count) {
                        $child_data['parent_taxonomy'] = $taxonomy_parent_name;
                        $child_taxonomies = Taxonomy::where('taxonomy_parent_name', '=', $taxonomy_parent_name)->get(['taxonomy_name', 'taxonomy_id']);
                        $child_data['child_taxonomies'] = $child_taxonomies;
                        array_push($parent_taxonomy, $child_data);
                    } else {
                        foreach($grandparent->terms()->where('taxonomy_parent_name', '=', $taxonomy_parent_name)->get() as $child_key => $child_term) {
                            $child_data['parent_taxonomy'] = $child_term;
                            $child_data['child_taxonomies'] = "";
                            array_push($parent_taxonomy, $child_data);
                        }
                    }
                }
                $taxonomy_data['parent_taxonomies'] = $parent_taxonomy;
                array_push($taxonomy_tree, $taxonomy_data);
            }
        }
        else {
            $parent_taxonomies = Taxonomy::whereNull('taxonomy_parent_name')->whereNotNull('taxonomy_services')->get();
            // $parent_taxonomy_data = [];
            // foreach($parent_taxonomies as $parent_taxonomy) {
            //     $child_data['parent_taxonomy'] = $parent_taxonomy->taxonomy_name;
            //     $child_data['child_taxonomies'] = $parent_taxonomy->childs;
            //     array_push($parent_taxonomy_data, $child_data);
            // }
            $taxonomy_tree['parent_taxonomies'] = $parent_taxonomies;
        }

        return view('frontEnd.service', compact('service', 'location', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'taxonomy_tree', 'service_taxonomy_info_list', 'contact_info', 'contact_phone', 'phone_number_info'));
    }

    public function taxonomy($id)
    {
        $chip_name = Taxonomy::where('taxonomy_recordid', '=', $id)->first()->taxonomy_name;
        $chip_title = 'Category:';

        $serviceids = Servicetaxonomy::where('taxonomy_recordid', '=', $id)->pluck('service_recordid')->toArray();
        $services = Service::whereIn('service_recordid', $serviceids)->orderBy('service_name')->paginate(10);

        $locationids = Servicelocation::whereIn('service_recordid', $serviceids)->pluck('location_recordid')->toArray();

        $locations = Location::whereIn('location_recordid', $locationids)->with('services','organization')->get();

        $map = Map::find(1);

        $parent_taxonomy = [];
        $child_taxonomy = [];
        $checked_organizations = [];
        $checked_insurances = [];
        $checked_ages = [];
        $checked_languages = [];
        $checked_settings = [];
        $checked_culturals = [];
        $checked_transportations = [];
        $checked_hours= [];

        return view('frontEnd.services', compact('services', 'locations', 'chip_title', 'chip_name', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $service = Service::where('service_recordid', '=', $id)->first();
        $service_name = $service->service_name;
        

        $layout = Layout::find(1);

        $pdf = PDF::loadView('frontEnd.service_download', compact('service', 'layout'));
        $service_name = str_replace('"','',$service_name);

        return $pdf->download($service_name.'111.pdf');

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
        $service = Service::find($id);
        return response()->json($service);
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
        $service = Service::where('service_recordid', '=', $id)->first(); 
        $service_organization_list = Organization::select('organization_recordid', 'organization_name')->get();  
        $service_location_list = Location::select('location_recordid', 'location_name')->get();
        $service_contacts_list = Contact::select('contact_recordid', 'contact_name')->get();
        $service_taxonomy_list = Taxonomy::select('taxonomy_recordid', 'taxonomy_name')->get();
        $service_details_list = Detail::select('detail_recordid', 'detail_value')->get();

        $service_address_id = $service->service_address;
        $service_address_street = Address::where('address_recordid', '=', $service_address_id)->select('address_1')->first();
        $service_address_city = Address::where('address_recordid', '=', $service_address_id)->select('address_city')->first();
        $service_address_state = Address::where('address_recordid', '=', $service_address_id)->select('address_state_province')->first();
        $service_address_postal_code = Address::where('address_recordid', '=', $service_address_id)->select('address_postal_code')->first();

        $phone_recordids = $service->service_phones;
        
        if(strpos($phone_recordids, ',') !== false){
            $phone_recordid_list = explode(',', $phone_recordids);
            $phone1_recordid = $phone_recordid_list[0];
            $phone2_recordid = $phone_recordid_list[1];
        } else{
            $phone1_recordid = $phone_recordids;
            $phone2_recordid = '';
        } 
        $service_phone1 = Phone::where('phone_recordid', '=', $phone1_recordid)->select('phone_number')->first();
        $service_phone2 = Phone::where('phone_recordid', '=', $phone2_recordid)->select('phone_number')->first();
       

        return view('frontEnd.service-edit', compact('service', 'map', 'service_address_street', 'service_address_city', 'service_address_state', 'service_address_postal_code', 'service_organization_list', 'service_location_list', 'service_phone1', 'service_phone2', 'service_contacts_list', 'service_taxonomy_list', 'service_details_list'));
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
        $service = Service::find($id);
        $service->service_name = $request->service_name;
        $service->service_alternate_name = $request->service_alternate_name;
        $service->service_description = $request->service_description;
        $service->service_url = $request->service_url;
        $service->service_email = $request->service_email;
        $service->service_status = $request->service_status;
        $service->service_application_process = $request->service_application_process;
        $service->service_wait_time = $request->service_wait_time;
        $service->service_fees = $request->service_fees;
        $service->service_accreditations = $request->service_accreditations;
        $service->service_licenses = $request->service_licenses;
        $service->service_organization = $request->service_organization;
        $service->service_locations = $request->service_locations;
        $service->service_contacts = $request->service_contacts;
        $service->service_taxonomy = $request->service_taxonomy;
        $service->service_details = $request->service_details;
        $service_phone1 = $request->service_phone1;
        $service_phone2 = $request->service_phone2;

        $phone2_info = NULL;
        $phone1_info = NULL;
        if ($service_phone1) {
            $phone1_info = Phone::where('phone_number', '=', $service_phone1)->select('phone_recordid')->first();
        }
        if ($service_phone2) {
            $phone2_info = Phone::where('phone_number', '=', $service_phone2)->select('phone_recordid')->first();
        }
   
        if ($phone2_info) {
            if ($phone1_info) {
                $service->service_phones = $phone1_info->phone_recordid.','.$phone2_info->phone_recordid;
            } else {
                $service->service_phones = $phone2_info->phone_recordid;
            }
        } else {
            $service->service_phones = $phone1_info->phone_recordid;
        }

        $service_address_info = $request->service_address;
        $address_infos = Address::select('address_recordid', 'address_1', 'address_city', 'address_state_province', 'address_postal_code')->distinct()->get();

        $full_address_info_list = array();
        foreach ($address_infos as $key => $value) {
            $full_address_info = $value->address_1.', '.$value->address_city.', '.$value->address_state_province.', '.$value->address_postal_code;
            array_push($full_address_info_list, $full_address_info);
        }
        $full_address_info_list = array_unique($full_address_info_list); 
        if ($service_address_info) {
            if (!in_array($service_address_info, $full_address_info_list)) {
                $new_recordid = Address::max('address_recordid') + 1;  
                $service->service_address = $new_recordid;
                $address = new Address();
                $address->address_recordid = $new_recordid;
                $address->address_1 = explode(', ', $service_address_info)[0];
                $address->address_city = explode(', ', $service_address_info)[1];
                $address->address_state_province = explode(', ', $service_address_info)[2];
                $address->address_postal_code = explode(', ', $service_address_info)[3];
                $address->save();
            } else {
                foreach ($address_infos as $key => $value) {
                    $full_address_info = $value->address_1.', '.$value->address_city.', '.$value->address_state_province.', '.$value->address_postal_code;
                    if ($full_address_info == $service_address_info) {
                        $service->service_address = $value->address_recordid;
                    }
                }
            }
        } else {
            $service->service_address = $service_address_info;
        }


        $service->save();

        return redirect('service/'.$id);
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
