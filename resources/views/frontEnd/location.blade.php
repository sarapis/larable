@extends('layouts.app')
@section('title')
Facility
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">


<style type="text/css">
.table a{
    text-decoration:none !important;
    color: rgba(40,53,147,.9);
    white-space: normal;
}
.footable.breakpoint > tbody > tr > td > span.footable-toggle{
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
#map{
    position: relative !important;
    z-index: 0 !important;
}
@media (max-width: 768px) {
    .property{
        padding-left: 30px !important;
    }
    #map{
        display: block !important;
        width: 100% !important;
    }
}
.morecontent span {
  display: none;

}
.morelink{
  color: #428bca;
}
button.dt-button {
    display: none !important;
}
div#tbl-location-profile-history_filter {
    margin-left: 10px;
}
table#tbl-location-profile-history {
    width: 100% !important;
    display: block;
    border-bottom: 0px;
}
#tbl-location-profile-history_wrapper {
    overflow-x: scroll;
}

#tagging-div {
    margin-top: 12px !important;
}

</style>

@section('content')
<div class="wrapper">
    <!-- Page Content Holder -->
    <div id="content" class="container">
		<div class="row m-0">
        	<div class="col-md-12 pt-15 pb-16">
               <div class="card">
                    <div class="card-block">
                        <h4 class="card-title">
							<a href="">{{$facility->location_name}}
                            </a>
                            <a href="/facility/{{$facility->location_recordid}}/edit" class="btn btn-floating btn-success waves-effect waves-classic" style="float: right;">
                                <i class="icon md-edit" style="margin-right: 0px;"></i>
                            </a>
                        </h4>
                        @if(isset($facility->address))
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Address:</b></span> 
							@if(isset($facility->address))
								@foreach($facility->address as $address)
								{{ $address->address_1 }} {{ $address->address_2 }} {{ $address->address_city }} {{ $address->address_state_province }} {{ $address->address_postal_code }}
								@endforeach
							@endif
                        </h4>
                        @endif
                        @if(isset($facility->phones))
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Phones:</b></span> 
							@foreach($facility->phones as $phone)
								@php 
									$phones ='';
									$phones = $phones.$phone->phone_number.','; 
								@endphp
							@endforeach
                            @if(isset($phones))
								{{ rtrim($phones, ',') }}
                            @endif
                        </h4>
                        @endif
                    </div>
                </div>
            </div>  
        </div>
    </div>
</div>

<script type="text/javascript" src="http://sliptree.github.io/bootstrap-tokenfield/dist/bootstrap-tokenfield.js"></script>
<script type="text/javascript" src="http://sliptree.github.io/bootstrap-tokenfield/docs-assets/js/typeahead.bundle.min.js"></script>

@endsection




