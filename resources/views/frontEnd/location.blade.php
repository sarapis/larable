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

#content-location-profile {
    width: calc(50% - 270px);
    padding: 0px;
    transition: all 0.3s;
    background: white;
    min-height: calc(100% - 134px);
}

</style>

@section('content')
<div class="wrapper">
    <!-- Page Content Holder -->
    <div id="content-location-profile" class="container">
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
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Organization:</b></span>
                            <br>
                            @foreach($facility_organizations as $organization)
                                <a href="/organization/{{$organization->organization_id}}">{{$organization->organization_name}}</a>
                                <br>
                            @endforeach
                        </h4>
                        @if(isset($facility->address))
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Address:</b></span> 
							@if(isset($facility->address))
								@foreach($facility->address as $address)
								{{ $address->address_1 }} {{ $address->address_2 }} {{ $address->address_city }} {{ $address->address_state_province }} {{ $address->address_postal_code }} {{ $address->address_region }} {{ $address->address_country }}
								@endforeach
							@endif
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Latitude:</b></span>
                            {{$facility->location_latitude}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Longitude:</b></span>
                            {{$facility->location_longitude}}
                        </h4>
                        @endif
                        @if(isset($facility->phones))
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Phones:</b></span> 
							@foreach($facility->phones as $phone)
                                {{$phone->phone_number}}, 
                            @endforeach
                        </h4>
                        @endif
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Alternative Name:</b></span>
                            {{$facility->location_alternate_name}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Description:</b></span>
                            {{$facility->location_description}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Transportation:</b></span>
                            {{$facility->location_transportation}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Details:</b></span>
                            {{$facility->location_details}}
                        </h4>                        
                    </div>
                </div>

                @if(isset($facility_services))
                    <h4 class="p-15 m-0 text-left bg-secondary" style=" border-radius:0; font-size:20px; background: #3f51b5;color: #fff;">Services (@if(isset($facility_services)){{$facility_services->count()}}@else 0 @endif)
                    </h4>
                    @foreach($facility_services as $service)
                    <div class="card">
                        <div class="card-block">
                            <h4 class="card-title">
                                <a href="/service/{{$service->service_recordid}}">{{$service->service_name}}</a>
                            </h4>
                            <h4 style="line-height: inherit;">{!! str_limit($service->service_description, 200) !!}</h4>
                            <h4 style="line-height: inherit;">
                                <span><i class="icon md-phone font-size-24 vertical-align-top  mr-5 pr-10"></i>
                                @foreach($service->phone as $phone) {!! $phone->phone_number !!} @endforeach</span>
                            </h4>
                            <h4>
                                <span>
                                <i class="icon md-pin font-size-24 vertical-align-top  mr-5 pr-10"></i>
                                @if(isset($service->address))
                                    @foreach($service->address as $address)
                                      {{ $address->address_1 }} {{ $address->address_2 }} {{ $address->address_city }} {{ $address->address_state_province }} {{ $address->address_postal_code }}
                                    @endforeach
                                @endif
                                </span>
                            </h4>
                            @if($service->service_details!=NULL)
                                @php
                                    $show_details = [];
                                @endphp
                                @foreach($service->details->sortBy('detail_type') as $detail)
                                    @php
                                        for($i = 0; $i < count($show_details); $i ++){
                                            if($show_details[$i]['detail_type'] == $detail->detail_type)
                                                break;
                                        }
                                        if($i == count($show_details)){
                                            $show_details[$i] = array('detail_type'=> $detail->detail_type, 'detail_value'=> $detail->detail_value);
                                        }
                                        else{
                                            $show_details[$i]['detail_value'] = $show_details[$i]['detail_value'].', '.$detail->detail_value;
                                        }
                                    @endphp                                
                                @endforeach
                                @foreach($show_details as $detail)
                                    <h4>
                                        <span class="badge bg-red"><b>{{ $detail['detail_type'] }}:</b></span> {!! $detail['detail_value'] !!}
                                    </h4>  
                                @endforeach
                            @endif
                            <h4 class="py-10" style="line-height: inherit;">
                                <span class="pl-0 category_badge"><b>Types of Services:</b>
                                    @if($service->service_taxonomy!=0 || $service->service_taxonomy==null)
                                        @php 
                                            $names = [];
                                        @endphp
                                        @foreach($service->taxonomy->sortBy('taxonomy_name') as $key => $taxonomy)
                                            @if(!in_array($taxonomy->taxonomy_grandparent_name, $names))
                                                @if($taxonomy->taxonomy_grandparent_name && $taxonomy->taxonomy_parent_name != 'Target Populations')
                                                    <a class="panel-link {{str_replace(' ', '_', $taxonomy->taxonomy_grandparent_name)}}" at="{{str_replace(' ', '_', $taxonomy->taxonomy_grandparent_name)}}">{{$taxonomy->taxonomy_grandparent_name}}</a>
                                                    @php
                                                    $names[] = $taxonomy->taxonomy_grandparent_name;
                                                    @endphp
                                                @endif
                                            @endif
                                            @if(!in_array($taxonomy->taxonomy_parent_name, $names))
                                                @if($taxonomy->taxonomy_parent_name && $taxonomy->taxonomy_parent_name != 'Target Populations')
                                                    @if($taxonomy->taxonomy_grandparent_name)
                                                    <a class="panel-link {{str_replace(' ', '_', $taxonomy->taxonomy_parent_name)}}" at="{{str_replace(' ', '_', $taxonomy->taxonomy_grandparent_name)}}_{{str_replace(' ', '_', $taxonomy->taxonomy_parent_name)}}">{{$taxonomy->taxonomy_parent_name}}</a>
                                                    @endif
                                                    @php
                                                    $names[] = $taxonomy->taxonomy_parent_name;
                                                    @endphp
                                                @endif
                                            @endif
                                            @if(!in_array($taxonomy->taxonomy_name, $names))
                                                @if($taxonomy->taxonomy_name && $taxonomy->taxonomy_parent_name != 'Target Populations')
                                                    <a class="panel-link {{str_replace(' ', '_', $taxonomy->taxonomy_name)}}" at="{{$taxonomy->taxonomy_recordid}}">{{$taxonomy->taxonomy_name}}</a>
                                                    @php
                                                    $names[] = $taxonomy->taxonomy_name;
                                                    @endphp
                                                @endif
                                            @endif                                                    
                                           
                                        @endforeach
                                    @endif
                                </span>  
                            </h4>
                        </div>
                    </div>
                    @endforeach
                @endif

                @if(isset($facility->contact))
                <div class="card page-project">
                    <h4 class="card-title">
                        <b>Contacts</b>                        
                    </h4>
                    @foreach($facility->contact as $contact_info)
                    <div class="card-block">
                        @if($contact_info->contact_name)
                        <h4><span><b>Name:</b></span> {{$contact_info->contact_name}}</h4>
                        @endif
                        @if($contact_info->contact_title)
                        <h4><span><b>Title:</b></span> {{$contact_info->contact_title}}</h4>
                        @endif
                        @if($contact_info->contact_department)
                        <h4><span><b>Department:</b></span> {{$contact_info->contact_department}}</h4>
                        @endif
                        @if($contact_info->contact_email)
                        <h4><span><b>Email:</b></span> {{$contact_info->contact_email}}</h4>
                        @endif
                        @if($contact_info->contact_phones)
                        <h4><span><b>Phones:</b></span> {{$contact_info->phone->phone_number}}</h4>
                        @endif
                    </div>
                    </br>
                    @endforeach
                </div>
                @endif

            </div>  
        </div>
    </div>
</div>

<script type="text/javascript" src="http://sliptree.github.io/bootstrap-tokenfield/dist/bootstrap-tokenfield.js"></script>
<script type="text/javascript" src="http://sliptree.github.io/bootstrap-tokenfield/docs-assets/js/typeahead.bundle.min.js"></script>

@endsection




