@extends('layouts.app')
@section('title')
Organization Edit
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">   
    
    #organizations-edit-content {
        margin-top: 50px;
        width: 35%;
    }
    
    #organizations-edit-content .form-group {
        width: 100%;
    }  

    button[data-id="organization_services"] {
        height: 100%;
        border: 1px solid #ddd;
    }
   	button[data-id="organization_contacts"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="organization_phones"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="organization_locations"] {
        height: 100%;
        border: 1px solid #ddd;
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
    .organization-details-div.org .dropdown.bootstrap-select.form-control {
        padding: 0 15px;
    }
    .delete-btn-div {
        text-align: center;
    }
    #view-organization-btn {
        float: right;
    }
    h1 {
        text-align: center;
    }


</style>

@section('content')
<div class="wrapper">
    <div id="organizations-edit-content" class="container">
        <h1>Edit Organization</h1>
        <div class="form-group delete-btn-div">
            <button class="btn btn-danger delete-td" id="delete-organization-btn" value="{{$organization->organization_recordid}}" data-toggle="modal" data-target=".bs-delete-modal-lg"><i class="fa fa-fw fa-remove"></i>Delete</button>
        </div>
        <form action="/organization/{{$organization->organization_recordid}}/update" method="GET">
            <div class="row"> 
                
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-name-div">
                        <input class="form-control selectpicker"  type="text" id="organization_name" name="organization_name" value="{{$organization->organization_name}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Alternate Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-alternate-name-div">
                        <input class="form-control selectpicker"  type="text" id="organization_alternate_name" name="organization_alternate_name" value="{{$organization->organization_alternate_name}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Description: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-description-div">
                      <textarea id="organization_description" name="organization_description" class="form-control selectpicker" rows="5" cols="30">{{$organization->organization_description}}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Email: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-email-div">
                        <input class="form-control selectpicker"  type="text" id="organization_email" name="organization_email" value="{{$organization->organization_email}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">URL: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-url-div">
                        <input class="form-control selectpicker"  type="text" id="organization_url" name="organization_url" value="{{$organization->organization_url}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Legal Status: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-legal-status-div">
                        <input class="form-control selectpicker"  type="text" id="organization_legal_status" name="organization_legal_status" value="{{$organization->organization_legal_status}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Tax Status: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-tax-status-div">
                        <input class="form-control selectpicker"  type="text" id="organization_tax_status" name="organization_tax_status" value="{{$organization->organization_tax_status}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Tax ID: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-tax-id-div">
                        <input class="form-control selectpicker"  type="text" id="organization_tax_id" name="organization_tax_id" value="{{$organization->organization_tax_id}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Year Incorporated: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-year-incorporated-div">
                        <input class="form-control selectpicker"  type="text" id="organization_year_incorporated" name="organization_year_incorporated" value="{{$organization->organization_year_incorporated}}">
                    </div>
                </div>

                <div class="form-group">                 
                    <label class="control-label sel-label-org pl-4">Services: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-services-div">
                        <select class="form-control selectpicker" multiple data-live-search="true" id="organization_services" data-size="5" name="organization_services[]">
                            @foreach($services_info_list as $key => $services_info)                                
                                <option value="{{$services_info->service_recordid}}" @if (in_array($services_info->service_recordid, $organization_service_list)) selected @endif>{{$services_info->service_name}}</option>
                            @endforeach
                        </select>
                    </div>           
                </div>
                <div class="form-group">                 
                    <label class="control-label sel-label-org pl-4">Contacts: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 organization-contacts-div">
                        <select class="form-control selectpicker" multiple data-live-search="true" data-size="5" id="organization_contacts" name="organization_contacts[]">
                            @foreach($organization_contacts_list as $key => $organization_cont)                                
                                <option value="{{$organization_cont->contact_recordid}}" @if (in_array($organization_cont->contact_recordid, $contact_info_list)) selected @endif>{{$organization_cont->contact_name}}</option>
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
                        @foreach($organization->phones as $phone)
                        <li class="organization-phones-li mb-2">
                            <div class="col-md-12 col-sm-12 col-xs-12 organization-phones-div">
                                <input class="form-control selectpicker organization_phones"  type="text" name="organization_phones[]"value="{{$phone->phone_number}}">
                            </div> 
                        </li> 
                        @endforeach
                    </ol>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Service Contacts: </label>
                    <ol>
                        @foreach($organization_services as $service)
                            @foreach($service->contact as $contact)
                                <li>
                                    <h5>{{$contact->contact_name}}</h5>
                                </li>
                            @endforeach
                        @endforeach
                    </ol>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Service Locations: </label>
                    <ol>
                        @foreach($organization_services as $service)
                        <li class="organization-phones-li mb-2">
                            <div class="col-md-12 col-sm-12 col-xs-12 organization-service-locations-div">
                                @foreach($service->locations as $location)
                                    <h5>{{$location->location_name}}</h5>
                                @endforeach
                            </div> 
                        </li> 
                        @endforeach
                    </ol>
                </div>

                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Other Locations: </label>
                    <!-- <a id="add-location-input">
                        <span class="glyphicon glyphicon-plus-sign"></span>
                    </a> -->
                    <ol id="other-locations-ul">
                        @foreach($location_info_list as $location_info)
                        <li class="organization-locations-li mb-2">
                            <div class="col-md-12 col-sm-12 col-xs-12 organization-locations-div">
                                <select class="form-control selectpicker" data-live-search="true" data-size="5" id="organization_locations" name="organization_locations[]">
                                    @foreach($organization_locations_list as $key => $organization_loc)
                                        <option value="{{$organization_loc->location_recordid}}" @if ($organization_loc->location_recordid==$location_info) selected @endif>{{$organization_loc->location_name}}</option>
                                    @endforeach
                                </select>
                            </div> 
                        </li> 
                        @endforeach
                    </ol>
                </div>

                <div class="form-group"> 
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-rounded" id="save-organization-btn"><i class="fa fa-save"></i>Save</button>
                        <a href="/organization/{{$organization->organization_recordid}}" class="btn btn-success btn-rounded" id="view-organization-btn"><i class="fa fa-eye"></i>Close</a>
                    </div>                   
                </div>
            </div>
        </form>
        <div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="/organization_delete_filter" method="POST" id="organization_delete_filter">
                        {!! Form::token() !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">Delete Organization</h4>
                        </div>
                        <div class="modal-body">
                            
                            <input type="hidden" id="organization_recordid" name="organization_recordid">
                            <h4>Are you sure to delete this organization?</h4>
                            
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
        $("#organization_services").selectpicker("");       
        $("#organization_contacts").selectpicker("");   
        $("#organization_locations").selectpicker(""); 
    });
    $('button.delete-td').on('click', function() {
        var value = $(this).val();
        $('input#organization_recordid').val(value);
    });
    $("#add-phone-input").click(function(){
        $("ol#phones-ul").append(
            "<li class='organization-phones-li mb-2'>"
          + "<div class='col-md-12 col-sm-12 col-xs-12 organization-phones-div'>"
          + "<input class='form-control selectpicker organization_phones'  type='text' name='organization_phones[]'>"
          + "</div>"
          + "</li>" );
    });
    $("#add-location-input").click(function(){
        $("ol#other-locations-ul").append(
            "<li class='organization-locations-li mb-2'>"
          + "<div class='col-md-12 col-sm-12 col-xs-12 organization-locations-div'>"
          + "<input class='form-control selectpicker organization_locations'  type='text' name='organization_locations[]'>"
          + "</div>"
          + "</li>" );
    });

</script>
@endsection


 
               
            








