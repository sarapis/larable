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

    button[data-id="facility_location_name"] {
        height: 100%;
        border: 1px solid #ddd;
    } 

    button[data-id="facility_organization_name"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="facility_address_city"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="facility_street_address"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="facility_zip_code"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="facility_building_status"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="facility_facility_call"] {
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
        <div class="form-group delete-btn-div">
            <button class="btn btn-danger delete-td" id="delete-facility-btn" value="{{$facility->location_recordid}}" data-toggle="modal" data-target=".bs-delete-modal-lg"><i class="fa fa-fw fa-remove"></i>Delete</button>
        </div>
        <form action="/facility/{{$facility->location_recordid}}/update" method="GET">
            <div class="row">  
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 facility-details-div">
                        <input class="form-control selectpicker"  type="text" id="facility_location_name" name="facility_location_name" value="{{$facility->location_name}}">
                    </div>
                </div> 
                
                <div class="form-group"> 
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-rounded" id="save-facility-btn"><i class="fa fa-save"></i>Save</button>
                        <a href="/location/{{$facility->location_recordid}}" class="btn btn-success btn-rounded" id="view-facility-btn"><i class="fa fa-eye"></i>Close</a>
                    </div>                   
                </div>
            </div>
        </form>
        <div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="/facility_delete_filter" method="POST" id="facility_delete_filter">
                        {!! Form::token() !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">Delete Facility</h4>
                        </div>
                        <div class="modal-body">
                            
                            <input type="hidden" id="facility_recordid" name="facility_recordid">
                            <h4>Are you sure to delete this facility?</h4>
                            
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
    $('button.delete-td').on('click', function() {
        var value = $(this).val();
        $('input#facility_recordid').val(value);
    });
    
</script>
@endsection




