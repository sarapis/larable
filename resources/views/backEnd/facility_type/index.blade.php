@extends('backLayout.app')
@section('title')
Facility type
@stop

@section('content')

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Activate</label>
          <div class="col-md-8 col-sm-8 col-xs-12">
              <label>On&nbsp;&nbsp;
                <input type="checkbox" class="js-switch" value="checked" id="activate_facility_types" name="activate_facility_types" @if($layout->activate_facility_types==0) checked @endif />&nbsp;&nbsp;Off
              </label>
          </div>
      </div>
    </div>
    <div class="x_panel">
      <div class="x_title">
        <h2>FacilityTypes</h2>
        <div class="nav navbar-right panel_toolbox">
          <a href="{{route('FacilityTypes.create')}}" class="btn btn-success">New FacilityType</a>

        </div>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
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
        <table class="table table-striped jambo_table bulk_action" id="tblFacilityTypes">
          <thead>
            <tr>
              {{-- <th>Select All <input name="select_all" value="1" id="example-select-all" type="checkbox" /> --}}
              </th>
              <th>ID</th>
              <th>facility type</th>
              <th>Note</th>
              <th>Created At</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($FacilityTypes as $FacilityType)
            <tr>
              {{-- <td>{{ Form::checkbox('sel', $FacilityType->id, null, ['class' => ''])}}</td> --}}
              <td>{{$FacilityType->id}}</td>
              <td>{{$FacilityType->facility_type}}</td>
              <td>{{$FacilityType->notes}}</td>
              <td>{{$FacilityType->created_at}}</td>
              <td>
                <a href="{{route('FacilityTypes.edit', $FacilityType->id)}}" class="btn btn-primary btn-xs"><i
                    class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>


                {!! Form::open(['method'=>'DELETE', 'route' => ['FacilityTypes.destroy', $FacilityType->id],
                'style' =>
                'display:inline']) !!}
                <button type="submit" class="btn btn-danger btn-xs" data-original-title="Delete"
                  onclick="return confirm('Are you sure to delete this religion')" data-placement="top"><i
                    class='fa fa-trash'></i></button>
                {!! Form::close() !!}




              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @if (Sentinel::getUser()->hasAccess(['FacilityType.destroy']))
        <button id="delete_all" class='btn btn-danger btn-xs'>Delete Selected</button>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
  $(document).ready(function(){
        table = $('#tblFacilityTypes').DataTable({
          'columnDefs': [{
            'targets': 0,
            'searchable':false,
            'orderable':false,
          }],
          'order': [0, 'asc'],
          dom: "Blfrtip",
            buttons: [
            {
              extend: "copy",
              className: "btn-sm"
            },
            {
              extend: "csv",
              className: "btn-sm"
            },
            {
              extend: "excel",
              className: "btn-sm"
            },
            {
              extend: "pdfHtml5",
              className: "btn-sm"
            },
            {
              extend: "print",
              className: "btn-sm"
            },
            ],
            responsive: true
        });
    });
      // Handle click on "Select all" control
   $('#example-select-all').on('click', function(){
      // Check/uncheck all checkboxes in the table
      var rows = table.rows({ 'search': 'applied' }).nodes();
      $('input[type="checkbox"]', rows).prop('checked', this.checked);
   });
  $("#delete-confirm").on("click", function(){
        return confirm("Are you sure to delete this FacilityType");
    });
  // start Delete All function
  $("#delete_all").click(function(event){
        event.preventDefault();
        if (confirm("Are you sure to Delete Selected?")) {
            var value=get_Selected_id();
            if (value!='') {

                 $.ajax({
                    type: "POST",
                    cache: false,
                    url : "{{action('UserController@ajax_all')}}",
                    data: {all_id:value,action:'delete'},
                        success: function(data) {
                          location.reload()
                        }
                    })

                }else{return confirm("You have to select any item before");}
        }
        return false;
   });
  //End Delete All Function
  //Start Deactivate all Function
    $("#deactivate_all").click(function(event){
        event.preventDefault();
        if (confirm("Are you sure to Deactivate Selected ?")) {
            var value=get_Selected_id();
            if (value!='') {

                 $.ajax({
                    type: "POST",
                    cache: false,
                    url : "{{action('UserController@ajax_all')}}",
                    data: {all_id:value,action:'deactivate'},
                        success: function(data) {
                          location.reload()
                        }
                    })

                }else{return confirm("You have to select any item before");}
        }
        return false;
    });
    //End Deactivate Function
      //Start Activate all Function
    $("#activate_all").click(function(event){
        event.preventDefault();
        if (confirm("Are you sure to Activate Selected ?")) {
            var value=get_Selected_id();
            if (value!='') {

                 $.ajax({
                    type: "POST",
                    cache: false,
                    url : "{{action('UserController@ajax_all')}}",
                    data: {all_id:value,action:'activate'},
                        success: function(data) {
                          location.reload()
                        }
                    })

                }else{return confirm("You have to select any checkbox before");}
        }
        return false;
    });
    //End Activate Function


    $('.js-switch').change(function(){
        var on = $('.js-switch').prop('checked');
        if(on == true){
          $('.item input').removeAttr('disabled');
          $('.usa-state').removeAttr('disabled');
        }
        else{
          $('.item input').attr('disabled','disabled'); 
          $('.usa-state').attr('disabled','disabled');
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: "/FacilityTypes_change_activate",
            data: {
              on: on
            },
            success: function (response) {
              console.log(response)
            }, 
            error: function (data) {
              console.log(data);
            }
        });
    });
   
   function get_Selected_id() {
    var searchIDs = $("input[name=sel]:checked").map(function(){
      return $(this).val();
    }).get();
    return searchIDs;
   }
</script>
@endsection