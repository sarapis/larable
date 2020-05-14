@extends('layouts.app')
@section('title')
Service Create
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    #service-create-content {
        margin-top: 50px;
        width: 35%;
    }

    #service-create-content .form-group {
        width: 100%;
    }

    .dropdown-menu.show {
        width: 100% !important;
    }

    .form-group button {
        width: auto;
    }

    .form-group a {
        width: auto;
    }

    button[data-id="service_organization"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="service_locations"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="service_status"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="service_taxonomies"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="service_schedules"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="service_contacts"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="service_details"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="service_address"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    @media only screen and (max-width: 768px) {
        .form-group button {
            width: 100%;
        }

        .form-group a {
            width: 32.96%;
        }
    }

    .contact-details-div.org .dropdown.bootstrap-select.form-control {
        padding: 0 15px;
    }

    h1 {
        text-align: center;
    }
</style>

@section('content')
<div class="wrapper">
    <div id="service-create-content" class="container">
        <h1>Create New Service</h1>
        <form action="/add_new_service" method="GET">
            <div class="row">
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Service Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="service_name"
                            name="service_name" value="" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Service Alternate Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="service_alternate_name"
                            name="service_alternate_name" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Organization Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <select class="form-control selectpicker" data-live-search="true" id="service_organization"
                            name="service_organization" data-size="5" required>
                            @foreach($organization_name_list as $key => $org_name)
                            <option value="{{$org_name}}">{{$org_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Service Description: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="service_description"
                            name="service_description" value="" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Service Locations: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <select class="form-control selectpicker" multiple data-live-search="true" id="service_locations"
                            name="service_locations[]" data-size="5" >
                            @foreach($facility_info_list as $key => $location_info)
                            <option value="{{$location_info->location_recordid}}">{{$location_info->location_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Service URL: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="service_url" name="service_url"
                            value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Service Program: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="service_program"
                            name="service_program" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Service Email: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="email" id="service_email"
                            name="service_email" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Status(Verified): </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <select class="form-control selectpicker" data-live-search="true" id="service_status"
                            name="service_status" data-size="5" >
                            @foreach($service_status_list as $key => $service_status)
                            <option value="{{$service_status}}">{{$service_status}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Taxonomies: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <select class="form-control selectpicker" multiple data-live-search="true" id="service_taxonomies"
                            name="service_taxonomies[]" data-size="5" >
                            @foreach($taxonomy_info_list as $key => $taxonomy_info)
                            <option value="{{$taxonomy_info->taxonomy_recordid}}">{{$taxonomy_info->taxonomy_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Application Process: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="service_application_process"
                            name="service_application_process" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Wait Time: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="service_wait_time"
                            name="service_wait_time" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Service Fees: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="service_fees"
                            name="service_fees" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Service Accrediations: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="service_accrediations"
                            name="service_accrediations" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Licenses: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="service_licenses"
                            name="service_licenses" value="">
                    </div>
                </div>
                <!-- <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Service Phone: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="service_phones"
                            name="service_phones" value="">
                        <p id="error_service_phones" style="font-style: italic; color: red;">Invalid phone number! Example: +39 422 789611, 0422-78961, (042)589-6000, +39 (0422)7896, 0422 (789611), 39 422/789 611 </p>
                    </div>
                </div> -->
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Phones: </label>
                    <a id="add-phone-input">
                        <span class="glyphicon glyphicon-plus-sign"></span>
                    </a>
                    <ol id="phones-ul">
                        <li class="service-phones-li mb-2">
                            <div class="col-md-12 col-sm-12 col-xs-12 service-phones-div">
                                <input class="form-control selectpicker service_phones"  type="text" name="service_phones[]" value="">
                            </div> 
                        </li> 
                    </ol>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Service Schedule: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <select class="form-control selectpicker" multiple data-live-search="true" id="service_schedules"
                            name="service_schedules[]" data-size="5" >
                            @foreach($schedule_info_list as $key => $schedule_info)
                            <option value="{{$schedule_info->schedule_recordid}}">{{$schedule_info->schedule_opens_at}} ~ {{$schedule_info->schedule_closes_at}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Contacts: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <select class="form-control selectpicker" multiple data-live-search="true" id="service_contacts"
                            name="service_contacts[]" data-size="5" >
                            @foreach($contact_info_list as $key => $contact_info)
                            <option value="{{$contact_info->contact_recordid}}">{{$contact_info->contact_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Service Details: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <select class="form-control selectpicker" multiple data-live-search="true" id="service_details"
                            name="service_details[]" data-size="5" >
                            @foreach($detail_info_list as $key => $detail_info)
                            <option value="{{$detail_info->detail_recordid}}">{{$detail_info->detail_value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Service Address: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <select class="form-control selectpicker" multiple data-live-search="true" id="service_address"
                            name="service_address[]" data-size="5" >
                            @foreach($address_info_list as $key => $address_info)
                            @if($address_info->address_1)
                            <option value="{{$address_info->address_recordid}}">{{$address_info->address_1}}, {{$address_info->address_city}}, {{$address_info->address_state_province}}, {{$address_info->address_postal_code}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Meta Data: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="service_metadata"
                            name="service_metadata" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Airs Taxonomy X: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="service_airs_taxonomy_x"
                            name="service_airs_taxonomy_x" value="">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-rounded" id="back-service-btn"><i
                                class="fa fa-arrow-left"></i> Back </button>
                        <button type="submit" class="btn btn-success btn-rounded" id="save-service-btn"><i
                                class="fa fa-check"></i> Save </button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
<script>
    $('#back-service-btn').click(function() {
        history.go(-1);
        return false;
    });
    $(document).ready(function() {
        $('select#service_organization').val([]).change();
        $('select#service_locations').val([]).change();
        $('select#service_schedules').val([]).change();
    });
    // $(document).ready(function(){
    //     $('#error_service_phones').hide();
    //     $("#service-create-content").submit(function(event){
    //         // var mob = /^((\+)?[1-9]{1,2})?([-\s\.])?((\(\d{1,4}\))|\d{1,4})(([-\s\.])?[0-9]{1,12})$/;
    //         var mob = /^(?!.*([\(\)\-\/]{2,}|\([^\)]+$|^[^\(]+\)|\([^\)]+\(|\s{2,}).*)\+?([\-\s\(\)\/]*\d){9,15}[\s\(\)]*$/;
    //         var service_phones = $("#service_phones").val();
    //         if (service_phones != ''){
    //             if(mob.test(service_phones) == false && service_phones != 10){ 
    //                 $('#error_service_phones').show();              
    //                 event.preventDefault();
    //             } 
    //         }
           
    //     });
    // });
    $("#add-phone-input").click(function(){
        $("ol#phones-ul").append(
            "<li class='service-phones-li mb-2'>"
          + "<div class='col-md-12 col-sm-12 col-xs-12 service-phones-div'>"
          + "<input class='form-control selectpicker service_phones'  type='text' name='service_phones[]'>"
          + "</div>"
          + "</li>" );
    });
</script>
@endsection