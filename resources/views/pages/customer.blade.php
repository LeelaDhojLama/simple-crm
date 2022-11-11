@extends('layouts.sidebar')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Customers</div>
                    <table id="customer-detail" style="width: 100%;" class="table responsive customer-table display">

                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Contact Number</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <!-- <div class="card-title">Details</div> -->
                    <div class="customer-name" id="customer-name">Select customers from left side table</div>
                    <div class="customer-contact" id="customer-contact"></div>
                    <table class="table table-scroll responsive" style="width: 100%;" id="customer-transaction-table">
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Description</th>
                                <th scope="col">Amount</th>
                            </tr>
                        </thead>
                        <tbody style="height: 300px;overflow-y:auto;">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">

                            <div class="card-body">
                                <div class="card-title">Edit Customer</div>
                                <form>
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">@</div>
                                        </div>
                                        <input type="text" id="contact-number" class="form-control" id="inlineFormInputGroupUsername2" placeholder="Contact Number">

                                    </div>
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">@</div>
                                        </div>
                                        <input type="text" id="name" class="form-control" id="inlineFormInputGroupUsername2" placeholder="Full Name">
                                    </div>
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">@</div>
                                        </div>
                                        <input type="text" id="email" class="form-control" id="inlineFormInputGroupUsername2" placeholder="Email">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="update-customer-detail" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('script')
<script>
    var tableData
    $(document).ready(function() {

        //customers datatable
        $('#customer-detail').DataTable({
            responsive: true,
            "ajax": "/api/customers",

            "columns": [{
                    "data": "name",
                },
                {
                    "data": "contact"
                },
                {
                    "data": "email",
                },
                {
                    "targets": -1,
                    "data": null,
                    "defaultContent": '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal">' + 'Edit' + '</button>'
                }

            ]
        });

        //customers detail datatable click action
        $('#customer-detail tbody').on('click', 'tr', function() {

            tableData = $('#customer-detail').DataTable().row(this).data();
            $("#customer-name").html(tableData.name)
            $("#customer-contact").html(tableData.contact)

            $.get("/api/sales/" + tableData.id + '/all-report',
                function(data, status) {
                    $('#customer-transaction-table tbody').html("");

                    for (var i = 0; i < data.length; i++) {
                        var row = '<tr>' +
                            '<td>' + moment(data[i].created_at).format('YYYY/MM/DD') + '</td>' +
                            '<td>' + data[i].description + '</td>' +
                            '<td>' + data[i].amount + '</td>' +
                            '</tr>';
                        $('#customer-transaction-table tbody').append(row);
                    }
                });

            $('#customer-detail').on('click', 'button', function() {
                $('#name').val(tableData.name);
                $('#contact-number').val(tableData.contact);
                $('#email').val(tableData.email);
            });
        });


        $('#update-customer-detail').click(function() {

            let data = {
                "name": $('#name').val(),
                "contact": $('#contact-number').val(),
                "email": $('#email').val()
            }
            console.log(data);

            $.ajax({
                type: 'PUT',
                url: '/api/customers/' + tableData.id,
                contentType: 'application/json',
                data: JSON.stringify(data),
                beforeSend: function() {
                    $('#loader').addClass('loader');
                } // access in body
            }).done(function() {
                $('#loader').removeClass('loader');
                toastr.success('Customer data updated sucessfully', 'Customer data updated');
                $('#customer-detail').DataTable().ajax.reload();
                $('#modal').modal('hide');
            }).fail(function(msg) {
                $('#loader').removeClass('loader');
                toastr.error('Customer data not updated', 'Customer data not updated');
                console.log('FAIL');
            }).always(function(msg) {

            });
        });

    });


    // var table = $('#customer-detail').DataTable();
</script>
@stop