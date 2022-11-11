@extends('layouts.sidebar')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-7 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Sales</div>
                    <table id="customer-detail" style="width: 100%;" class="table customer-table display responsive">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer Name/Contact Number</th>
                                <th>Description</th>
                                <th>Current/Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-5 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card-title">Offers</div>
                        </div>
                        <div class="col-sm-6 text-right">
                            <button type="button" id="model-open-button" class="btn btn-primary" data-toggle="modal" data-target="#modal">
                                Add Offers
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <table style="width: 100%;" class="table customer-table display responsive" id="offers-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Title</th>
                                        <th scope="col">Valid Till</th>
                                        <th scope="col">Min. Amount</th>
                                        <th scope="col">Total Recurrence</th>
                                        <!-- <th scope="col">Action</th> -->

                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- <div class="card">
            <div class="card-body">
                <div class="card-title">Create Offer</div>
                
            </div>
        </div> -->
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Offers</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="message"></div>
                        <form>
                            <div class="input-group mb-2 mr-sm-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fas fa-info"></i></div>
                                </div>
                                <input type="text" class="form-control" id="title" placeholder="Title">
                            </div>
                            <div class="row">
                                <div class="col-sm-6" style="padding-right: 0;">
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-shopping-cart"></i></div>
                                        </div>
                                        <input type="text" class="form-control" id="minimum_time" placeholder="Minimum Purchase">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Rs</div>
                                        </div>
                                        <input type="text" class="form-control" id="minimum_amount" placeholder="Minimum Amount">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6" style="padding-right: 0;">
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                        </div>
                                        <input type="text" id="datepicker" class="form-control datepicker" id="datepicker" placeholder="Valid Date">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer" id="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('script')
<script>
    $(document).ready(function() {
        $('#customer-detail').DataTable({
            // "processing": true,
            // "serverSide": true,
            responsive: true,
            "ajax": "/api/sales",
            "columns": [{
                    "data": "created_at",
                    render: function(data, type, row) {

                        return moment(data).format('YYYY/MM/DD')
                    }
                },
                {
                    "data": "name",
                    render: function(data, type, row) {

                        return '<h5>' + data + '</h5>' + '<p>' + 'Contact: ' + row.contact + '</p>';
                    }
                },
                {
                    "data": "description"
                },
                {
                    "data": "amount",
                    render: function(data, type, row) {

                        return '<h5 style="text-align:right">Rs.' + data + '</h5>' + '<p style="text-align:right">' + 'Total Amount: Rs.' + row.total_amount + '</p>';
                    }
                },
            ]
        });

        $('#offers-table').DataTable({
            responsive: true,
            "ajax": "/api/offers",
            "columns": [{
                    "data": "title",
                },
                {
                    "data": "validity",
                    render: function(data, type, row) {

                        return moment(data).format('YYYY/MM/DD');
                    }
                },
                {
                    "data": "minimum_amount"
                },
                {
                    "data": "minimum_time"
                },
            ]
        });

        $('#model-open-button').click(function() {
            $('#title').val('');
            $('#minimum_amount').val('');
            $('#minimum_time').val('');
            $('#datepicker').val('');
            $('#modal-footer').html('<button type="button" id="offer-button" class="btn btn-primary"> Add Offers </button>');
            $('#offer-button').click(function() {
                let data = {
                    "title": $("#title").val(),
                    "minimum_amount": $("#minimum_amount").val(),
                    "minimum_time": $("#minimum_time").val(),
                    "validity": $("#datepicker").val()
                }
                $.ajax({
                    type: 'POST',
                    url: '/api/offers/',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    beforeSend: function() {
                        $("#loader").addClass('loader');
                    } // access in body
                }).done(function() {
                    $('#offers-table').DataTable().ajax.reload();
                    $('#modal').modal('hide');
                    $("#loader").removeClass('loader');
                    toastr.success('Successfully Offer Created', 'Offer added')
                }).fail(function(msg) {
                    $("#loader").removeClass('loader');
                    toastr.error(msg.responseJSON.data, 'Sorry offer not added')
                    $("#message").html('<div class="alert alert-danger" role="alert">' + msg.responseJSON.data + '</div>');
                }).always(function(msg) {

                });
            })
        });

        $('#offers-table tbody').on('click', 'tr', function() {

            tableData = $('#offers-table ').DataTable().row(this).data();

            $('#title').val(tableData.title);
            $('#minimum_amount').val(tableData.minimum_amount);
            $('#minimum_time').val(tableData.minimum_time);
            $('#datepicker').val(tableData.validity);
            $('#modal').modal('show');

            $('#modal-footer').html('<button type="button" id="offer-update-button" class="btn btn-primary"> Update Offers </button>');

            $('#offer-update-button').click(function() {
                let data = {
                    "title": $("#title").val(),
                    "minimum_amount": $("#minimum_amount").val(),
                    "minimum_time": $("#minimum_time").val(),
                    "validity": $("#datepicker").val()
                }
                $.ajax({
                    type: 'PUT',
                    url: '/api/offers/' + tableData.id,
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    beforeSend: function() {
                        $("#loader").addClass('loader');
                    } // access in body
                }).done(function() {
                    $("#loader").removeClass('loader');
                    $('#offers-table').DataTable().ajax.reload();
                    $('#modal').modal('hide');
                    toastr.success('Offer data updated sucessfully', 'Sorry offer not updated');
                }).fail(function(msg) {
                    $("#loader").removeClass('loader');
                    toastr.error(msg.responseJSON.data, 'Sorry offer not updated')
                    $("#message").html('<div class="alert alert-danger" role="alert">' + msg.responseJSON.data + '</div>');
                }).always(function(msg) {

                });
            })
        });

        $("#datepicker").datepicker({
            dateFormat: "yy-mm-dd",
            duration: "fast",
            minDate: new Date(),
        });

    });
</script>
@stop