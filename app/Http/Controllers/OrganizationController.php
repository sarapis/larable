<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Functions\Airtable;
use App\Organization;
use App\Organizationdetail;
use App\Taxonomy;
use App\Alt_taxonomy;
use App\Servicetaxonomy;
use App\Service;
use App\Contact;
use App\Comment;
use App\Session;
use App\Phone;
use App\Location;
use App\Airtablekeyinfo;
use App\Layout;
use App\Map;
use App\Airtables;
use App\CSV_Source;
use App\Source_data;
use App\Services\Stringtoint;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Sentinel;


class OrganizationController extends Controller
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

        Organization::truncate();
        Organizationdetail::truncate();

        // $airtable = new Airtable(array(
        //     'api_key'   => env('AIRTABLE_API_KEY'),
        //     'base'      => env('AIRTABLE_BASE_URL'),
        // ));
        $airtable = new Airtable(array(
            'api_key'   => $api_key,
            'base'      => $base_url,
        ));

        $request = $airtable->getContent( 'organizations' );

        do {


            $response = $request->getResponse();

            $airtable_response = json_decode( $response, TRUE );

            foreach ( $airtable_response['records'] as $record ) {

                $organization = new Organization();
                $strtointclass = new Stringtoint();
                $organization->organization_recordid= $strtointclass->string_to_int($record[ 'id' ]);
                $organization->organization_name = isset($record['fields']['name'])?$record['fields']['name']:null;
                if(isset($record['fields']['logo-x'])){
                    foreach ($record['fields']['logo-x'] as $key => $image) {
                        try {
                            $organization->organization_logo_x .= $image["url"];
                        } catch (Exception $e) {
                            echo 'Caught exception: ',  $e->getMessage(), "\n";
                        }
                    }
                }
                if(isset($record['fields']['forms-x'])){
                    foreach ($record['fields']['forms-x'] as $key => $form) {
                        try {
                            $organization->organization_forms_x_filename .= $form["filename"];
                            $organization->organization_forms_x_url .= $form["url"];
                        } catch (Exception $e) {
                            echo 'Caught exception: ',  $e->getMessage(), "\n";
                        }
                    }
                }
                $organization->organization_alternate_name = isset($record['fields']['alternate_name'])?$record['fields']['alternate_name']:null;
                $organization->organization_x_uid = isset($record['fields']['x-uid'])?$record['fields']['x-uid']:null;
                $organization->organization_description = isset($record['fields']['description'])?$record['fields']['description']:null;

                $organization->organization_description =  mb_convert_encoding($organization->organization_description, "HTML-ENTITIES", "UTF-8");

                $organization->organization_email = isset($record['fields']['email'])?$record['fields']['email']:null;
                $organization->organization_url = isset($record['fields']['url'])?$record['fields']['url']:null;
                $organization->organization_status_x = isset($record['fields']['status-x'])?$record['fields']['status-x']:null;
                if($organization->organization_status_x == 'Vetted')
                    $organization->organization_status_sort = 1;
                if($organization->organization_status_x == 'Vetting In Progress')
                    $organization->organization_status_sort = 2;
                if($organization->organization_status_x == 'Not vetted')
                    $organization->organization_status_sort = 3;
                if($organization->organization_status_x == null)
                    $organization->organization_status_sort = 4;
                $organization->organization_legal_status = isset($record['fields']['legal_status'])?$record['fields']['legal_status']:null;
                $organization->organization_tax_status = isset($record['fields']['tax_status'])?$record['fields']['tax_status']:null;
                $organization->organization_legal_status = isset($record['fields']['legal_status'])?$record['fields']['legal_status']:null;
                $organization->organization_tax_status = isset($record['fields']['tax_status'])?$record['fields']['tax_status']:null;
                $organization->organization_tax_id = isset($record['fields']['tax_id'])?$record['fields']['tax_id']:null;
                $organization->organization_year_incorporated = isset($record['fields']['year_incorporated'])?$record['fields']['year_incorporated']:null;

                if(isset($record['fields']['services'])){
                    $i = 0;
                    foreach ($record['fields']['services']  as  $value) {

                        $organizationservice=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $organization->organization_services = $organization->organization_services. ','. $organizationservice;
                        else
                            $organization->organization_services = $organizationservice;
                        $i ++;
                    }
                }

                if(isset($record['fields']['phones'])){
                    $i = 0;
                    foreach ($record['fields']['phones']  as  $value) {

                        $organizationphone=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $organization->organization_phones = $organization->organization_phones. ','. $organizationphone;
                        else
                            $organization->organization_phones = $organizationphone;
                        $i ++;
                    }
                }
                

                if(isset($record['fields']['locations'])){
                    $i = 0;
                    foreach ($record['fields']['locations']  as  $value) {

                        $organizationlocation=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $organization->organization_locations = $organization->organization_locations. ','. $organizationlocation;
                        else
                            $organization->organization_locations = $organizationlocation;
                        $i ++;
                    }
                }
                $organization->organization_contact = isset($record['fields']['contact']) ?implode(",", $record['fields']['contact']):null;
                $organization->organization_contact = $strtointclass->string_to_int($organization->organization_contact);

                if(isset($record['fields']['details'])){
                    $i = 0;
                    foreach ($record['fields']['details']  as  $value) {
                        $organization_detail = new Organizationdetail();
                        $organization_detail->organization_recordid=$organization->organization_recordid;
                        $organization_detail->detail_recordid=$strtointclass->string_to_int($value);
                        $organization_detail->save();
                        $organizationdetail=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $organization->organization_details = $organization->organization_details. ','. $organizationdetail;
                        else
                            $organization->organization_details = $organizationdetail;
                        $i ++;
                    }
                }

                if(isset($record['fields']['AIRS Taxonomy-x'])){
                    $i = 0;
                    foreach ($record['fields']['AIRS Taxonomy-x']  as  $value) {

                        if($i != 0)
                            $organization->organization_airs_taxonomy_x = $organization->organization_airs_taxonomy_x . ','. $value;
                        else
                            $organization->organization_airs_taxonomy_x  = $value;
                        $i ++;
                    }
                }    

                $organization ->save();

            }
            
        }
        while( $request = $response->next() );

        $date = date("Y/m/d H:i:s");
        $airtable = Airtables::where('name', '=', 'Organizations')->first();
        $airtable->records = Organization::count();
        $airtable->syncdate = $date;
        $airtable->save();
    }

    public function csv(Request $request)
    {


        $path = $request->file('csv_file')->getRealPath();

        $data = Excel::load($path)->get();

        $filename =  $request->file('csv_file')->getClientOriginalName();
        $request->file('csv_file')->move(public_path('/csv/'), $filename);

        if ($filename!='organizations.csv') 
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

        Organization::truncate();
        Organizationdetail::truncate();

        foreach ($csv_data as $row) {
            
            $organization = new Organization();

            $organization->organization_recordid = $row['id'];
            $organization->organization_name = $row['name']!='NULL'?$row['name']:null;

            $organization->organization_alternate_name = $row['alternate_name']!='NULL'?$row['alternate_name']:null;

            $organization->organization_description = $row['description']!='NULL'?$row['description']:null;
            $organization->organization_url = $row['url']!='NULL'?$row['url']:null;
            $organization->organization_email = $row['email']!='NULL'?$row['email']:null;
            $organization->organization_tax_status = $row['tax_status']!='NULL'?$row['tax_status']:null;
            $organization->organization_tax_id = $row['tax_id']!='NULL'?$row['tax_id']:null;
            $organization->organization_year_incorporated = $row['year_incorporated']!='NULL'?$row['year_incorporated']:null;
            $organization->organization_legal_status = $row['legal_status']!='NULL'?$row['legal_status']:null;
                                     
            $organization->save();

           
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Organizations')->first();
        $csv_source->records = Organization::count();
        $csv_source->syncdate = $date;
        $csv_source->save();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizations = Organization::orderBy('organization_recordid')->paginate(20);
        $source_data = Source_data::find(1);

        return view('backEnd.tables.tb_organization', compact('organizations', 'source_data'));
    }

    public function organizations()
    {
        $organizations = Organization::orderBy('organization_name')->paginate(20);
        $organization_tag_list = Organization::whereNotNull('organization_tag')->select('organization_tag')->pluck('organization_tag')->toArray();

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

        //=====================updated tree==========================//

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

        return view('frontEnd.organizations', compact('organizations', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'taxonomy_tree', 'organization_tag_list'));
    }

    public function organization($id)
    {
        $organization = Organization::where('organization_recordid', '=', $id)->first();
        $locations = Location::with('services', 'address', 'phones')->where('location_organization', '=', $id)->get();       


        $organization_services = $organization->services()->orderBy('service_name')->paginate(10);

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

        $organization_contacts_recordid_list = explode(',', $organization->organization_contact);
        $contact_info_list = Contact::whereIn('contact_recordid', $organization_contacts_recordid_list)->get(); 

        $organization_locations_recordid_list = explode(',', $organization->organization_locations);
        $location_info_list = Location::whereIn('location_recordid', $organization_locations_recordid_list)->orderBy('location_recordid')->paginate(10);

        //=====================updated tree==========================//

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

        $existing_tag_element_list = Organization::whereNotNull('organization_tag')->get()->pluck('organization_tag');
        $existing_tags = [];
        foreach ($existing_tag_element_list as $key => $existing_tag_element) {
            $existing_tag_list = explode(", ", $existing_tag_element);
            foreach ($existing_tag_list as $key => $existing_tag) {
                if (!in_array($existing_tag, $existing_tags, true)) {
                    array_push($existing_tags, $existing_tag);
                }
            }
        }

        $comment_list = Comment::where('comments_organization', '=', $id)->get();
        $session_list = Session::where('session_organization', '=', $id)->select('session_recordid', 'session_edits', 'session_performed_at', 'session_verification_status')->get();

        return view('frontEnd.organization', compact('organization', 'locations', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'taxonomy_tree', 'contact_info_list', 'organization_services', 'location_info_list', 'existing_tags', 'comment_list', 'session_list'));
    }

    public function tagging(Request $request, $id)
    {
        $organization = Organization::find($id);
        $organization->organization_tag = $request->tokenfield;
        $organization->updated_at = date("Y-m-d H:i:s");
        $organization->save();
        return redirect('organization/' . $id);
    }

    public function download($id)
    {
        $organization = Organization::where('organization_recordid', '=', $id)->first();
        $organization_name = $organization->organization_name;

        $layout = Layout::find(1);

        $pdf = PDF::loadView('frontEnd.organization_download', compact('organization', 'layout'));

        return $pdf->download($organization_name.'.pdf');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $map = Map::find(1);
        $services_info_list = Service::select('service_recordid', 'service_name')->get();
        $organization_contacts_list = Contact::select('contact_recordid', 'contact_name')->get();
        $rating_info_list = ['1', '2', '3', '4', '5'];
        return view('frontEnd.organization-create', compact('map', 'services_info_list', 'organization_contacts_list', 'rating_info_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $organization = new Organization;

        $new_recordid = Organization::max("organization_recordid") + 1;
        $organization->organization_recordid = $new_recordid;

        $organization->organization_name = $request->organization_name;
        $organization->organization_alternate_name = $request->organization_alternate_name;
        $organization->organization_description = $request->organization_description;
        $organization->organization_email = $request->organization_email;
        $organization->organization_url = $request->organization_url;
        $organization->organization_legal_status = $request->organization_legal_status;
        $organization->organization_tax_status = $request->organization_tax_status;
        $organization->organization_tax_id = $request->organization_tax_id;

        if ($request->organization_year_incorporated) {
            $organization->organization_year_incorporated = join(',', $request->organization_year_incorporated);
        } else {
            $organization->organization_year_incorporated = '';
        }

        if ($request->organization_services) {
            $organization->organization_services = join(',', $request->organization_services);
        } else {
            $organization->organization_services = '';
        }

        if ($request->organization_contacts) {
            $contact_recordid_list = $request->organization_contacts;
            foreach ($contact_recordid_list as $key => $value) {
                $updating_contact = Contact::where('contact_recordid', '=', $value)->first();
                $updating_contact->contact_organizations = $new_recordid;
                $updating_contact->save();
            }
        }
        
        if ($request->organization_phones) {
            $phone_recordids = [];
            foreach ($request->organization_phones as $key => $number) {
                $phone = Phone::where('phone_number', $number);
                if ($phone->count() > 0) {
                    $phone_record_id = $phone->first()->phone_recordid;
                    array_push($phone_recordids, $phone_record_id);
                } else {
                    $new_phone = new Phone;
                    $new_phone_recordid = Phone::max('phone_recordid') + 1;
                    $new_phone->phone_recordid = $new_phone_recordid;
                    $new_phone->phone_number = $number;
                    $new_phone->save();
                    array_push($phone_recordids, $new_phone_recordid);
                }
            }
            $organization->phones()->sync($phone_recordids);
        }

        if ($request->organization_locations) {
            $organization->organization_locations = join(',', $request->organization_locations);
        } else {
            $organization->organization_locations = '';
        }

        $organization->updated_at = date("Y-m-d H:i:s");
       
        $organization->save();

        return redirect('organizations');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $organization= Organization::find($id);
        return response()->json($organization);
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
        $organization = Organization::where('organization_recordid', '=', $id)->first(); 
        $organization_service_list = explode(',', $organization->organization_services);
        $services_info_list = Service::select('service_recordid', 'service_name')->get();

        $organization_contacts_list = Contact::select('contact_recordid', 'contact_name')->get();
        $organization_contact_info_list = Contact::where('contact_organizations', '=', $id)->select('contact_recordid')->get();

        $contact_info_list = [];
        foreach ($organization_contact_info_list as $key => $value) {
            array_push($contact_info_list, $value->contact_recordid);
        }

        $organization_phones_list = Phone::select('phone_recordid', 'phone_number')->get();
        $phone_info_list = explode(',', $organization->organization_phones);
        $organization_locations_list = Location::select('location_recordid', 'location_name')->get();
        $location_info_list = explode(',', $organization->organization_locations);
        $organization_services_recordid_list = explode(',', $organization->organization_services);
        $organization_services = Service::whereIn('service_recordid', $organization_services_recordid_list)->orderBy('service_name')->paginate(10);

        $rating_info_list = ['1', '2', '3', '4', '5'];


        return view('frontEnd.organization-edit', compact('organization', 'map', 'services_info_list', 'organization_service_list', 'organization_contacts_list', 'contact_info_list', 'organization_phones_list', 'phone_info_list', 'organization_locations_list', 'location_info_list', 'organization_services', 'rating_info_list'));
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
        $organization = Organization::find($id);
        $organization->organization_name = $request->organization_name;
        $organization->organization_alternate_name = $request->organization_alternate_name;
        $organization->organization_description = $request->organization_description;
        $organization->organization_email = $request->organization_email;
        $organization->organization_url = $request->organization_url;
        $organization->organization_legal_status = $request->organization_legal_status;
        $organization->organization_tax_status = $request->organization_tax_status;
        $organization->organization_tax_id = $request->organization_tax_id;
        $organization->organization_website_rating = $request->organization_rating;

        if ($request->organization_year_incorporated) {
            $organization->organization_year_incorporated = join(',', $request->organization_year_incorporated);
        } else {
            $organization->organization_year_incorporated = '';
        }

        if ($request->organization_services) {
            $organization->organization_services = join(',', $request->organization_services);
        } else {
            $organization->organization_services = '';
        }

        if ($request->organization_contacts) {
            $contact_recordid_list = $request->organization_contacts;
            foreach ($contact_recordid_list as $key => $value) {
                $updating_contact = Contact::where('contact_recordid', '=', $value)->first();
                $updating_contact->contact_organizations = $id;
                $updating_contact->save();
            }
        }
        
        // if ($request->organization_phones) {
        //     $phone_recordids = [];
        //     foreach ($request->organization_phones as $key => $number) {
        //         $phone = Phone::where('phone_number', $number);
        //         if ($phone->count() > 0) {
        //             $phone_record_id = $phone->first()->phone_recordid;
        //             array_push($phone_recordids, $phone_record_id);
        //         } else {
        //             $new_phone = new Phone;
        //             $new_phone_recordid = Phone::max('phone_recordid') + 1;
        //             $new_phone->phone_recordid = $new_phone_recordid;
        //             $new_phone->phone_number = $number;
        //             $new_phone->save();
        //             array_push($phone_recordids, $new_phone_recordid);
        //         }
        //     }
        //     $organization->phones()->sync($phone_recordids);
        // }

        if ($request->organization_phones) {
            $phone_recordids = [];
            $number = $request->organization_phones;
            $phone = Phone::where('phone_number', $number);
            if ($phone->count() > 0) {
                $phone_record_id = $phone->first()->phone_recordid;
                array_push($phone_recordids, $phone_record_id);
            } else {
                $new_phone = new Phone;
                $new_phone_recordid = Phone::max('phone_recordid') + 1;
                $new_phone->phone_recordid = $new_phone_recordid;
                $new_phone->phone_number = $number;
                $new_phone->save();
                array_push($phone_recordids, $new_phone_recordid);
            }
            
            $organization->phones()->sync($phone_recordids);
        }

        if ($request->organization_locations) {
            $organization->organization_locations = join(',', $request->organization_locations);
        } else {
            $organization->organization_locations = '';
        }

        $organization->updated_at = date("Y-m-d H:i:s");
       
        $organization->save();

        return redirect('organization/'.$id);
    }

    public function add_comment(Request $request, $id)
    {

        $organization = Organization::find($id);
        $comment_content = $request->reply_content;
        $user = Sentinel::getUser();
        $date_time = date("Y-m-d H:i:s");
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
        $comment->comments_organization = $id;
        $comment->comments_datetime = $date_time;
        $comment->save();

        $comment_list = Comment::where('comments_organization', '=', $id)->get();

        return redirect('organization/' . $id);

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

    public function delete_organization(Request $request)
    {
        $organization_recordid = $request->input('organization_recordid');
        $organization = Organization::where('organization_recordid', '=', $organization_recordid)->first();
        if ($organization != null) {
            $organization->delete();
        }
        return redirect('organizations');
    }


}
