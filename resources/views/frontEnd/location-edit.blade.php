@extends('layouts.app')
@section('title')
Facility Edit
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">   
    
    #facilities-edit-content {
        margin-top: 50px;
        width: 35%;
    }
    
    #facilities-edit-content .form-group {
        width: 100%;
    }


    button[data-id="facility_organization_name"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="facility_service"] {
        height: 100%;
        border: 1px solid #ddd;
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
    button[data-id="facility_address"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    .dropdown-menu.show {
        width: 100% !important;
    }

    .form-group button {
        width: 32.96%;
    }

    .form-group a {
        width: 32.96%;
    }

    @media only screen and (max-width: 768px) {
        .form-group button {
            width: 100%;
        }
        .form-group a {
            width: 32.96%;
        }
    }
    .facility-details-div.org .dropdown.bootstrap-select.form-control {
        padding: 0 15px;
    }
    .delete-btn-div {
        text-align: center;
    }
    #view-facility-btn {
        float: right;
    }
    h1 {
        text-align: center;
    }
</style>

@section('content')
<div class="wrapper">
    <div id="facilities-edit-content" class="container">
        <h1>Edit Facility</h1>
        <form action="/facility/{{$facility->location_recordid}}/update" method="GET">
            <div class="row">  
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Facility Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="location_name"
                            name="location_name" value="{{$facility->location_name}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Organization: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 facility-details-div">
                        {!!
                        Form::select('facility_organization_name',$organization_names,$facility->location_organization,['class'
                        => 'form-control
                        selectpicker','id' => 'facility_organization_name' ,'placeholder' => 'Select organization', 'data-live-search' => 'true', 'data-size' => '5']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Facility Alternate Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="location_alternate_name"
                            name="location_alternate_name" value="{{$facility->location_alternate_name}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Facility Transportation: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="location_transporation"
                            name="location_transporation" value="{{$facility->location_transportation}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Facility Description: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <textarea id="location_description" name="location_description" class="form-control selectpicker" rows="5" cols="30">{{$facility->location_description}}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Facility Service: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 facility-details-div">
                        {!!
                        Form::select('facility_service[]',$service_info_list, $facility_service_list, ['class'
                        => 'form-control
                        selectpicker','id' => 'facility_service', 'multiple' => 'true', 'data-live-search' => 'true', 'data-size' => '5']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Phone Number: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="facility_phones"
                            name="facility_phones" value="{{$facility_phone_number}}">
                        <p id="error_cell_phone" style="font-style: italic; color: red;">Invalid phone number! Example: +39 422 789611, 0422-78961, (042)589-6000, +39 (0422)7896, 0422 (789611), 39 422/789 611 </p>
                    </div>
                </div>
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
                <div class="form-group">
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
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Facility Details: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="location_details"
                            name="location_details" value="{{$facility->location_details}}">
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
    $(document).ready(function(){
        $('#error_cell_phone').hide();
        $("#facilities-edit-content").submit(function(event){
            // var mob = /^((\+)?[1-9]{1,2})?([-\s\.])?((\(\d{1,4}\))|\d{1,4})(([-\s\.])?[0-9]{1,12})$/;
            var mob = /^(?!.*([\(\)\-\/]{2,}|\([^\)]+$|^[^\(]+\)|\([^\)]+\(|\s{2,}).*)\+?([\-\s\(\)\/]*\d){9,15}[\s\(\)]*$/;
            var facility_phones = $("#facility_phones").val();
            if (facility_phones != ''){
                if(mob.test(facility_phones) == false && facility_phones != 10){ 
                    $('#error_cell_phone').show();              
                    event.preventDefault();
                } 
            }
           
        });
    });
    
</script>
@endsection




