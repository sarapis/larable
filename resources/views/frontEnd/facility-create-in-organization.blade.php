@extends('layouts.app')
@section('title')
Facility Create
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    #facility-create-content {
        margin-top: 50px;
        width: 35%;
    }

    #facility-create-content .form-group {
        width: 100%;
    }

    button[data-id="facility_schedules"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="facility_details"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="facility_service"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="facility_address_city"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="facility_address_state"] {
        height: 100%;
        border: 1px solid #ddd;
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
    <div id="facility-create-content" class="container">
        <h1>Create New Facility</h1>
        <form action="/add_new_facility_in_organization" method="GET">
            <div class="row">
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Facility Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="location_name"
                            name="location_name" value="">
                    </div>
                </div>
                <input type="hidden" id="facility_organization" name="facility_organization" value="{{$organization->organization_name}}">
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Facility Alternate Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="location_alternate_name"
                            name="location_alternate_name" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Facility Transportation: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="location_transporation"
                            name="location_transporation" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Facility Description: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <textarea id="location_description" name="location_description" class="form-control selectpicker" rows="5" cols="30"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Facility Service: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <select class="form-control selectpicker" multiple data-live-search="true" id="facility_service"
                            name="facility_service[]" data-size="8">
                            @foreach($service_info_list as $key => $service_info)
                            <option value="{{$service_info->service_recordid}}">{{$service_info->service_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Phones: </label>
                    <a id="add-phone-input">
                        <span class="glyphicon glyphicon-plus-sign"></span>
                    </a>
                    <ol id="phones-ul">
                        <li class="facility-phones-li mb-2">
                            <div class="col-md-12 col-sm-12 col-xs-12 facility-phones-div">
                                <input class="form-control selectpicker facility_phones"  type="text" name="facility_phones[]" value="">
                            </div> 
                        </li> 
                    </ol>
                </div>
                <!-- <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Phone Number: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="facility_phones"
                            name="facility_phones" value="">
                        <p id="error_cell_phone" style="font-style: italic; color: red;">Invalid phone number! Example: +39 422 789611, 0422-78961, (042)589-6000, +39 (0422)7896, 0422 (789611), 39 422/789 611 </p>
                    </div>
                </div> -->
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Facility Schedule: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <select class="form-control selectpicker" multiple data-live-search="true" id="facility_schedules"
                            name="facility_schedules[]" data-size="5" >
                            @foreach($schedule_info_list as $key => $schedule_info)
                            <option value="{{$schedule_info->schedule_recordid}}">{{$schedule_info->schedule_opens_at}} ~ {{$schedule_info->schedule_closes_at}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!-- <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Facility Address: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <select class="form-control selectpicker" multiple data-live-search="true" id="facility_address"
                            name="facility_address[]" data-size="5" >
                            @foreach($address_info_list as $key => $address_info)
                            @if($address_info->address_1)
                            <option value="{{$address_info->address_recordid}}">{{$address_info->address_1}}, {{$address_info->address_city}}, {{$address_info->address_state_province}}, {{$address_info->address_postal_code}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div> -->
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Street Address: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 facility-details-div">
                        <input class="form-control selectpicker" type="text" id="facility_street_address"
                            name="facility_street_address" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">City: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 facility-details-div">
                        <select class="form-control selectpicker" data-live-search="true" id="facility_address_city"
                            name="facility_address_city", data-size="5">
                            @foreach($address_city_list as $key => $address_city)
                            <option value="{{$address_city}}">{{$address_city}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">State: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 facility-details-div">
                        <select class="form-control selectpicker" data-live-search="true" id="facility_address_state"
                            name="facility_address_state", data-size="5">
                            @foreach($address_states_list as $key => $address_state)
                            <option value="{{$address_state}}">{{$address_state}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Zip Code: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 facility-details-div">
                        <input class="form-control selectpicker" type="text" id="facility_zip_code"
                            name="facility_zip_code" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Facility Details: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="location_details"
                            name="location_details" value="">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-rounded" id="back-facility-btn"><i
                                class="fa fa-arrow-left"></i> Back</button>
                        <button type="submit" class="btn btn-success btn-rounded" id="save-facility-btn"><i
                                class="fa fa-check"></i> Save</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
<script>
    $('#back-facility-btn').click(function() {
        history.go(-1);
        return false;
    });
    $(document).ready(function() {
        $('select#facility_schedules').val([]).change();
        $('select#facility_address_city').val([]).change();
        $('select#facility_address_state').val([]).change();
    });
    // $(document).ready(function(){
    //     $('#error_cell_phone').hide();
    //     $("#facility-create-content").submit(function(event){
    //         // var mob = /^((\+)?[1-9]{1,2})?([-\s\.])?((\(\d{1,4}\))|\d{1,4})(([-\s\.])?[0-9]{1,12})$/;
    //         var mob = /^(?!.*([\(\)\-\/]{2,}|\([^\)]+$|^[^\(]+\)|\([^\)]+\(|\s{2,}).*)\+?([\-\s\(\)\/]*\d){9,15}[\s\(\)]*$/;
    //         var facility_phones = $("#facility_phones").val();
    //         if (facility_phones != ''){
    //             if(mob.test(facility_phones) == false && facility_phones != 10){ 
    //                 $('#error_cell_phone').show();              
    //                 event.preventDefault();
    //             } 
    //         }
           
    //     });
    // });

    $("#add-phone-input").click(function(){
        $("ol#phones-ul").append(
            "<li class='facility-phones-li mb-2'>"
          + "<div class='col-md-12 col-sm-12 col-xs-12 facility-phones-div'>"
          + "<input class='form-control selectpicker facility_phones'  type='text' name='facility_phones[]'>"
          + "</div>"
          + "</li>" );
    });

</script>
@endsection