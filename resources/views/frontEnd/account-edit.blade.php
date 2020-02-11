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

    button[data-id="account_organizations"] {
        height: 100%;
        border: 1px solid #ddd;
    }

</style>

@section('content')
<div class="wrapper">
    <div id="services-edit-content" class="container">
        <h1>Edit Account Info</h1>
        <form action="/account/{{$user_info->id}}/update" method="GET">
            <div class="row"> 
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">First Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 first-name-div">
                        <input class="form-control selectpicker"  type="text" id="first_name" name="first_name" value="{{$user_info->first_name}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Last Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 service-alternate-name-div">
                        <input class="form-control selectpicker"  type="text" id="last_name" name="last_name" value="{{$user_info->last_name}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Email: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 service-alternate-name-div">
                        <input class="form-control selectpicker"  type="text" id="account_email" name="account_email" value="{{$user_info->email}}">
                    </div>
                </div>
                
                <div class="form-group">                 
                    <label class="control-label sel-label-org pl-4">Organizations: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 account_organizations-div">
                        <select class="form-control selectpicker" multiple data-live-search="true" data-size="5" id="account_organizations" name="account_organizations[]">
                            @foreach($organization_list as $key => $organization)                                
                                <option value="{{$organization->organization_recordid}}" @if (in_array($organization->organization_recordid, $account_organization_list)) selected @endif>{{$organization->organization_name}}</option>
                            @endforeach
                        </select>
                    </div>           
                </div>
               
                <div class="form-group"> 
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-rounded" id="save-service-btn"><i class="fa fa-save"></i>Save</button>
                        <a href="/account/{{$user_info->id}}" class="btn btn-success btn-rounded" id="view-service-btn"><i class="fa fa-eye"></i>Close</a>
                    </div>                   
                </div>
            </div>
        </form>
       
    </div>
</div>

<script> 
    $(document).ready(function() {
        $("#account_organizations").selectpicker("");
    }); 


</script>
@endsection




