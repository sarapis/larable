@extends('layouts.app')
@section('title')
Contacts
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/scroller/2.0.1/css/scroller.dataTables.min.css">

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

button[data-id="religion"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="faith_tradition"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="denomination"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="judicatory_body"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="has-email"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="has-phone"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="contact_type"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="tag"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="contact_address"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="contact_languages"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="contact_borough"] {
    height: 100%;
    border: 1px solid #ddd;
}
button[data-id="contact_zipcode"] {
    height: 100%;
    border: 1px solid #ddd;
}
.sel-label-org {
    width: 15%;
}
#clear-filter-contacts-btn {
    width: 100%;
}
#tbl-contact_wrapper {
    overflow-x: scroll;
}



</style>

@section('content')
<div class="wrapper">
    <!-- Page Content Holder -->
    <div id="contacts-content" class="container">
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" id="checked_terms" name="checked_terms">
            </div>
            <div class="col-sm-12 p-10"> 
                <table class="table table-striped jambo_table bulk_action nowrap" id="tbl-contact">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="All_check" id="check_all">
                            Action</th>
                            <th class="default-inactive">Id</th>
                            <th class="default-active">Name</th>
                            <th class="default-active">Contact Title</th>
                            <th class="default-active">Contact Department</th> 
                            <th class="default-active">Contact Email</th>
                            <th class="default-active">Organization</th>
                        </tr>
                    </thead>
                </table>
               
            </div>
        </div>
    </div>
</div>


@endsection
@section('customScript')

<script type="text/javascript" src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/js/dataTables.checkboxes.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/scroller/2.0.1/js/dataTables.scroller.min.js"></script>
<script src="{{asset('js/markerclusterer.js')}}"></script>
<script>
    var dataTable;
    var checked_terms_set;
    var filter_map = "";
    var marks = [];

    $(document).ready(function() {

        dataTable = $('#tbl-contact').DataTable({
            "scrollX": true,
            "dom": 'lBfrtip',
            "order": [[ 1, 'desc' ]],
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
                    $.ajax({
                        type: "POST",
                        url: "/get_all_contacts",
                        data: {
                            start: start,
                            length: length,
                            search_term: search_term
                        },
                        success: function (response) {
                            callback({
                                draw: data.draw,
                                data: response.data,
                                recordsTotal: response.recordsTotal,
                                recordsFiltered: response.recordsFiltered                               
                            });
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                },
            "columnDefs": [
                {
                    targets: 'default-inactive', visible: false
                },
                {
                    'targets': 0,
                    'checkboxes': {
                        'selectRow': true
                    },
                },
                {
                    "targets": 6,
                    "data": null,
                    "render": function ( data, type, row ) {
                        return '<a id="contact_organization_link" style="color: #3949ab; text-decoration: underline;" href="/organization/' + row[7] + '">' + row[8] + '</a>';
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
    });
    
</script>

@endsection


