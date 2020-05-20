@extends('layouts.app')
@section('title')
Contact Create
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    #contacts-create-content {
        margin-top: 50px;
        width: 35%;
    }

    #contacts-create-content .form-group {
        width: 100%;
    }

    button[data-id="contact_service"] {
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
    <div id="contacts-create-content" class="container">
        <h1>Create New Contact</h1>
        <form action="/add_new_contact_in_facility" method="GET">
            <div class="row">
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Contact Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="contact_name"
                            name="contact_name" value="">
                    </div>
                </div>
                <input type="hidden" id="contact_facility_recordid" name="contact_facility_recordid" value="{{$facility->location_recordid}}">
                <input type="hidden" id="contact_organization" name="contact_organization" value="{{$facility->location_organization}}">
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Service: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <select class="form-control selectpicker" multiple data-live-search="true" id="contact_service"
                            name="contact_service[]" data-size="8">
                            @foreach($service_info_list as $key => $service_info)
                            <option value="{{$service_info->service_recordid}}">{{$service_info->service_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Contact Title: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="contact_title"
                            name="contact_title" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Contact Department: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="contact_department" name="contact_department"
                            value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Contact Email: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="contact_email"
                            name="contact_email" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Phones: </label>
                    <a id="add-phone-input">
                        <span class="glyphicon glyphicon-plus-sign"></span>
                    </a>
                    <ol id="phones-ul">
                        <li class="contact-phones-li mb-2">
                            <div class="col-md-12 col-sm-12 col-xs-12 contact-phones-div">
                                <input class="form-control selectpicker contact_phones"  type="text" name="contact_phones[]" value="">
                            </div> 
                        </li> 
                    </ol>
                </div>
                <div class="form-group">
                    <div class="col-md-12 text-center">
                        <a href="/contacts" class="btn btn-danger btn-rounded" id="view-contact-btn"><i
                                class="fa fa-arrow-left"></i> Back</a>
                        <button type="submit" class="btn btn-success btn-rounded" id="save-contact-btn"><i
                                class="fa fa-check"></i> Save</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
<script>
    $('#back-contact-btn').click(function() {
        history.go(-1);
        return false;
    });
    $(document).ready(function() {
        $('select#contact_service').val([]).change();
    });

    $("#add-phone-input").click(function(){
        $("ol#phones-ul").append(
            "<li class='service-phones-li mb-2'>"
          + "<div class='col-md-12 col-sm-12 col-xs-12 contact-phones-div'>"
          + "<input class='form-control selectpicker contact_phones'  type='text' name='contact_phones[]'>"
          + "</div>"
          + "</li>" );
    });

</script>
@endsection