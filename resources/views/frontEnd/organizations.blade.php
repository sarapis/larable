@extends('layouts.app')
@section('title')
Organizations
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
    position: fixed !important;
}
.card-columns {
    -webkit-column-count: 2;
    column-count: 4 !important;
}

</style>

@section('content')
@include('layouts.filter_organization')
<div class="wrapper">
    @include('layouts.sidebar_organization')
    <div id="content" class="container">
        
        <div class="col-sm-12 p-20 card-columns">
            @foreach($organizations as $organization)
            <div class="card">
                <div class="card-block">
                    <h4 class="card-title">
                        <a href="/organization/{{$organization->organization_recordid}}" class="notranslate">{{$organization->organization_name}}</a>
                    </h4>
                    <p class="card-text" style="font-weight:400;">
                        {!! str_limit($organization->organization_description, 200) !!}
                    </p>
                    <p class="card-text">
                        <small class="text-muted"><b>Number of Services:</b> 
                            @if(isset($organization->services))
                                {{$organization->services->count()}}
                            @else 0 @endif
                        </small>
                    </p>
                </div>
            </div>
            @endforeach
        </div>
        <div class="example col-sm-12 ">
            <nav>
                {{ $organizations->appends(\Request::except('page'))->render() }}
            </nav>
        </div>
    </div>
</div>

@endsection


