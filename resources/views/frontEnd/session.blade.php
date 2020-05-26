@extends('layouts.app')
@section('title')
{{$session->session_name}}
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<style type="text/css">
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
			<div class="col-md-4 pl-30">
				<div class="card">
                    <div class="card-block"> 
                    	<h4 class="card-title">
                            Info box
                        </h4>  
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
                            <label id="start-time"></label>
                        </h4>   
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>End Time:</b></span>
                            <label id="end-time"></label>
                        </h4>                    	
                    </div>
                </div>
            </div>
            <div class="col-md-4 pl-30">
            	<div class="card">
                    <div class="card-block">
                    	<h4 class="card-title">
                            Edit Status and Notes
                        </h4>
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
            <div class="col-md-4 pl-30">
            	<div class="card">
                    <div class="card-block">
                    	<h4 class="card-title">
                            Add Interaction
                        </h4>
                    	<h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Method:</b></span>
                            {{$session->session_method}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Disposition:</b></span>
                            {{$session->session_disposition}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Records Edited:</b></span>
                            {{$session->session_records_edited}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Notes:</b></span>
                            {{$session->session_notes}}
                        </h4>
                    </div>
                </div>
            </div>
		</div>
		<div class="row">
			<div class="col-md-12 pt-15 pb-15 pl-30 pl-30">
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
		startTimeLabel.innerHTML = startTime;

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
  	});
	
</script>
@endsection