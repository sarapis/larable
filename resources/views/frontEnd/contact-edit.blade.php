@extends('layouts.app')
@section('title')
Contact Edit
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    #contacts-edit-content {
        margin-top: 50px;
        width: 35%;
    }

    #contacts-edit-content .form-group {
        width: 100%;
    }

    button[data-id="contact_first_name"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="contact_middle_name"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="contact_last_name"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="contact_organization_name"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="contact_languages_spoken"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="contact_other_languages"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="contact_type"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="contact_organization_type"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="contact_pronouns"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    .form-group button {
        width: auto;
    }

    .form-group a {
        width: auto;
    }

    @media only screen and (max-width: 768px) {
        .form-group button {
            width: 100%;
        }

        .form-group a {
            width: 32.96%;
        }
    }

    .contact-details-div.org .dropdown.bootstrap-select.form-control {
        padding: 0 15px;
    }

    .delete-btn-div {
        text-align: center;
    }



    h1 {
        text-align: center;
    }
</style>

@section('content')
<div class="wrapper">
    <div id="contacts-edit-content" class="container">
        <h1>Edit Contact</h1>
        <div class="form-group delete-btn-div">

        </div>
        <form action="/contact/{{$contact->contact_recordid}}/update" method="GET">
            <div class="row">
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Contact Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="contact_name"
                            name="contact_name" value="{{$contact->contact_name}}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12 text-center">
                        <a href="/contacts/{{$contact->contact_recordid}}" class="btn btn-info btn-rounded"
                            id="view-contact-btn"><i class="fa fa-arrow-left"></i> Back</a>
                        <button type="button" class="btn btn-danger delete-td" id="delete-contact-btn"
                            value="{{$contact->contact_recordid}}" data-toggle="modal" data-target=".bs-delete-modal-lg"><i
                                class="fa fa-fw fa-trash"></i> Delete</button>
                        <button type="submit" class="btn btn-success btn-rounded" id="save-contact-btn"><i
                                class="fa fa-check"></i> Save</button>
                    </div>

                </div>
            </div>
        </form>
        <div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="/contact_delete_filter" method="POST" id="contact_delete_filter">
                        {!! Form::token() !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">Delete Contact</h4>
                        </div>
                        <div class="modal-body">

                            <input type="hidden" id="contact_recordid" name="contact_recordid">
                            <h4>Are you sure to delete this contact?</h4>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger btn-delete">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('button.delete-td').on('click', function() {
        var value = $(this).val();
        $('input#contact_recordid').val(value);
    });
</script>
@endsection