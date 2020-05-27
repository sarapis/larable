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

    .dropdown-menu.show {
        width: 100% !important;
    }

    button[data-id="contact_organization"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="contact_services"] {
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
                    <label class="control-label sel-label-org pl-4">Organization: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <select class="form-control selectpicker" data-live-search="true" id="contact_organization"
                            name="contact_organization" data-size="5" required>
                            @foreach($organization_info_list as $key => $org_info)
                            <option value="{{$org_info->organization_recordid}}" @if($org_info->organization_recordid == $contact->contact_organizations) selected @endif>{{$org_info->organization_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Services: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <select class="form-control selectpicker" multiple data-live-search="true" id="contact_services"
                            name="contact_services[]" data-size="5">
                            @foreach($service_info_list as $key => $service_info)
                            <option value="{{$service_info->service_recordid}}" @if(in_array($service_info->service_recordid, $contact_service_recordid_list)) selected @endif>{{$service_info->service_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Contact Title: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="contact_title"
                            name="contact_title" value="{{$contact->contact_title}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Contact Department: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="contact_department"
                            name="contact_department" value="{{$contact->contact_department}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Contact Email: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="email" id="contact_email"
                            name="contact_email" value="{{$contact->contact_email}}">
                    </div>
                </div>
                
                
                <!-- <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Phone: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="contact_cell_phones"
                            name="contact_cell_phones" @if ($contact_phone) value="{{$contact_phone->phone_number}}" @endif>
                        <p id="error_cell_phone" style="font-style: italic; color: red;">Invalid phone number! Example: +39 422 789611, 0422-78961, (042)589-6000, +39 (0422)7896, 0422 (789611), 39 422/789 611 </p>
                    </div>
                </div> -->

                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Phones: </label>
                    <a id="add-phone-input">
                        <span class="glyphicon glyphicon-plus-sign"></span>
                    </a>
                    <ol id="phones-ul">
                        @foreach($contact->phone as $phone)
                            @if ($phone->phone_number)
                            <li class="contact-phones-li mb-2">
                                <div class="col-md-12 col-sm-12 col-xs-12 contact-phones-div">
                                    <input class="form-control selectpicker contact_phones"  type="text" name="contact_phones[]" value="{{$phone->phone_number}}">
                                </div> 
                            </li> 
                            @endif
                        @endforeach
                    </ol>
                </div>
               <!--  <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Phone Extension: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="contact_phone_extension" name="contact_phone_extension"
                            value="{{$contact->contact_phone_extension}}">
                    </div>
                </div> -->

                <div class="form-group">
                    <div class="col-md-12 text-center">
                        <a href="/contact/{{$contact->contact_recordid}}" class="btn btn-info btn-rounded"
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
    // $(document).ready(function(){
    //     $('#error_cell_phone').hide();
    //     $("#contacts-edit-content").submit(function(event){
    //         // var mob = /^((\+)?[1-9]{1,2})?([-\s\.])?((\(\d{1,4}\))|\d{1,4})(([-\s\.])?[0-9]{1,12})$/;
    //         var mob = /^(?!.*([\(\)\-\/]{2,}|\([^\)]+$|^[^\(]+\)|\([^\)]+\(|\s{2,}).*)\+?([\-\s\(\)\/]*\d){9,15}[\s\(\)]*$/;
    //         var contact_cell_phones_value = $("#contact_cell_phones").val();
    //         if (contact_cell_phones_value != ''){
    //             if(mob.test(contact_cell_phones_value) == false && contact_cell_phones_value != 10){ 
    //                 $('#error_cell_phone').show();              
    //                 event.preventDefault();
    //             } 
    //         }
           
    //     });
    // });
    $("#add-phone-input").click(function(){
        $("ol#phones-ul").append(
            "<li class='contact-phones-li mb-2'>"
          + "<div class='col-md-12 col-sm-12 col-xs-12 contact-phones-div'>"
          + "<input class='form-control selectpicker contact_phones'  type='text' name='contact_phones[]'>"
          + "</div>"
          + "</li>" );
    });
</script>
@endsection