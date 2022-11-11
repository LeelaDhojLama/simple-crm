@extends('layouts.sidebar')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-5">
            <div class="card">

                <div class="card-body">
                    <div class="card-title">Sales Entry</div>
                    <form id="sales-form">
                        <div class="input-group mb-2 mr-sm-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-phone-alt"></i></div>
                            </div>
                            <input type="text" id="contact-number" class="form-control" placeholder="Contact Number" required="Please enter contact number">
                            <small id="message" class="form-text col-sm-12"></small>
                        </div>
                        <div class="input-group mb-2 mr-sm-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-user"></i></div>
                            </div>
                            <input type="text" id="name" class="form-control" placeholder="Full Name" required="Please enter full name">
                        </div>
                        <div class="input-group mb-2 mr-sm-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-envelope"></i></div>
                            </div>
                            <input type="text" id="email" class="form-control" placeholder="Email" required="Please enter email">
                        </div>
                        <div class="input-group mb-2 mr-sm-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Rs</div>
                            </div>
                            <input type="number" class="form-control" id="amount" placeholder="Amount" required="Please enter amount of sell">
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" id="description" placeholder="Description" rows="6" required="Please enter description about selle"></textarea>
                        </div>
                        <div class="text-right">
                            <button type="submit" id="add-sales" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Sales History</div>
                    <table id="transaction-table" class="table table-scroll">
                        <thead>
                            <tr>
                                <th scope="col">Count</th>
                                <th scope="col">Date</th>
                                <th scope="col">Amount</th>
                            </tr>
                        </thead>
                        <tbody style="height: 285px;overflow-y:auto;">
                        </tbody>
                    </table>
                    <button id="claim-offer" class="btn disabled btn-primary btn-block">Claim Offer</button>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('script')
