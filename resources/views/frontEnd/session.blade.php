@extends('layouts.app')
@section('title')
Session Profile Page
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<style type="text/css">
    #facility-create-content {
        margin-top: 50px;
        width: 80%;
    }

    #facility-create-content .form-group {
        width: 100%;
    }

    button[data-id="interaction_method"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="interaction_disposition"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    .table a {
        text-decoration: none !important;
        color: rgba(40, 53, 147, .9);
        white-space: normal;
    }

    .footable.breakpoint>tbody>tr>td>span.footable-toggle {
        position: absolute;
        right: 25px;
        font-size: 25px;
        color: #000000;
    }

    .ui-menu .ui-menu-item .ui-state-active {
        padding-left: 0 !important;
    }

    ul#ui-id-1 {
        width: 260px !important;
    }

    .card {
        margin-bottom: 1.143rem !important;
    }

    .form-group button {
        width: 32.96%;
    }

    .card-title {
    	color: #36459b;
    }

</style>

@section('content')
<div class="wrapper">
	<div id="content" class="container">
		<div class="row m-50">
			<div class="col-md-8 pl-30">
				<div class="card">
                    <div class="card-block"> 
                        <div class="row">
                            <div class="col-md-6">
                            	<h4 class="card-title">
                                    Info box
                                </h4>  
                                <div>
                                    <div id="carouselButtons">
                                        <button id="playButton" type="button" class="btn btn-default btn-xs">
                                            <span class="glyphicon glyphicon-play"></span>
                                         </button>
                                        <button id="pauseButton" type="button" class="btn btn-default btn-xs">
                                            <span class="glyphicon glyphicon-pause"></span>
                                        </button>
                                    </div>

                                    <h4>
                                        <span class="badge bg-red pl-0 organize_font"><b>Timer:</b></span>
                                        <label id="minutes">00</label>:<label id="seconds">00</label>
                                    </h4>
                                    <h4>
                                        <span class="badge bg-red pl-0 organize_font"><b>Session ID:</b></span>
                                        {{$session->session_recordid}}
                                    </h4> 
                                    <h4>
                                        <span class="badge bg-red pl-0 organize_font"><b>User Name:</b></span>
                                        {{$session->user->first_name}} {{$session->user->last_name}}
                                    </h4> 
                                    <h4>
                                        <span class="badge bg-red pl-0 organize_font"><b>Organization:</b></span>
                                        <a href="/organization/{{$session->organization->organization_recordid}}">{{$session->organization->organization_name}}</a>
                                    </h4> 
                                    <h4>
                                        <span class="badge bg-red pl-0 organize_font"><b>Start Time:</b></span>
                                        <label id="start-time">{{$session->session_start}}</label>
                                    </h4>   
                                    <h4>
                                        <span class="badge bg-red pl-0 organize_font"><b>End Time:</b></span>
                                        <label id="end-time">{{$session->session_end}}</label>
                                    </h4>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex justify-content-center align-items-center">
                                <div class="card-block">
                                    <h4>
                                        <span class="badge bg-red pl-0 organize_font"><b>Status:</b></span>
                                        {{$session->session_verification_status}}
                                    </h4>
                                    <h4>
                                        <span class="badge bg-red pl-0 organize_font"><b>Notes:</b></span>
                                        {{$session->session_notes}}
                                    </h4>
                                    <a href="/session_info/{{$session->session_recordid}}/edit" class="btn btn-floating btn-success waves-effect waves-classic" style="float: right;">
                                        <i class="icon md-edit" style="margin-right: 0px;"></i>
                                    </a>
                                </div>                   	
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="col-md-4 pl-30">
            	<div class="card">
                    <div class="card-block">
                    	<h4 class="card-title">
                            Add Interaction
                        </h4>
                        <div class="page container-fluid pl-0 pr-0">
                            <div class="wrapper">
                                <div id="facility-create-content" class="container">
                                	<form action="/add_interaction" method="POST">
                                    {{ csrf_field() }}
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label sel-label-org pl-4">Session Method: </label>
                                                <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                                                    <select class="form-control selectpicker" data-live-search="true" id="interaction_method" name="interaction_method" data-size="5">
                                                        @foreach($method_list as $key => $method)
                                                        <option value="{{$method}}">{{$method}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <input type="hidden" id="session_recordid" name="session_recordid" value="{{$session->session_recordid}}">

                                            <div class="form-group">
                                                <label class="control-label sel-label-org pl-4">Session Disposition: </label>
                                                <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                                                    <select class="form-control selectpicker" data-live-search="true" id="interaction_disposition"
                                                        name="interaction_disposition" data-size="5">
                                                        @foreach($disposition_list as $key => $disposition)
                                                        <option value="{{$disposition}}">{{$disposition}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label sel-label-org pl-4">Session Notes: </label>
                                                <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                                                    <input class="form-control selectpicker" type="text" id="interaction_notes"
                                                        name="interaction_notes" value="">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label sel-label-org pl-4">Records Edited: </label>
                                                <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                                                    <input class="form-control selectpicker" type="text" id="interaction_records_edited"
                                                        name="interaction_records_edited" value="">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-md-12 text-center">
                                                    <button type="submit" class="btn btn-success btn-rounded" id="save-interaction-btn"><i class="fa fa-check"></i> Add</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</div>
		<div class="row">
			<div class="col-md-12 pt-15 pb-15 pl-30 pl-30">
                <div class="card">
                    <div class="card-block">
                        <h3 class="mt-0 mb-20">Interaction Log
                        </h3>
                        <div class="col-md-12">
                            <table class="table table-striped jambo_table bulk_action nowrap"
                                id="tbl-session-log">
                                <thead>
                                    <tr>
                                        <th>Method</th>
                                        <th>Timestamp</th>
                                        <th>Notes</th>
                                        <th>Disposition</th>
                                        <th>Records Edited</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($interaction_list as $key => $interaction)
                                    <tr>
                                        <td>{{$interaction->interaction_method}}</td>
                                        <td>{{$interaction->interaction_timestamp}}</td>
                                        <td>{{$interaction->interaction_notes}}</td>
                                        <td>{{$interaction->interaction_disposition}}</td>
                                        <td>{{$interaction->interaction_records_edited}}</td>
                                    </tr>
                                    @endforeach
                                <tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>			
		</div>
	</div>
</div>

<script>

	var minutesLabel = document.getElementById("minutes");
	var secondsLabel = document.getElementById("seconds");
	var startTimeLabel = document.getElementById("start-time");
	var endTimeLabel = document.getElementById("end-time");
	var totalSeconds = 0;
	var interval = null;
	var startTime, endTime;

	$("#playButton").on('click', function(e) {
      	e.preventDefault();
	    console.log('timer has been started');

	    startTime = new Date();
        console.log(startTime);
		startTimeLabel.innerHTML = startTime;

        $.ajax({
            type: 'post',
            url: '/session_start',
            data: {
                "_token": "{{ csrf_token() }}",
                "session_id": '{{$session->session_recordid}}',
                "session_start_time": startTime
            }
        })

	    interval = setInterval(setTime, 1000);

		function setTime() {
		  ++totalSeconds;
		  secondsLabel.innerHTML = pad(totalSeconds % 60);
		  minutesLabel.innerHTML = pad(parseInt(totalSeconds / 60));
		}

		function pad(val) {
		  var valString = val + "";
		  if (valString.length < 2) {
		    return "0" + valString;
		  } else {
		    return valString;
		  }
		}
  	});

  	$("#pauseButton").on('click', function(e) {
	    e.preventDefault();
	    console.log('timer has been stop');	    
	    clearInterval(interval); // stop interval
	    endTime = new Date();
	    endTimeLabel.innerHTML = endTime;

        $.ajax({
            type: 'post',
            url: '/session_end',
            data: {
                "_token": "{{ csrf_token() }}",
                "session_id": '{{$session->session_recordid}}',
                "session_end_time": endTime
            }
        })

  	});
	
</script>
@endsection