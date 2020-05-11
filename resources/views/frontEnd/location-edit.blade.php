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
                    <label class="control-label sel-label-org pl-4">Facility Description: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <textarea id="location_description" name="location_description" class="form-control selectpicker" rows="5" cols="30"></textarea>
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
    
</script>
@endsection




