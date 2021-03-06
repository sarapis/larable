@extends('layouts.app')
@section('title')
Edit Service
@stop

<style type="text/css">   
    
    #services-edit-content {
        margin-top: 50px;
        width: 35%;
    }
    
    #services-edit-content .form-group {
        width: 100%;
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
    .service-details-div.org .dropdown.bootstrap-select.form-control {
        padding: 0 15px;
    }
    .delete-btn-div {
        text-align: center;
    }
    #view-service-btn {
        float: right;
    }
    h1 {
        text-align: center;
    }
    button[data-id="service_organization"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="service_locations"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="service_contacts"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="service_taxonomy"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="service_details"] {
        height: 100%;
        border: 1px solid #ddd;
    }

</style>

@section('content')
<div class="wrapper">
    <div id="services-edit-content" class="container">
        <h1>Edit Service</h1>
        <div class="form-group delete-btn-div">
            <button class="btn btn-danger delete-td" id="delete-service-btn" value="{{$service->service_recordid}}" data-toggle="modal" data-target=".bs-delete-modal-lg"><i class="fa fa-fw fa-remove"></i>Delete</button>
        </div>
        <form action="/service/{{$service->service_recordid}}/update" method="GET">
            <div class="row"> 
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 service-name-div">
                        <input class="form-control selectpicker"  type="text" id="service_name" name="service_name" value="{{$service->service_name}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Alternate Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 service-alternate-name-div">
                        <input class="form-control selectpicker"  type="text" id="service_alternate_name" name="service_alternate_name" value="{{$service->service_alternate_name}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Description: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 service-description-div">
                        <textarea id="service_description" name="service_description" class="form-control selectpicker" rows="5" cols="30">{{$service->service_description}}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">URL: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 service-url-div">
                        <input class="form-control selectpicker"  type="text" id="service_url" name="service_url" value="{{$service->service_url}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Email: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 service-email-div">
                        <input class="form-control selectpicker"  type="text" id="service_email" name="service_email" value="{{$service->service_email}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Address: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 service-address-div">
                        <input class="form-control selectpicker"  type="text" id="service_address" name="service_address" @if($service_address_city) value="{{$service_address_street->address_1}}, {{$service_address_city->address_city}}, {{$service_address_state->address_state_province}}, {{$service_address_postal_code->address_postal_code}} @endif">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Status: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 service-status-div">
                        <input class="form-control selectpicker"  type="text" id="service_status" name="service_status" value="{{$service->service_status}}">
                    </div>
                </div>
                <div class="form-group">                 
                    <label class="control-label sel-label-org pl-4">Organization: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 service-organization-div">
                        <select class="form-control selectpicker" data-live-search="true" id="service_organization" data-size="5" name="service_organization">
                            @foreach($service_organization_list as $key => $service_org)                                
                                <option value="{{$service_org->organization_recordid}}" @if ($service->service_organization == $service_org->organization_recordid) selected @endif>{{$service_org->organization_name}}</option>
                            @endforeach
                        </select>
                    </div>           
                </div>
            
                <div class="form-group">                 
                    <label class="control-label sel-label-org pl-4">Locations: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 service-locations-div">
                        <select class="form-control selectpicker" multiple data-live-search="true" data-size="5" id="service_locations" name="service_locations[]">
                            @foreach($service_location_list as $key => $service_loc)                                
                                <option value="{{$service_loc->location_recordid}}" @if (in_array($service_loc->location_recordid, $location_info_list)) selected @endif>{{$service_loc->location_name}}</option>
                            @endforeach
                        </select>
                    </div>           
                </div>
                <!-- <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Phone1: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 service-phone1-div">
                        <input class="form-control selectpicker"  type="text" id="service_phone1" name="service_phone1" @if($service_phone1) value="{{$service_phone1->phone_number}}" @endif>
                    </div>  
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Phone2: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 service-phone2-div">
                        <input class="form-control selectpicker"  type="text" id="service_phone2" name="service_phone2" @if($service_phone2) value="{{$service_phone2->phone_number}}" @endif>
                    </div>  
                </div> -->
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Phones: </label>
                    <a id="add-phone-input">
                        <span class="glyphicon glyphicon-plus-sign"></span>
                    </a>
                    <ol id="phones-ul">
                        @foreach($service->phone as $phone)
                        <li class="service-phones-li mb-2">
                            <div class="col-md-12 col-sm-12 col-xs-12 service-phones-div">
                                <input class="form-control selectpicker service_phones"  type="text" name="service_phones[]" value="{{$phone->phone_number}}">
                            </div> 
                        </li> 
                        @endforeach
                    </ol>
                </div>
                <div class="form-group">                 
                    <label class="control-label sel-label-org pl-4">Contacts: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 service-contacts-div">
                        <select class="form-control selectpicker" multiple data-live-search="true" data-size="5" id="service_contacts" name="service_contacts[]">
                            @foreach($service_contacts_list as $key => $service_cont)                                
                                <option value="{{$service_cont->contact_recordid}}" @if (in_array($service_cont->contact_recordid, $contact_info_list)) selected @endif>{{$service_cont->contact_name}}</option>
                            @endforeach
                        </select>
                    </div>           
                </div>
                <div class="form-group">                 
                    <label class="control-label sel-label-org pl-4">Taxonomy: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 service-taxonomy-div">
                        <select class="form-control selectpicker" multiple data-live-search="true" data-size="5" id="service_taxonomy" name="service_taxonomy[]">
                            @foreach($service_taxonomy_list as $key => $service_taxo)                                
                                <option value="{{$service_taxo->taxonomy_recordid}}" @if (in_array($service_taxo->taxonomy_recordid, $taxonomy_info_list)) selected @endif>{{$service_taxo->taxonomy_name}}</option>
                            @endforeach
                        </select>
                    </div>           
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Application Process: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 service-application-process-div">
                        <textarea id="service_application_process" name="service_application_process" class="form-control selectpicker" rows="5" cols="30">{{$service->service_application_process}}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Wait Time: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 service-wait-time-div">
                        <input class="form-control selectpicker"  type="text" id="service_wait_time" name="service_wait_time" value="{{$service->service_wait_time}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Fees: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 service-fees-div">
                        <input class="form-control selectpicker"  type="text" id="service_fees" name="service_fees" value="{{$service->service_fees}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Accreditations: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 service-accreditations-div">
                        <input class="form-control selectpicker"  type="text" id="service_accreditations" name="service_accreditations" value="{{$service->service_accreditations}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Licenses: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 service-licenses-div">
                        <input class="form-control selectpicker"  type="text" id="service_licenses" name="service_licenses" value="{{$service->service_licenses}}">
                    </div>
                </div>

                
              <!--   <div class="form-group">                 
                    <label class="control-label sel-label-org pl-4">Details: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 service-details-div">
                        <select class="form-control selectpicker" data-live-search="true" data-size="5" id="service_details" name="service_details">
                            @foreach($service_details_list as $key => $service_det)                                
                                <option value="{{$service_det->detail_recordid}}" @if ($service->service_details == $service_det->detail_recordid) selected @endif>{{$service_det->detail_value}}</option>
                            @endforeach
                        </select>
                    </div>           
                </div> -->

                <div class="form-group"> 
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-rounded" id="save-service-btn"><i class="fa fa-save"></i>Save</button>
                        <a href="/service/{{$service->service_recordid}}" class="btn btn-success btn-rounded" id="view-service-btn"><i class="fa fa-eye"></i>Close</a>
                    </div>                   
                </div>
            </div>
        </form>
        <div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="/service_delete_filter" method="POST" id="service_delete_filter">
                        {!! Form::token() !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">Delete service</h4>
                        </div>
                        <div class="modal-body">
                            
                            <input type="hidden" id="service_recordid" name="service_recordid">
                            <h4>Are you sure to delete this service?</h4>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger btn-delete">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script> 
    $(document).ready(function() {
        $("#service_organization").selectpicker("");
        $("#service_locations").selectpicker("");
        $("#service_contacts").selectpicker("");
        $("#service_taxonomy").selectpicker("");
        $("#service_details").selectpicker("");
    }); 

    $('button.delete-td').on('click', function() {
        var value = $(this).val();
        $('input#service_recordid').val(value);
    });

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




