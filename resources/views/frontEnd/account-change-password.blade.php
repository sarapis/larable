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
        <h1>Change Password</h1>
        <form action="/account/{{$user_info->id}}/update" method="GET">
            <div class="row"> 
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">New Password: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 service-new-password-div">
                        <input class="form-control selectpicker"  type="text" id="new_password" name="new_password">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Confirm Password: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 service-confirm-password-div">
                        <input class="form-control selectpicker"  type="text" id="confirm_password" name="confirm_password">
                    </div>
                </div>
                <div class="form-group"> 
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-rounded" id="update-password-btn"><i class="fa fa-save"></i>Save</button>
                        <a href="/account/{{$user_info->id}}" class="btn btn-success btn-rounded" id="view-service-btn"><i class="fa fa-eye"></i>Close</a>
                    </div>                   
                </div>
            </div>
        </form>
       
    </div>
</div>

<script> 
</script>
@endsection




