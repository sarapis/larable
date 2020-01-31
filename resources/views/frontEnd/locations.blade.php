@extends('layouts.app')
@section('title')
Locations
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/scroller/2.0.1/css/scroller.dataTables.min.css">

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

    button[data-id="type"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="borough"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="tag"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="city_council_district"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="community_district"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="zipcode"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="address"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    .sel-label-org {
        width: 15%;
    }

    #clear-filter-locations-btn {
        width: 100%;
    }

    #tbl-location_wrapper {
        overflow-x: scroll;
    }
</style>

@section('content')
<div class="wrapper">
    <div id="locations-content" class="container">
        <div class="row">
            <div class="col-sm-12 p-20">
                <table class="table table-striped jambo_table bulk_action nowrap" id="tbl-location">
                    <thead>
                        <tr>
                            <th class="default-active"></th>
                            <th class="default-inactive">Id</th>
                            <th class="default-active">Facility Name</th>
                            <th class="default-active">Organization</th>
                            <th class="default-active">Address</th>
                            <th class="default-active">Facility Description</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
@section('customScript')
<script type="text/javascript"
    src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/js/dataTables.checkboxes.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/scroller/2.0.1/js/dataTables.scroller.min.js"></script>
<script src="{{asset('js/markerclusterer.js')}}"></script>
<script>
    var dataTable;

    $(document).ready(function() {
        // $('#waiting').hide();
        sessionStorage.setItem('check_marks', '');
        dataTable = $('#tbl-location').DataTable({
            "scrollX": true,
            "dom": 'lBfrtip',
            "order": [[ 2, 'desc' ]],
            "serverSide": true,          
            "searching": true,                   
            "scrollY": 500,
            "scroller": {
                "loadingIndicator": true
            },
            "ajax": function (data, callback, settings) {
                    var start = data.start;
                    var length = data.length;
                    var search_term = data.search.value;
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    // $('#waiting').show();
                    $('#loading').show();
              
                    $.ajax({
                        type: "POST",
                        url: "/get_all_facilities",
                        data: {
                            start: start,
                            length: length,
                            search_term: search_term,
                        },
                        success: function (response) {
                            // $('#waiting').hide();
                            $('#loading').hide();
                            callback({
                                draw: data.draw,
                                data: response.data,
                                recordsTotal: response.recordsTotal,
                                recordsFiltered: response.recordsFiltered,
                            });                            
                        },
                        error: function (data) {
                            $('#loading').hide();
                            console.log('Error:', data);
                        }
                    });
                },
            "columnDefs": [
                { targets: 'default-inactive', visible: false},
                {
                    "targets": 0,
                    "data": null,
                    "render": function ( data, type, row ) {
                        return '<a class=" open-td" href="/facility/' + row[1] + '" style="color: #007bff;"><i class="fa fa-fw fa-eye"></a>';
                    }
                   
                }
            ],
            'select': {
                'style': 'multi'
            },
            'scroller': {
                'loadingIndicator': true
            }
        });   
    })

</script>
@endsection