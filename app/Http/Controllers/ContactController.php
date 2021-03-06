<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Functions\Airtable;
use App\Contact;
use App\Organization;
use App\Location;
use App\Address;
use App\Phone;
use App\Service;
use App\Map;
use App\Airtablekeyinfo;
use App\Servicecontact;
use App\Airtables;
use App\CSV_Source;
use App\Source_data;
use App\Services\Stringtoint;
use App\Servicelocation;
use Maatwebsite\Excel\Facades\Excel;

class ContactController extends Controller
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

        Contact::truncate();
        // $airtable = new Airtable(array(
        //     'api_key'   => env('AIRTABLE_API_KEY'),
        //     'base'      => env('AIRTABLE_BASE_URL'),
        // ));
        $airtable = new Airtable(array(
            'api_key'   => $api_key,
            'base'      => $base_url,
        ));

        $request = $airtable->getContent( 'contact' );

        do {


            $response = $request->getResponse();

            $airtable_response = json_decode( $response, TRUE );

            foreach ( $airtable_response['records'] as $record ) {

                $contact = new Contact();
                $strtointclass = new Stringtoint();

                $contact->contact_recordid= $strtointclass->string_to_int($record[ 'id' ]);

                $contact->contact_name = isset($record['fields']['name'])?$record['fields']['name']:null;
                $contact->contact_organizations = isset($record['fields']['organizations'])? implode(",", $record['fields']['organizations']):null;

                $contact->contact_organizations = $strtointclass->string_to_int($contact->contact_organizations);

                $contact->contact_services = isset($record['fields']['services'])? implode(",", $record['fields']['services']):null;

                $contact->contact_services = $strtointclass->string_to_int($contact->contact_services);

                $contact->contact_title = isset($record['fields']['title'])?$record['fields']['title']:null;
                $contact->contact_department = isset($record['fields']['department'])?$record['fields']['department']:null;
                $contact->contact_email = isset($record['fields']['email'])?$record['fields']['email']:null;
                $contact->contact_phones = isset($record['fields']['phones'])? implode(",", $record['fields']['phones']):null;

                $contact->contact_phones = $strtointclass->string_to_int($contact->contact_phones);

                $contact ->save();

            }
            
        }
        while( $request = $response->next() );

        $date = date("Y/m/d H:i:s");
        $airtable = Airtables::where('name', '=', 'Contact')->first();
        $airtable->records = Contact::count();
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

        if ($filename!='contacts.csv') 
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

        Contact::truncate();
        Servicecontact::truncate();


        foreach ($csv_data as $row) {

            $contact = new Contact();

            $contact->contact_recordid= $row['id'];
            $contact->contact_services = $row['service_id']!='NULL'?$row['service_id']:null;

            if($row['service_id']){

                $service_contact = new Servicecontact();
                $service_contact->service_recordid=$row['service_id']!='NULL'?$row['service_id']:null;
                $service_contact->contact_recordid=$row['id'];
                $service_contact->save();

            }


            $contact->contact_email = $row['email']!='NULL'?$row['email']:null;
            $contact->contact_name = $row['name']!='NULL'?$row['name']:null;
            $contact->contact_phones = $row['phone_number']!='NULL'?$row['phone_number']:null;
            $contact->contact_phone_areacode = $row['phone_areacode']!='NULL'?$row['phone_areacode']:null;
            $contact->contact_phone_extension = $row['phone_extension']!='NULL'?$row['phone_extension']:null;
            $contact->contact_title = $row['title']!='NULL'?$row['title']:null;
            $contact->contact_organizations = $row['organization_id']!='NULL'?$row['organization_id']:null;
            $contact->contact_department = $row['department']!='NULL'?$row['department']:null;          
                                     
            $contact ->save();

           
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Contacts')->first();
        $csv_source->records = Contact::count();
        $csv_source->syncdate = $date;
        $csv_source->save();
    }


    public function index()
    {
        $contacts = Contact::orderBy('contact_recordid')->paginate(20);
        $source_data = Source_data::find(1);

        return view('backEnd.tables.tb_contacts', compact('contacts', 'source_data'));
    }

    public function get_all_contacts(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $search_term = $request->search_term;

        $contacts = Contact::orderBy('contact_recordid', 'DESC');
        if ($search_term) {
            $contacts = $contacts
                ->where('contact_name', 'LIKE', '%' . $search_term . '%')
                ->orWhere('contact_title', 'LIKE', '%' . $search_term . '%')
                ->orWhere('contact_department', 'LIKE', '%' . $search_term . '%')
                ->orWhere('contact_email', 'LIKE', '%' . $search_term . '%');
        }
     
        $filtered_count = $contacts->count();
        $contacts = $contacts->offset($start)->limit($length)->get();
        $total_count = Contact::count();
        $result = [];
        $contact_info = [];
        
        foreach ($contacts as $contact) {
            $contact_info[0] = '';
            $contact_info[1] = $contact->contact_recordid;
            $contact_info[2] = $contact->contact_name;
            $contact_info[3] = $contact->contact_title;
            $contact_info[4] = $contact->contact_department;
            $contact_info[5] = $contact->contact_email;
            $contact_info[6] = '';
            if ($contact->organization) {
                $contact_info[7] = $contact->organization['organization_recordid'];
                $contact_info[8] = $contact->organization['organization_name'] != null ? $contact->organization['organization_name'] : '';
            } else {
                $contact_info[7] = '';
                $contact_info[8] = '';
            }
            
            array_push($result, $contact_info);
        }
        return response()->json(array('data' => $result, 'recordsTotal' => $total_count, 'recordsFiltered' => $filtered_count));
    }


    public function contacts(Request $request)
    {
        $map = Map::find(1);
        $contacts = Contact::orderBy('contact_recordid', 'map')->paginate(20);
        $locations = Location::with('services', 'address', 'phones')->distinct()->get();

        return view('frontEnd.contacts', compact('contacts', 'map', 'locations'));
    }

    public function contact($id)
    {
        $contact = Contact::where('contact_recordid', '=', $id)->first();  
        $phone_recordid = $contact->contact_phones;
        $phone_info = Phone::where('phone_recordid', '=', $phone_recordid)->select('phone_number')->first();
        if ($phone_info) {
            $phone_number = $phone_info->phone_number;
        } else {
            $phone_number = '';
        }
        $map = Map::find(1);

        return view('frontEnd.contact', compact('contact', 'map', 'phone_number'));
    }


    public function contactData(Request $request)
    {
        DB::statement(DB::raw('set @rownum=0'));

        $query = Contact::select('*', DB::raw('@rownum := @rownum + 1 AS rownum'));
        if ($request->has('extraData')) {
            $extraData = $request->get('extraData');
            $query = $this->getDataManual($extraData);
        }
        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                $links = '<input type="checkbox" class="contactCheckbox"  name="contactCheckbox"  value= "' . $row->contact_recordid . '"/> ';
                $links .= '<a class="open-td" href="/contacts/' . $row->contact_recordid . '" style="color: #007bff;"><i class="fa fa-fw fa-eye"></i></a>&nbsp';
                return $links;
            })
            ->editColumn('contact_name', function ($row) {
                return $row->contact_name ? $row->contact_name : '';
            })
            ->editColumn('contact_organizations', function ($row) {

                $organization = Organization::where('organization_recordid', 'LIKE', '%' . intval($row->contact_organizations) . '%')->first();
                $organizationName = $organization ? $organization->organization_name : '';
                $organizationRecordId = $organization ? $organization->organization_recordid : '';

                $links = '<a href="/organization/' . $organizationRecordId . '" style="color: #007bff;">' . $organizationName . '</a>';
                return $links;
            })
         
            ->editColumn('contact_title', function ($row) {
                return $row->contact_title ? $row->contact_title : '';
            })
            ->editColumn('contact_department', function ($row) {
                return $row->contact_department ? $row->contact_department : '';
            })
            ->editColumn('contact_email', function ($row) {
                return $row->contact_email ? $row->contact_email : '';
            })
            ->editColumn('contact_phones', function ($row) {
                return $row->contact_phones ? $row->contact_phones : '';
            })
           
            ->rawColumns(['action', 'contact_name', 'contact_organizations', 'contact_title', 'contact_department', 'contact_email', 'contact_phones'])
            ->make(true);
    }

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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

        return view('frontEnd.contact-create', compact('map', 'organization_name_list', 'service_info_list'));
    }

    public function create_in_organization($id)
    {
        $map = Map::find(1);
        $organization = Organization::where('organization_recordid', '=', $id)->select('organization_recordid', 'organization_name')->first();
        $service_info_list = Service::select('service_recordid', 'service_name')->orderBy('service_name')->distinct()->get();
        return view('frontEnd.contact-create-in-organization', compact('map', 'organization', 'service_info_list'));
    }

    public function create_in_facility($id)
    {
        $map = Map::find(1);
        $facility = Location::where('location_recordid', '=', $id)->first();
        $service_info_list = Service::select('service_recordid', 'service_name')->orderBy('service_name')->distinct()->get();
        return view('frontEnd.contact-create-in-facility', compact('map', 'facility', 'service_info_list'));
    }


    public function add_new_contact(Request $request)
    {
        $contact = new Contact;       

        $phone_recordids = Phone::select("phone_recordid")->distinct()->get();
        $phone_recordid_list = array();
        foreach ($phone_recordids as $key => $value) {
            $phone_recordid = $value->phone_recordid;
            array_push($phone_recordid_list, $phone_recordid);
        }
        $phone_recordid_list = array_unique($phone_recordid_list);

        $contact->contact_name = $request->contact_name;
        $contact->contact_title = $request->contact_title;
        $contact->contact_department = $request->contact_department;
        $contact->contact_email = $request->contact_email;
        // $contact->contact_phone_extension = $request->contact_phone_extension;

        $organization_name = $request->contact_organization_name;
        $contact_organization = Organization::where('organization_name', '=', $organization_name)->first();
        $contact_organization_id = $contact_organization["organization_recordid"];
        $contact->contact_organizations = $contact_organization_id;

        $contact_recordids = Contact::select("contact_recordid")->distinct()->get();
        $contact_recordid_list = array();
        foreach ($contact_recordids as $key => $value) {
            $contact_recordid = $value->contact_recordid;
            array_push($contact_recordid_list, $contact_recordid);
        }
        $contact_recordid_list = array_unique($contact_recordid_list);

        $new_recordid = Contact::max('contact_recordid') + 1;
        if (in_array($new_recordid, $contact_recordid_list)) {
            $new_recordid = Contact::max('contact_recordid') + 1;
        }
        $contact->contact_recordid = $new_recordid;

        if ($request->contact_service) {
            $contact->contact_services = join(',', $request->contact_service);
        } else {
            $contact->contact_services = '';
        }
        $contact->service()->sync($request->contact_service);

        // $contact_cell_phones = $request->contact_cell_phones;
        // $cell_phone = Phone::where('phone_number', '=', $contact_cell_phones)->first();
        // if ($cell_phone != null) {
        //     $cell_phone_id = $cell_phone["phone_recordid"];
        //     $contact->contact_phones = $cell_phone_id;
        // } else {
        //     $phone = new Phone;
        //     $new_recordid = Phone::max('phone_recordid') + 1;
        //     if (in_array($new_recordid, $phone_recordid_list)) {
        //         $new_recordid = Phone::max('phone_recordid') + 1;
        //     }
        //     $phone->phone_recordid = $new_recordid;
        //     $phone->phone_number = $contact_cell_phones;
        //     $phone->phone_type = "voice";
        //     $contact->contact_phones = $phone->phone_recordid;
        //     $phone->save();
        // }

        // $contact_phone_info_list = array();
        // array_push($contact_phone_info_list, $contact->contact_phones);
        // $contact_phone_info_list = array_unique($contact_phone_info_list);
        // $contact->phone()->sync($contact_phone_info_list);

        $contact->contact_phones = '';
        $phone_recordid_list = [];
        if ($request->contact_phones) {
            $contact_phone_number_list = $request->contact_phones;
            foreach ($contact_phone_number_list as $key => $contact_phone_number) {
                $phone_info = Phone::where('phone_number', '=', $contact_phone_number)->select('phone_recordid')->first();
                if ($phone_info) {
                    $contact->contact_phones = $contact->contact_phones . $phone_info->phone_recordid . ',';
                    array_push($phone_recordid_list, $phone_info->phone_recordid);
                } else {
                    $new_phone = new Phone;
                    $new_phone_recordid = Phone::max('phone_recordid') + 1;
                    $new_phone->phone_recordid = $new_phone_recordid;
                    $new_phone->phone_number = $contact_phone_number;
                    $new_phone->save();
                    $contact->contact_phones = $contact->contact_phones . $new_phone_recordid . ',';
                    array_push($phone_recordid_list, $new_phone_recordid);
                }
            }
        }
        $contact->phone()->sync($phone_recordid_list);

        $contact->save();

        return redirect('contacts');
    }


    public function add_new_contact_in_facility(Request $request)
    {
        $contact = new Contact;       

        $phone_recordids = Phone::select("phone_recordid")->distinct()->get();
        $phone_recordid_list = array();
        foreach ($phone_recordids as $key => $value) {
            $phone_recordid = $value->phone_recordid;
            array_push($phone_recordid_list, $phone_recordid);
        }
        $phone_recordid_list = array_unique($phone_recordid_list);

        $contact->contact_name = $request->contact_name;
        $contact->contact_title = $request->contact_title;
        $contact->contact_department = $request->contact_department;
        $contact->contact_email = $request->contact_email;
        
        $contact_organization_id = $request->contact_organization;
        $contact->contact_organizations = $contact_organization_id;

        $contact_recordids = Contact::select("contact_recordid")->distinct()->get();
        $contact_recordid_list = array();
        foreach ($contact_recordids as $key => $value) {
            $contact_recordid = $value->contact_recordid;
            array_push($contact_recordid_list, $contact_recordid);
        }
        $contact_recordid_list = array_unique($contact_recordid_list);

        $new_recordid = Contact::max('contact_recordid') + 1;
        if (in_array($new_recordid, $contact_recordid_list)) {
            $new_recordid = Contact::max('contact_recordid') + 1;
        }
        $contact->contact_recordid = $new_recordid;

        if ($request->contact_service) {
            $contact->contact_services = join(',', $request->contact_service);
        } else {
            $contact->contact_services = '';
        }
        $contact->service()->sync($request->contact_service);

        $contact->contact_phones = '';
        $phone_recordid_list = [];
        if ($request->contact_phones) {
            $contact_phone_number_list = $request->contact_phones;
            foreach ($contact_phone_number_list as $key => $contact_phone_number) {
                $phone_info = Phone::where('phone_number', '=', $contact_phone_number)->select('phone_recordid')->first();
                if ($phone_info) {
                    $contact->contact_phones = $contact->contact_phones . $phone_info->phone_recordid . ',';
                    array_push($phone_recordid_list, $phone_info->phone_recordid);
                } else {
                    $new_phone = new Phone;
                    $new_phone_recordid = Phone::max('phone_recordid') + 1;
                    $new_phone->phone_recordid = $new_phone_recordid;
                    $new_phone->phone_number = $contact_phone_number;
                    $new_phone->save();
                    $contact->contact_phones = $contact->contact_phones . $new_phone_recordid . ',';
                    array_push($phone_recordid_list, $new_phone_recordid);
                }
            }
        }
        $contact->phone()->sync($phone_recordid_list);

        $contact_facility_id = $request->contact_facility_recordid;

        $contact->save();

        return redirect('facility/'.$contact_facility_id);
    }

    public function add_new_contact_in_organization(Request $request)
    {
        $contact = new Contact;    

        $phone_recordids = Phone::select("phone_recordid")->distinct()->get();
        $phone_recordid_list = array();
        foreach ($phone_recordids as $key => $value) {
            $phone_recordid = $value->phone_recordid;
            array_push($phone_recordid_list, $phone_recordid);
        }
        $phone_recordid_list = array_unique($phone_recordid_list);           
        
        $contact->contact_name = $request->contact_name;
        $contact->contact_title = $request->contact_title;
        $contact->contact_department = $request->contact_department;
        $contact->contact_email = $request->contact_email;
        // $contact->contact_phone_extension = $request->contact_phone_extension;

        $organization_name = $request->contact_organization_name;
        $contact_organization = Organization::where('organization_name', '=', $organization_name)->first();
        $contact_organization_id = $contact_organization["organization_recordid"];
        $contact->contact_organizations = $contact_organization_id;

        $contact_recordids = Contact::select("contact_recordid")->distinct()->get();
        $contact_recordid_list = array();
        foreach ($contact_recordids as $key => $value) {
            $contact_recordid = $value->contact_recordid;
            array_push($contact_recordid_list, $contact_recordid);
        }
        $contact_recordid_list = array_unique($contact_recordid_list);

        $new_recordid = Contact::max('contact_recordid') + 1;
        if (in_array($new_recordid, $contact_recordid_list)) {
            $new_recordid = Contact::max('contact_recordid') + 1;
        }
        $contact->contact_recordid = $new_recordid;

        if ($request->contact_service) {
            $contact->contact_services = join(',', $request->contact_service);
            $contact->service()->sync($request->contact_service);
        } else {
            $contact->contact_services = '';
        } 
        
        // $contact_cell_phones = $request->contact_cell_phones;
        // $cell_phone = Phone::where('phone_number', '=', $contact_cell_phones)->first();
        // if ($cell_phone != null) {
        //     $cell_phone_id = $cell_phone["phone_recordid"];
        //     $contact->contact_phones = $cell_phone_id;
        // } else {
        //     $phone = new Phone;
        //     $new_recordid = Phone::max('phone_recordid') + 1;
        //     if (in_array($new_recordid, $phone_recordid_list)) {
        //         $new_recordid = Phone::max('phone_recordid') + 1;
        //     }
        //     $phone->phone_recordid = $new_recordid;
        //     $phone->phone_number = $contact_cell_phones;
        //     $phone->phone_type = "voice";
        //     $contact->contact_phones = $phone->phone_recordid;
        //     $phone->save();
        // }

        // $contact_phone_info_list = array();
        // array_push($contact_phone_info_list, $contact->contact_phones);
        // $contact_phone_info_list = array_unique($contact_phone_info_list);
        // $contact->phone()->sync($contact_phone_info_list);

        $contact->contact_phones = '';
        $phone_recordid_list = [];
        if ($request->contact_phones) {
            $contact_phone_number_list = $request->contact_phones;
            foreach ($contact_phone_number_list as $key => $contact_phone_number) {
                $phone_info = Phone::where('phone_number', '=', $contact_phone_number)->select('phone_recordid')->first();
                if ($phone_info) {
                    $contact->contact_phones = $contact->contact_phones . $phone_info->phone_recordid . ',';
                    array_push($phone_recordid_list, $phone_info->phone_recordid);
                } else {
                    $new_phone = new Phone;
                    $new_phone_recordid = Phone::max('phone_recordid') + 1;
                    $new_phone->phone_recordid = $new_phone_recordid;
                    $new_phone->phone_number = $contact_phone_number;
                    $new_phone->save();
                    $contact->contact_phones = $contact->contact_phones . $new_phone_recordid . ',';
                    array_push($phone_recordid_list, $new_phone_recordid);
                }
            }
        }
        $contact->phone()->sync($phone_recordid_list);

        $contact->save();

        return redirect('organization/'.$contact_organization_id);
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
        $contact= Contact::find($id);
        return response()->json($contact);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contact = Contact::where('contact_recordid', '=', $id)->first();
        $organization_info_list = Organization::select('organization_recordid', 'organization_name')->get();
        $service_info_list = Service::select('service_name', 'service_recordid')->get();
        $contact_services = [];
        foreach ($contact->service as $key => $value) {
             array_push($contact_services, $value->service_recordid);
        }  
        $phone_recordid = $contact->contact_phones;
        if ($phone_recordid) {
            $contact_phone = Phone::where('phone_recordid', '=', $phone_recordid)->select('phone_recordid', 'phone_number')->first();
        } else {
            $contact_phone = NULL;
        }
        $contact_service_recordid_list = [];
        if ($contact->contact_services) {
            $contact_service_recordid_list = explode(',', $contact->contact_services);
        }
             
        $map = Map::find(1);
        return view('frontEnd.contact-edit', compact('contact', 'map', 'organization_info_list', 'service_info_list', 'contact_services', 'contact_phone', 'contact_service_recordid_list'));
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
        $contact = Contact::find($id);
        $contact->contact_name = $request->contact_name;
        $contact->contact_title = $request->contact_title;
        $contact->contact_department = $request->contact_department;
        $contact->contact_email = $request->contact_email;
        // $contact->contact_phone_extension = $request->contact_phone_extension;
        $contact->contact_organizations = $request->contact_organization;

        if ($request->contact_services) {
            $contact->contact_services = join(',', $request->contact_services);
            $contact->service()->sync($request->contact_services);
        } else {
            $contact->contact_services = '';
        }

        $phone_recordids = Phone::select("phone_recordid")->distinct()->get();
        $phone_recordid_list = array();
        foreach ($phone_recordids as $key => $value) {
            $phone_recordid = $value->phone_recordid;
            array_push($phone_recordid_list, $phone_recordid);
        }
        $phone_recordid_list = array_unique($phone_recordid_list);   

        $contact->contact_phones = '';
        $phone_recordid_list = [];
        if ($request->contact_phones) {
            $contact_phone_number_list = $request->contact_phones;
            foreach ($contact_phone_number_list as $key => $contact_phone_number) {
                $phone_info = Phone::where('phone_number', '=', $contact_phone_number)->select('phone_recordid')->first();
                if ($phone_info) {
                    $contact->contact_phones = $contact->contact_phones . $phone_info->phone_recordid . ',';
                    array_push($phone_recordid_list, $phone_info->phone_recordid);
                } else {
                    $new_phone = new Phone;
                    $new_phone_recordid = Phone::max('phone_recordid') + 1;
                    $new_phone->phone_recordid = $new_phone_recordid;
                    $new_phone->phone_number = $contact_phone_number;
                    $new_phone->save();
                    $contact->contact_phones = $contact->contact_phones . $new_phone_recordid . ',';
                    array_push($phone_recordid_list, $new_phone_recordid);
                }
            }
        }
        $contact->phone()->sync($phone_recordid_list);
        
        $contact->flag = 'modified';
        $contact->save();

        $contact_organization = $request->contact_organization;
        $organization = Organization::where('organization_recordid', '=', $contact_organization)->select('organization_recordid', 'updated_at')->first();
        $organization->updated_at = date("Y-m-d H:i:s");
        $organization->save();

        return redirect('contact/' . $id);
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
