@extends('layouts.app')
@section('title')
{{$organization->organization_name}}
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

#tagging-div {
    margin-top: 12px !important;
}

.comment-author {
    color: #3949ab !important;
    font-size: 18px !important;
}

</style>

@section('content')
@include('layouts.filter')
<div class="wrapper">
    @include('layouts.sidebar')
    <!-- Page Content Holder -->
    <div id="content" class="container">
        <!-- <div id="map" style="height: 30vh;"></div> -->
		    <!-- Example Striped Rows -->
    		<div class="row m-0">
            	<div class="col-md-8 pt-15 pb-15 pl-30">
                   <div class="card">
                        <div class="card-block">
                            <h4 class="card-title">
                  							<a href="">@if($organization->organization_logo_x)<img src="{{$organization->organization_logo_x}}" height="80">@endif {{$organization->organization_name}} @if($organization->organization_alternate_name!='')({{$organization->organization_alternate_name}})@endif
                  							</a>
                                @if (Sentinel::getUser() && str_contains(Sentinel::getUser()->user_organization, $organization->organization_recordid))
                                <a href="/organization/{{$organization->organization_recordid}}/edit" class="btn btn-floating btn-success waves-effect waves-classic" style="float: right;">
                                    <i class="icon md-edit" style="margin-right: 0px;"></i>
                                </a>
                                @endif
                                @if (Sentinel::getUser() && Sentinel::getUser()->roles[0]->name == 'System Admin')
                                <a href="/organization/{{$organization->organization_recordid}}/edit" class="btn btn-floating btn-success waves-effect waves-classic" style="float: right;">
                                    <i class="icon md-edit" style="margin-right: 0px;"></i>
                                </a>
                                @endif
                            </h4>
                            <h4>
                  							<span class="badge bg-red pl-0 organize_font"><b>Status:</b></span> 
                  							{{$organization->organization_status_x}}
                						</h4>
                            {{-- <h4 class="panel-text"><span class="badge bg-red">Alternate Name:</span> {{$organization->organization_alternate_name}}</h4> --}}
                            <h4 style="line-height:inherit"> {{$organization->organization_description}}</h4>
                            <h4 style="line-height: inherit;">
                            	<span><i class="icon md-globe font-size-24 vertical-align-top  mr-5 pr-10"></i>
                								<a href="{{$organization->organization_url}}" > {{$organization->organization_url}}</a>
                							</span> 
                						</h4>
                            @if($organization->phones)
                						<h4 style="line-height: inherit;">
                              	<span><i class="icon md-phone font-size-24 vertical-align-top  mr-5 pr-10"></i>
                  								@foreach($organization->phones as $phone)
                  								{{$phone->phone_number}}, 
                  								@endforeach
                							</span> 
                            </h4>
                            @endif
                            @if(isset($organization->organization_forms_x_filename))
                            <h4 class="py-10" style="line-height: inherit;"><span class="mb-10"><b>Referral Forms:</b></span>
                							<a href="{{$organization->organization_forms_x_url}}" class="panel-link"> {{$organization->organization_forms_x_filename}}</a>
                						</h4>
                            @endif
                        </div>
                    </div>

                    @if(isset($organization_services))
                    <h4 class="p-15 m-0 text-left bg-secondary" style=" border-radius:0; font-size:20px; background: #3f51b5;color: #fff;">Services (@if(isset($organization_services)){{$organization_services->count()}}@else 0 @endif)
                    </h4>
                    @foreach($organization_services as $service)
                    <div class="card">
            						<div class="card-block">
              							<h4 class="card-title">
              								  <a href="/service/{{$service->service_recordid}}">{{$service->service_name}}</a>
                                @if (Sentinel::getUser() && str_contains(Sentinel::getUser()->user_organization, $organization->organization_recordid))
                                <a href="/service/{{$service->service_recordid}}/edit" class="btn btn-floating btn-success waves-effect waves-classic" style="float: right;">
                                    <i class="icon md-edit" style="margin-right: 0px;"></i>
                                </a>
                                @endif
                                @if (Sentinel::getUser() && Sentinel::getUser()->roles[0]->name == 'System Admin')
                                <a href="/service/{{$service->service_recordid}}/edit" class="btn btn-floating btn-success waves-effect waves-classic" style="float: right;">
                                    <i class="icon md-edit" style="margin-right: 0px;"></i>
                                </a>
                                @endif
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
                                <h4><span class="badge bg-red"><b>{{ $detail['detail_type'] }}:</b></span> {!! $detail['detail_value'] !!}</h4>  
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

                    <div class="card page-project">
                        <h4 class="p-15 m-0 text-left bg-secondary" style=" border-radius:0; font-size:20px; background: #3f51b5;color: #fff;"> Contacts (@if(isset($organization->contact)){{$organization->contact->count()}}@else 0 @endif)
                        </h4>
                        @foreach($organization->contact as $contact_info)
                        <div class="card-block">
                            @if (Sentinel::getUser() && Sentinel::getUser()->roles[0]->name == 'System Admin')
                                <a href="/contact/{{$contact_info->contact_recordid}}/edit" class="btn btn-floating btn-success waves-effect waves-classic" style="float: right;">
                                    <i class="icon md-edit" style="margin-right: 0px;"></i>
                                </a>
                            @endif
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
                              @if (isset($contact_info->phone->phone_number))
                              <h4><span><b>Phones:</b></span> {{$contact_info->phone->phone_number}}</h4>
                              @endif
                            @endif
                        </div>
                        </br>
                        @endforeach
                    </div>
              </div>

              <div class="col-md-4 property">
          				<!-- <div class="pt-10 pb-10 pl-0 btn-download">
          					<a href="/download_organization/{{$organization->organization_recordid}}" class="btn btn-primary btn-button">Download PDF</a>
          					<button type="button" class="btn btn-primary btn-button" style="padding: 1px;">
                        <div class="sharethis-inline-share-buttons"></div>
                    </button>
          				</div> -->

                  <div class="pt-10 pb-10 pl-0 btn-download">
                      <form method="GET" action="/organization/{{$organization->organization_recordid}}/tagging"
                          id="organization_tagging">
                          <div class="row m-0" id="tagging-div">
                              <div class="col-md-10">
                                  <input type="text" class="form-control" id="tokenfield" name="tokenfield"
                                      value="{{$organization->organization_tag}}" />
                              </div>
                              <div class="col-md-2">
                                  <button type="submit" class="btn btn-secondary btn-tag-save">
                                      <i class="fas fa-save"></i>
                                  </button>
                              </div>
                          </div>
                      </form>
                  </div>
    				
          				<div class="card">
          					<div id="map" style="width:initial;margin-top: 0;height: 50vh;"></div>
          					<div class="card-block">
                      <h4 class="card-title">
                          <b>Locations</b>
                          @if (Sentinel::getUser() && str_contains(Sentinel::getUser()->user_organization, $organization->organization_recordid))
                          <a href="/facility/{{$service->service_locations}}/edit" class="btn btn-floating btn-success waves-effect waves-classic" style="float: right;">
                              <i class="icon md-edit" style="margin-right: 0px;"></i>
                          </a>
                          @endif
                      </h4>
          						<div class="p-10">
          						@if($location_info_list)
          							@foreach($location_info_list as $location)
                        @if (Sentinel::getUser() && Sentinel::getUser()->roles[0]->name == 'System Admin')
                            <a href="/facility/{{$location->location_recordid}}/edit" class="btn btn-floating btn-success waves-effect waves-classic" style="float: right;">
                                <i class="icon md-edit" style="margin-right: 0px;"></i>
                            </a>
                        @endif
          							<h4>
          								<span><i class="icon fas fa-building font-size-24 vertical-align-top  "></i>
          									{{$location->location_name}}
          								</span> 
          							</h4>                        
          							<h4>
          								<span><i class="icon md-pin font-size-24 vertical-align-top  "></i>
          									@if(isset($location->address))
          										@foreach($location->address as $address)
          										{{ $address->address_1 }} {{ $address->address_2 }} {{ $address->address_city }} {{ $address->address_state_province }} {{ $address->address_postal_code }}
          										@endforeach
          									@endif
          								</span> 
          							</h4>
          							<h4>
          								<span><i class="icon md-phone font-size-24 vertical-align-top  "></i>
          									@foreach($location->phones as $phone)
          									@php 
          										$phones ='';
          										$phones = $phones.$phone->phone_number.','; @endphp
          									@endforeach
                            @if(isset($phones))
          									{{ rtrim($phones, ',') }}
                            @endif
          								</span>
          							</h4>
                        </br>
          							@endforeach
          						@endif
          						</div>
                    </div>
                  </div>

                  <div class="pt-5 pb-0">
                      <h3>Comments</h3>
                      <div class="card">
                          <div class="card-block">
                              <div class="comment-body media-body">
                                  @foreach($comment_list as $key => $comment)
                                  <a class="comment-author" href="javascript:void(0)">{{$comment->comments_user_firstname}}
                                      {{$comment->comments_user_lastname}}</a>
                                  <div class="comment-meta">
                                      <span class="date">{{$comment->comments_datetime}}</span>
                                  </div>
                                  <div class="comment-content">
                                      <p style="color: black;">{{$comment->comments_content}}</p>
                                  </div>
                                  <hr>
                                  @endforeach
                                  <div class="comment-actions">
                                      <a class="active" id="reply-btn" href="javascript:void(0)" role="button">Add a
                                          comment</a>
                                  </div>
                                  <form class="comment-reply"
                                      action="/organization/{{$organization->organization_recordid}}/add_comment"
                                      method="POST">
                                      {{ csrf_field() }}
                                      <div class="form-group">
                                          <textarea class="form-control" id="reply_content" name="reply_content" rows="3">
                                          </textarea>
                                      </div>
                                      <div class="form-group">
                                          <button type="submit"
                                              class="btn btn-primary waves-effect waves-classic">Post</button>
                                          <button type="button" id="close-reply-window-btn"
                                              class="btn btn-link grey-600 waves-effect waves-classic">Close</button>
                                      </div>
                                  </form>
                              </div>
                          </div>
                      </div>
                  </div>

              </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="http://sliptree.github.io/bootstrap-tokenfield/dist/bootstrap-tokenfield.js">
