@extends('layouts.app')
@section('title')
Suggest a Change
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
    button[data-id="suggest_organization"] {
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
    .sel-label-org {
      font-size: large;
    }
    #suggest_content {
      width: 100%;
    }
</style>

@section('content')
<div class="wrapper">
    <div id="contacts-create-content" class="container">
        <h1>Suggest a Change</h1>
        <form action="/add_new_suggestion" method="GET">
            <div class="row">
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Organization * </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <label class="control-label sel-description-org pl-2">Select the organization for which you're suggesting a change</label>
                        <select class="form-control selectpicker" data-live-search="true" id="suggest_organization"
                            name="suggest_organization" data-size="5" required>
                            @foreach($organizations as $key => $organization)
                            <option value="{{$organization->organization_recordid}}">{{$organization->organization_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Suggestion * </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <label class="control-label sel-description-org pl-2">Explain what should be changed: Please be specific-reference the field that contains information which is incorrect or incomplete, and tell us what should be there instead. Thank you!</label>
                        <textarea id="suggest_content" name="suggest_content" rows="5" required></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Your Name * </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="suggest_name"
                            name="suggest_name" value="" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Your Email * </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="email" id="suggest_email"
                            name="suggest_email" value="" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Your Phone </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="suggest_phone"
                            name="suggest_phone" value="">
                    </div>
                </div>
       
                <div class="form-group">
                    <div class="col-md-12 text-center">
                        <!-- <a href="/contacts" class="btn btn-danger btn-rounded" id="view-contact-btn"><i
                                class="fa fa-arrow-left"></i> Back</a> -->
                        <button type="submit" class="btn btn-primary btn-rounded" id="save-suggestion-btn"><i
                                class="fa fa-check"></i> Submit</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
<script>
    // $('#back-contact-btn').click(function() {
    //     history.go(-1);
    //     return false;
    // });
    $(document).ready(function() {
        $('select#suggest_organization').val([]).change();
    });
</script>
@endsection