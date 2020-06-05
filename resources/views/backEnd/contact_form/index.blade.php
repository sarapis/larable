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
        <table id="suggest_table" class="display nowrap table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%">
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

  <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
    <div class="x_panel" style="width: 50%;">
      <div class="x_title" style="text-align: center;">
        <h4>Forward submission to the following email addresses:</h4>
        <div class="clearfix"></div>  
      </div>
      <div class="x_content" style="overflow: scroll;">

        <table id="email_table" class="display nowrap table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                	<th class="text-center">No</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Actions</th>             
                </tr>
            </thead>
            <tbody>
            @if ($emails)
              	@foreach($emails as $key => $email)
	                <tr id="suggest{{$suggest->suggest_recordid}}">
	                  <td class="text-center">{{$key+1}}</td>
	                  <td class="text-center">{{$email->email_info}}</td>
	                  <td class="text-center">
	                    <button id="btn_delete" class="btn btn-block btn-danger btn-sm open_modal" style="width: 100%;" data-toggle="modal" data-target=".bs-delete-modal-lg" style="color:#fb0c2df7;"><i class="fa fa-fw fa-trash"></i>Delete</button>
	                  </td>
	                </tr>
	            @endforeach  
	        @endif           
            </tbody>
        </table>
      </div>
    </div>
  </div>

  	<div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="/email_delete_filter" method="POST" id="email_delete_filter">
                    {!! Form::token() !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Delete Email</h4>
                    </div>
                    <div class="modal-body">
                        
                        <input type="hidden" id="email_recordid" name="email_recordid">
                        <h4>Are you sure to delete this email?</h4>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        <button type="submit" id="email_delete_btn" class="btn btn-danger btn-delete" value="{{$email->email_recordid}}">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')

<script type="text/javascript">
$(document).ready(function() {

    $('#suggest_table').DataTable( {
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

    $('#email_table').DataTable( {
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

    $('#btn_delete').on('click', function(e) {
        e.preventDefault();
    });

    $('#email_delete_btn').on('click', function(e) {
        e.preventDefault();
        var value = $(this).val();
        console.log(value);
        $('input#email_recordid').val(value);
        $('#email_delete_filter').submit();
    });



} );
</script>
@endsection
