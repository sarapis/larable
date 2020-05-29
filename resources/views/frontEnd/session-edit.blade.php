@extends('layouts.app')
@section('title')
Add Interaction
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    #facility-create-content {
        margin-top: 50px;
        width: 35%;
    }

    #facility-create-content .form-group {
        width: 100%;
    }

    button[data-id="session_method"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="session_disposition"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="session_status"] {
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
    <div id="facility-create-content" class="container">
        <h1>Edit Session Info</h1>
        <form action="/session_info/{{$session->session_recordid}}/update" method="GET">
            <div class="row">

                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Session Status: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <select class="form-control selectpicker" data-live-search="true" id="session_status"
                            name="session_status" data-size="5">
                            @foreach($session_status_list as $key => $session_status)
                            <option value="{{$session_status}}" @if ($session->session_status == $session_status) selected @endif>{{$session_status}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Session Notes: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="session_notes"
                            name="session_notes" value="{{$session->session_notes}}">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-rounded" id="back-session-btn"><i
                                class="fa fa-arrow-left"></i> Back</button>
                        <button type="submit" class="btn btn-success btn-rounded" id="save-session-btn"><i
                                class="fa fa-check"></i> Save</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
<script>
    $('#back-session-btn').click(function() {
        history.go(-1);
        return false;
    });

</script>
@endsection