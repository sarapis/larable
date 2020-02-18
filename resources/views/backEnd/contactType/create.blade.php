@extends('backLayout.app')
@section('title')
create contact type
@stop

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">Create new contact type</div>

    <div class="panel-body">

        {!! Form::open(['route' => 'ContactTypes.store', 'class' => 'form-horizontal']) !!}

        <div class="form-group {{ $errors->has('contact_type') ? 'has-error' : ''}}">
            {!! Form::label('contact_type', 'Contact type', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('contact_type', null, ['class' => 'form-control']) !!}
                {!! $errors->first('contact_type', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('notes') ? 'has-error' : ''}}">
            {!! Form::label('notes', 'Notes', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::textarea('notes', null, ['class' => 'form-control']) !!}
                {!! $errors->first('notes', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-3">
                {!! Form::submit('Submit', ['class' => 'btn btn-success form-control']) !!}
            </div>
            <a href="{{route('ContactTypes.index')}}" class="btn btn-default">Back</a>
        </div>
    </div>
</div>
{!! Form::close() !!}

@endsection