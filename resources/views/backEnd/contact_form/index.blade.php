@extends('backLayout.app')
@section('title')
Contact Form
@stop
<style>
    tr.modified{
        background-color: red !important;
    }

    tr.modified > td{
        background-color: red !important;
        color: white;
    }
</style>
@section('content')

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Contact Form</h2>
        <div class="clearfix"></div>  
      </div>
      <div class="x_content" style="overflow: scroll;">

        <!-- <table class="table table-striped jambo_table bulk_action table-responsive"> -->
        <table id="example" class="display nowrap table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                	<th class="text-center">No</th>
                    <th class="text-center">Timestamp</th>
                    <th class="text-center">Organization</th>                   
                    <th class="text-center">Message</th>                   
                    <th class="text-center">Name</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Phone</th>             
                </tr>
            </thead>
            <tbody>
              @foreach($suggests as $key => $suggest)
                <tr id="suggest{{$suggest->suggest_recordid}}">
                  <td class="text-center">{{$key+1}}</td>
                  <td class="text-center">{{$suggest->suggest_created_at}}</td>
                  <td class="text-center">@if($suggest->suggest_organization)
                    <span class="badge bg-blue">{{$suggest->organization->organization_name}}</span>
                  @endif
                  </td>
                  <td><span style="white-space:normal;">{{$suggest->suggest_content}}</span></td>
                  <td class="text-center">{{$suggest->suggest_username}}</td>
                  <td class="text-center">{{$suggest->suggest_user_email}}</td>
                  <td class="text-center">{{$suggest->suggest_user_phone}}</td>
                </tr>
              @endforeach             
            </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')

<script type="text/javascript">
$(document).ready(function() {
    $('#example').DataTable( {
        responsive: {
            details: {
                renderer: function ( api, rowIdx, columns ) {
                    var data = $.map( columns, function ( col, i ) {
                        return col.hidden ?
                            '<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
                                '<td>'+col.title+':'+'</td> '+
                                '<td>'+col.data+'</td>'+
                            '</tr>' :
                            '';
                    } ).join('');
 
                    return data ?
                        $('<table/>').append( data ) :
                        false;
                }
            }
        },
        "paging": true,
        "pageLength": 20,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": false
    } );
} );
</script>
<script src="{{asset('js/detail_ajaxscript.js')}}"></script>
@endsection
