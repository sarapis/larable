@extends('layouts.app')
@section('title')
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">


<style type="text/css">
</style>

@section('content')

<div class="wrapper">
    <div id="content" class="container">
    	<div class="row m-0">
    		<div class="col-md-6 mx-auto" style="margin-top: 120px;">
    			<div class="card">
    				<div class="card-block">
    					<div class="card-title">
    						@if (Sentinel::getUser())
    						<h2>
    							<a href="">
									{{Sentinel::getUser()->first_name}} {{Sentinel::getUser()->last_name}}
	                            </a>
	                            <a href="" class="btn btn-floating btn-success waves-effect waves-classic" style="float: right;">
	                                <i class="icon md-edit" style="margin-right: 0px;"></i>
	                            </a>
    						</h2>
                            
                            <h4>
								<span class="badge bg-red pl-0 organize_font"><b>First Name:</b></span> 
								{{Sentinel::getUser()->first_name}}
		                    </h4>
		                    <h4>
								<span class="badge bg-red pl-0 organize_font"><b>Last Name:</b></span> 
								{{Sentinel::getUser()->last_name}}
		                    </h4>
		                    <h4>
								<span class="badge bg-red pl-0 organize_font"><b>Email:</b></span> 
								{{Sentinel::getUser()->email}}
		                    </h4>
		                    <h4>
								<span class="badge bg-red pl-0 organize_font"><b>Organization:</b></span> 
								{{Sentinel::getUser()->user_organization}}
		                    </h4>
		                    @endif
                        </div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
</div>

<script>
</script>
@endsection


