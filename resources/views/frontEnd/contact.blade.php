@extends('layouts.app')
@section('title')
Contact
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">


<style type="text/css">

    #content-contact-profile {
        width: calc(50% - 270px);
        padding: 0px;
        transition: all 0.3s;
        background: white;
        min-height: calc(100% - 134px);
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

    #map {
        position: relative !important;
        z-index: 0 !important;
    }

    @media (max-width: 768px) {
        .property {
            padding-left: 30px !important;
        }

        #map {
            display: block !important;
            width: 100% !important;
        }
    }

    .morecontent span {
        display: none;

    }

    .morelink {
        color: #428bca;
    }

    table#tbl-message-profile-contact {
        width: 100% !important;
        display: block;
        border-bottom: 0px;
    }

    #tbl-message-profile-contact_wrapper {
        overflow-x: scroll;
    }

    #contact_group_list_div {
        display: flex;
        flex-direction: column;
    }

    #tagging-div {
        margin-top: 12px !important;
    }
</style>

@section('content')
@if (session()->has('error'))
<div class="alert alert-danger alert-dismissable custom-success-box" style="margin: 15px;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong> {{ session()->get('error') }} </strong>
</div>
@endif
@if (session()->has('success'))
<div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong> {{ session()->get('success') }} </strong>
</div>
@endif
@if (session()->has('success'))
<div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong> {{ session()->get('success') }} </strong>
</div>
@endif
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="wrapper">
    <!-- Page Content Holder -->
    <div id="content-contact-profile" class="container">
        <div class="row m-0">
            <div class="col-md-12 pt-50">
                <div class="card">
                    <div class="card-block">
                        <h4 class="card-title">
                            <a href="">@if($contact->contact_name!='?'){{$contact->contact_name}}@endif
                            </a>
                           <!--  <a href="/contact/{{$contact->contact_recordid}}/edit"
                                class="btn btn-floating btn-success waves-effect waves-classic" style="float: right;">
                                <i class="icon md-edit" style="margin-right: 0px;"></i>
                            </a> -->
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Contact Title:</b></span>
                            {{$contact->contact_title}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Organization:</b></span>
                            @if ($contact->organization)
                            <a
                                href="/organization/{{$contact->organization->organization_recordid}}">{{ $contact->organization->organization_name }}</a>

                            @endif
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Department:</b></span>
                            {{$contact->contact_department}}
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Email:</b></span>
                            {{$contact->contact_email}}
                        </h4>
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
</script>
@endsection