<script>
    var customerData;
    var isCustomerFound = false;
    var salesId;
    $('#contact-number').on('input', function(e) {
        var input = $(this);
        if (input.val().length == 10) {
            $.ajax({
                type: 'GET',
                url: '/api/customers/' + input.val(),
                contentType: 'application/json',
                beforeSend: function() {
                    $("#loader").addClass('loader');
                },
            }).done(function(data) {
                console.log(data);
                $("#loader").removeClass('loader');
                if (!jQuery.isEmptyObject(data)) {
                    $('#message').html("Customer Found!!");
                    $('#message').addClass('found')
                    $('#message').removeClass('not-found')
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    id = data.id
                    isCustomerFound = true;
                    customerData = data;
                    loadSalesHistory(false);
                } else {
                    isCustomerFound = false;
                    $('#message').html("New Customer!!");
                    $('#message').addClass('not-found')
                    $('#message').removeClass('found')
                    $('#name').val('');
                    $('#email').val('');
                }
            }).fail(function(msg) {
                $("#loader").removeClass('loader');
                console.log('FAIL');
                isCustomerFound = false;
                $('#message').html("New Customer!!");
                $('#message').addClass('not-found')
            }).always(function(msg) {

            });
        } else {
            $('#message').html("");
            $('#name').val('');
            $('#email').val('');
            // $('#message').addClass('not-found')
            $('#transaction-table tbody').html('');
        }
    });

    // $('#claim-offer').addClass('disabled')
    $('#claim-offer').click(function() {

        claimOffer();
    });

    function claimOffer() {
        if (customerData == null) {
            $.alert({
                title: "sorry",
                content: 'Sorry no user selected!',
            });
        } else {
            $.ajax({
                type: 'GET',
                url: '/api/customer/' + customerData.id + '/offers/active',
                contentType: 'application/json',
                beforeSend: function() {
                    $("#loader").addClass('loader');
                },

            }).done(function(data) {
                $('#loader').removeClass('loader')
                console.log(data);
                $.confirm({
                    title: data.title + ' !!',
                    content: 'Customer is liable for ' + data.title +
                        ' offer. Are you sure to claim?' +
                        '<p style="margin-buttom:0;">Minimu Purchase: ' + data.minimum_time + '</p>' +
                        '<p>Minimum Amount:' + data.minimum_amount + '</p>',
                    buttons: {
                        confirm: {
                            btnClass: 'btn-blue',
                            action: function() {

                                let offerClaimData = {
                                    "sales_id": salesId,
                                    "customer_id": customerData.id,
                                    "offer_id": data.id
                                }
                                $.ajax({
                                    type: 'POST',
                                    url: '/api/offers/claim',
                                    contentType: 'application/json',
                                    data: JSON.stringify(offerClaimData)
                                }).done(function(data) {

                                    toastr.success("offer claimed for customer", 'Offer claimed')
                                    loadSalesHistory(true);

                                }).fail(function(msg) {
                                    toastr.error('Offer not claimed for customer', 'Sorry not claimed')
                                });
                            }
                        },
                        cancel: {
                            btnClass: 'btn-danger',
                            action: function() {

                            }
                        }
                    }
                });
            }).fail(function(msg) {
                toastr.error('Failed to get offer for customer', 'Sorry offer not loaded')
            });

        }
    }


    $('#sales-form').submit(function(e) {
        e.preventDefault();
        if (isCustomerFound) {
            addNewSale(customerData)
        } else {
            addNewCustomer();
        }

    });

    function addNewCustomer() {

        let customerData = {
            "contact": $("#contact-number").val(),
            "name": $("#name").val(),
            "email": $("#email").val()
        }
        $.ajax({
            type: 'POST',
            url: '/api/customers/',
            contentType: 'application/json',
            data: JSON.stringify(customerData),
            beforeSend: function() {
                $("#loader").addClass('loader');
            }
        }).done(function(data) {
            $("#loader").removeClass('loader');
            addNewSale(data);
            toastr.success("New customer added", 'Customer Added')
        }).fail(function(msg) {
            $("#loader").removeClass('loader');
            toastr.error("Sorry customer not added", 'Sorry customer not added')
        });
    }

    function addNewSale(data) {
        let saleData = {
            "customer_id": data.id,
            "description": $("#description").val(),
            "amount": $("#amount").val(),
            "email": data.email
        }
        $.ajax({
            type: 'POST',
            url: '/api/sales/',
            contentType: 'application/json',
            data: JSON.stringify(saleData),
            beforeSend: function() {
                $("#loader").addClass('loader');
            },
        }).done(function(data) {
            $("#loader").removeClass('loader');
            toastr.success('Sales data is added and mail send to customer', 'Sales added')
            loadSalesHistory(true)
        }).fail(function(msg) {
            $("#loader").removeClass('loader');
            toastr.error('Sales data is not added and failed to send mail to customer', 'Sales not added')
        }).always(function(msg) {

        });
    }

    function loadSalesHistory(isLoadedFromButtonClick) {
        $.ajax({
            type: 'GET',
            url: "/api/sales/" + customerData.id,
            contentType: 'application/json',
            beforeSend: function() {
                $("#loader").addClass('loader');
            },
        }).done(function(data, status) {
            $("#loader").removeClass('loader');
            if (data.sales_history.length > 0) {
                $('#transaction-table tbody').html('');
                for (var i = 0; i < data.sales_history.length; i++) {
                    var row = '<tr>' +
                        '<td>' + (i + 1) + '</td>' +
                        '<td>' + moment(data.sales_history[i].created_at).format('YYYY/MM/DD') + '</td>' +
                        '<td>' + data.sales_history[i].amount + '</td>' +
                        '</tr>';
                    $('#transaction-table tbody').append(row);

                    if (i == data.sales_history.length - 1) {
                        console.log("data.sales_history[i].id");
                        salesId = data.sales_history[i].id;
                    }
                }

                if (data.offer_status) {
                    $('#claim-offer').removeClass('disabled')
                    if (isLoadedFromButtonClick) {
                        claimOffer();
                    }
                } else {
                    $('#claim-offer').addClass('disabled')
                }
            }
            if (data.is_offer_active) {
                if (data.is_offered_claimed) {
                    var row = '<tr>' +
                        '<td> Recent offers already claimed </td>' +
                        '</tr>';
                    $('#transaction-table tbody').append(row);
                    // $('#claim-offer').addClass('disabled')
                }
            } else {
                $('#claim-offer').addClass('disabled')
            }

        });
    }
</script>
@stop