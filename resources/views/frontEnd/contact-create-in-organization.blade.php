@extends('layouts.app')
@section('title')
Contact Create
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    #contacts-edit-content {
        margin-top: 50px;
        width: 35%;
    }

    #contacts-edit-content .form-group {
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
    <div id="contacts-edit-content" class="container">
        <h1>Create New Contact</h1>
        <form action="/add_new_contact_in_organization" method="GET">
            <div class="row">
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Contact Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="contact_name"
                            name="contact_name" value="">
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
                <input type="hidden" id="contact_organization_name" name="contact_organization_name" value="{{$organization->organization_name}}">
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Contact Email: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="contact_email"
                            name="contact_email" value="">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-rounded" id="back-contact-btn"><i
                                class="fa fa-arrow-left"></i> Back</button>
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
</script>
@endsection