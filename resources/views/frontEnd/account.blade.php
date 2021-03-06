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
    			@if ($user)
	    			<div class="card">
	    				<div class="card-block">
	    					@if ($user->roles[0]->name == "System Admin")
							<a href="/dashboard" class="btn btn-primary ml-20" style="width: 20%; float: right;">
	                        	Access Backend
	               			</a>
	               			@endif 
	               			<a href="/account/{{$user->id}}/change_password" class="btn btn-danger mr-20" style="width: 20%; float: right;">
	                        	Change Password
	               			</a>
						</div>
					</div>						
				@endif
    			<div class="card">
    				<div class="card-block">
    					<div class="card-title">
    						@if ($user)    							
	    						<h2>
	    							<a href="">
										{{$user->first_name}} {{$user->last_name}}
		                            </a>
		                            <a href="/account/{{$user->id}}/edit" class="btn btn-floating btn-success waves-effect waves-classic" style="float: right;">
		                                <i class="icon md-edit" style="margin-right: 0px;"></i>
		                            </a>
	    						</h2>
	                            
	                            <h4>
									<span class="badge bg-red pl-0 organize_font"><b>First Name:</b></span> 
									{{$user->first_name}}
			                    </h4>
			                    <h4>
									<span class="badge bg-red pl-0 organize_font"><b>Last Name:</b></span> 
									{{$user->last_name}}
			                    </h4>
			                    <h4>
									<span class="badge bg-red pl-0 organize_font"><b>Email:</b></span> 
									{{$user->email}}

			                    </h4>
			                    @if ($organization_list)
				                    @if ($user->roles[0]->name != "System Admin")
				                    <h4>
										<span class="badge bg-red pl-0 organize_font"><b>Organization:</b></span> 
										<br>
										@foreach($organization_list as $organization)
		  									&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
		  									<a href="/organization/{{$organization->organization_recordid}}">
		  										{{$organization->organization_name}} </a>
		  									<br>
		  								@endforeach
				                    </h4>
				                    @endif
			                    @endif
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


