@extends('backLayout.app')
@section('title')
Edit User
@stop

@section('content')
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

<style type="text/css">  
    .dropdown-menu.open {
        max-height: 156px;
        overflow: hidden;        
        width: 100%;
    }
</style>

<div class="panel panel-default">
   <div class="panel-heading">Edit user: {{$user->name}}</div>

     <div class="panel-body">                

    {{ Form::model($user, array('method' => 'PATCH', 'url' => route('user.update', $user->id), 'class' => 'form-horizontal', 'files' => true)) }}
      <ul>
            <div class="form-group {{ $errors->has('first_name') ? 'has-error' : ''}}">
                 {!! Form::label('first_name', 'First Name', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('first_name', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
           
           <div class="form-group {{ $errors->has('last_name') ? 'has-error' : ''}}">
                 {!! Form::label('last_name', 'Last name' , ['class' => 'col-md-4 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('last_name', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
                 {!! Form::label('email', 'Email', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('email', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('new_password') ? 'has-error' : ''}}">
                 {!! Form::label('new_password', 'Password', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::password('new_password', ['class' => 'form-control']) !!}
                    {!! $errors->first('new_password', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('new_password_confirmation') ? 'has-error' : ''}}">
                 {!! Form::label('new_password_confirmation', 'Password Confirmation', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::password('new_password_confirmation', ['class' => 'form-control']) !!}
                    {!! $errors->first('new_password_confirmation', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <!-- <div id="role" class="form-group {{ $errors->has('role') ? 'has-error' : ''}}">
                 {!! Form::label('role','User role', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::select('role', $roles, null, ['class' => 'form-control']) !!}
                    {!! $errors->first('role', '<p class="help-block">:message</p>') !!}
                </div>
            </div> -->

            <div id="role" class="form-group {{ $errors->has('role') ? 'has-error' : ''}}">
                 {!! Form::label('role','User role', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-sm-6">
                    <select class="form-control selectpicker" id="role" name="role">
                        @foreach($role_info_list as $key => $role_info)                                
                            <option value="{{$role_info->id}}" @if ($role_info->name == $user->roles[0]->name) selected @endif>{{$role_info->name}}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('role', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div id="organization" class="form-group {{ $errors->has('role') ? 'has-error' : ''}}">
                 {!! Form::label('organization','Organizations', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-sm-6">
                    <select class="form-control selectpicker" multiple data-live-search="true" data-size="5" id="user_organizations" name="user_organizations[]">
                        @foreach($organization_list as $key => $organization)                                
                            <option value="{{$organization->organization_recordid}}" @if (in_array($organization->organization_recordid, $account_organization_list)) selected @endif>{{$organization->organization_name}}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('organization', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
           
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-3">
                    {!! Form::submit('Submit', ['class' => 'btn btn-success form-control']) !!}
                </div>
                <a href="{{route('user.index')}}" class="btn btn-default">Return to all users</a>
            </div>
           

        </ul>
    {{ Form::close() }}
    </div>
    </div>


@stop

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script> 
    $(document).ready(function() {
        $("#user_organizations").selectpicker("");
        $("#role").selectpicker("");
    }); 

</script>
@endsection