@extends('layouts.app')
@section('title')
Organization Create
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
    button[data-id="organization_rating"] {
        height: 100%;
        border: 1px solid #ddd;
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

    .organization-details-div.org .dropdown.bootstrap-select.form-control {
        padding: 0 15px;
    }

    .delete-btn-div {
        text-align: center;
    }

    h1 {
        text-align: center;
    }
</style>

@section('content')
<div class="wrapper">
    <div id="organizations-edit-content" class="container">
        <h1>Create New Organization</h1>
        {{-- <form action="/add_new_organization" method="GET"> --}}
        {!! Form::open(['route' => 'organizations.store']) !!}
        <div class="row">

            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><b> Organization Name :</b> </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    <input class="form-control selectpicker" type="text" id="organization_name"
                        name="organization_name" value="">
                </div>
            </div>
           
            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><b> Alternate Name :</b> </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    <input class="form-control selectpicker" type="text" id="organization_alternate_name"
                        name="organization_alternate_name" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><b> Description : </b> </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    <textarea id="organization_description" name="organization_description" class="form-control selectpicker" rows="5" cols="30"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><b> Email :</b> </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    <input class="form-control selectpicker" type="text" id="organization_email"
                        name="organization_email" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4"><b> URL :</b> </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-details-div">
                    <input class="form-control selectpicker" type="text" rows="10" cols="30"
                        id="organization_url" name="organization_url" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4">Legal Status: </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-legal-status-div">
                    <input class="form-control selectpicker"  type="text" id="organization_legal_status" name="organization_legal_status" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4">Tax Status: </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-tax-status-div">
                    <input class="form-control selectpicker"  type="text" id="organization_tax_status" name="organization_tax_status" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4">Tax ID: </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-tax-id-div">
                    <input class="form-control selectpicker"  type="text" id="organization_tax_id" name="organization_tax_id" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4">Year Incorporated: </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-year-incorporated-div">
                    <input class="form-control selectpicker"  type="text" id="organization_year_incorporated" name="organization_year_incorporated" value="">
                </div>
            </div>

            <div class="form-group">                 
                <label class="control-label sel-label-org pl-4">Services: </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-services-div">
                    <select class="form-control selectpicker" multiple data-live-search="true" id="organization_services" data-size="5" name="organization_services[]">
                        @foreach($services_info_list as $key => $services_info)                                
                            <option value="{{$services_info->service_recordid}}">{{$services_info->service_name}}</option>
                        @endforeach
                    </select>
                </div>           
            </div>
            <div class="form-group">                 
                <label class="control-label sel-label-org pl-4">Contacts: </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-contacts-div">
                    <select class="form-control selectpicker" multiple data-live-search="true" data-size="5" id="organization_contacts" name="organization_contacts[]">
                        @foreach($organization_contacts_list as $key => $organization_cont)                                
                            <option value="{{$organization_cont->contact_recordid}}">{{$organization_cont->contact_name}}</option>
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
                    <li class="organization-phones-li mb-2">
                        <div class="col-md-12 col-sm-12 col-xs-12 organization-phones-div">
                            <input class="form-control selectpicker organization_phones"  type="text" name="organization_phones[]" value="">
                        </div> 
                    </li> 
                </ol>
            </div>
            @if (Sentinel::getUser() && Sentinel::getUser()->roles[0]->name == 'System Admin')
            <div class="form-group">                 
                <label class="control-label sel-label-org pl-4">Website Rating: </label>
                <div class="col-md-12 col-sm-12 col-xs-12 organization-rating-div">
                    <select class="form-control selectpicker" data-live-search="true" id="organization_rating" data-size="5" name="organization_rating">
                        @foreach($rating_info_list as $key => $rating_info)                                
                            <option value="{{$rating_info}}">{{$rating_info}}</option>
                        @endforeach
                    </select>
                </div>           
            </div>
            @endif
            <div class="form-group">
                <div class="col-md-12 text-center">

                    <a href="/" class="btn btn-info btn-rounded" id="view-organization-btn"><i
                            class="fa fa-arrow-left"></i> Close</a>

                    <button type="submit" class="btn btn-success btn-rounded" id="save-organization-btn"><i
                            class="fa fa-check"></i> Save</button>
                </div>
            </div>
        </div>
        {{-- </form> --}}
        {!! Form::close() !!}
    </div>
</div>

<script>
    $(document).ready(function() {
        $(document).ready(function() {
        $("#organization_services").selectpicker("");       
        $("#organization_contacts").selectpicker("");   
        $("#organization_locations").selectpicker(""); 
    });

    $("#add-phone-input").click(function(){
        $("ol#phones-ul").append(
            "<li class='organization-phones-li mb-2'>"
          + "<div class='col-md-12 col-sm-12 col-xs-12 organization-phones-div'>"
          + "<input class='form-control selectpicker organization_phones'  type='text' name='organization_phones[]'>"
          + "</div>"
          + "</li>" );
    });

</script>
@endsection