</script>
<script type="text/javascript"
    src="http://sliptree.github.io/bootstrap-tokenfield/docs-assets/js/typeahead.bundle.min.js"></script>
<script>

  var tag_source = <?php print_r(json_encode($existing_tags)) ?>; 

  $(document).ready(function() {   
      $('#tokenfield').tokenfield({
      autocomplete: {
          source: tag_source,
          delay: 100
      },
      showAutocompleteOnFocus: true
      });
  });

  $(document).ready(function() {
      $('.comment-reply').hide();
      $('#reply_content').val('');
  });

  $(document).ready(function(){  
    setTimeout(function(){
      var locations = <?php print_r(json_encode($locations)) ?>;
      var organization = <?php print_r(json_encode($organization->organization_name)) ?>;
      var maplocation = <?php print_r(json_encode($map)) ?>;
      console.log(locations);

      if(maplocation.active == 1){
        avglat = maplocation.lat;
        avglng = maplocation.long;
        zoom = maplocation.zoom;
      }
      else
      {
          avglat = 40.730981;
          avglng = -73.998107;
          zoom = 12;
      }

      latitude = locations[0].location_latitude;
      longitude = locations[0].location_longitude;

      if(latitude == null){
        latitude = avglat;
        longitude = avglng;
      }

    
      var mymap = new GMaps({
        el: '#map',
        lat: latitude,
        lng: longitude,
        zoom: zoom
      });


      $.each( locations, function(index, value ){
            // console.log(locations);
            var name = value.organization==null?'':value.organization.organization_name;
            var serviceid = value.services.length == 0?'':value.services[0].service_recordid;
            var service_name = value.services.length == 0?'':value.services[0].service_name;

            var content = "";
            for(i = 0; i < value.services.length; i ++){
                content +=  '<a href="/service/'+value.services[i].service_recordid+'" style="color:#428bca;font-weight:500;font-size:14px;">'+value.services[i].service_name+'</a><br>';
            }
            content += '<p>'+name+'</p>';

            if(value.location_latitude){
                mymap.addMarker({

                    lat: value.location_latitude,
                    lng: value.location_longitude,
                    title: value.city,
                           
                    infoWindow: {
                        maxWidth: 250,
                        content: (content)
                    }
                });
            }
      });
    }, 2000)
  });

  $(document).ready(function() {
    var showChar = 250;
    var ellipsestext = "...";
    var moretext = "More";
    var lesstext = "Less";
    $('.more').each(function() {
      var content = $(this).html();

      if(content.length > showChar) {

        var c = content.substr(0, showChar);
        var h = content.substr(showChar, content.length - showChar);

        var html = c + '<span class="moreelipses">'+ellipsestext+'</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">'+moretext+'</a></span>';

        $(this).html(html);
      }

    });

    $(".morelink").click(function(){
      if($(this).hasClass("less")) {
        $(this).removeClass("less");
        $(this).html(moretext);
      } else {
        $(this).addClass("less");
        $(this).html(lesstext);
      }
      $(this).parent().prev().toggle();
      $(this).prev().toggle();
      return false;
    });

    $('.panel-link').on('click', function(e){
          if($(this).hasClass('target-population-link') || $(this).hasClass('target-population-child'))
              return;
          var id = $(this).attr('at');
          console.log(id);
          $("#category_" +  id).prop( "checked", true );
          $("#checked_" +  id).prop( "checked", true );
          $("#filter").submit();
      });
      
      $('.panel-link.target-population-link').on('click', function(e){
          $("#target_all").val("all");
          $("#filter").submit();
      });

      $('.panel-link.target-population-child').on('click', function(e){
          var id = $(this).attr('at');
          $("#target_multiple").val(id);
          $("#filter").submit();

      });
  });


  $("#reply-btn").on('click', function(e) {
      e.preventDefault();
      $('.comment-reply').show();
  });
  $("#close-reply-window-btn").on('click', function(e) {
      e.preventDefault();
      $('.comment-reply').hide();
  });

</script>
@endsection


