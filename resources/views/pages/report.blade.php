@extends('layouts.sidebar')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Weekly Sales Report</div>
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Sales</div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="input-group mb-2 mr-sm-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                </div>
                                <input type="text" id="starting-date" class="form-control datepicker" id="datepicker" placeholder="Starting Date">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group mb-2 mr-sm-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                </div>
                                <input type="text" id="ending-date" class="form-control datepicker" id="datepicker" placeholder="End Date">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <button class="btn btn-primary fluid" id="generate-sales-report">Generate</button>
                        </div>
                    </div>
                    <div>
                        <table id="transaction-table" class="table table-scroll sales-history-table">
                            <thead>
                                <tr>
                                    <th scope="col">S.N.</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Customer Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Amount</th>
                                </tr>
                            </thead>
                            <tbody style="height: 136px;overflow-y:auto;">
                            </tbody>
                        </table>
                        <div style="float:right;" id="total-amount">Total Amount</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="card">
            <div class="card-body">
                <div class="card-title">Create Offer</div>
                
            </div>
        </div> -->
    </div>
    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Offers Claimed</div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="input-group mb-2 mr-sm-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                </div>
                                <input type="text" id="offer-starting-date" class="form-control datepicker" id="datepicker" placeholder="Starting Date">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group mb-2 mr-sm-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                </div>
                                <input type="text" id="offer-ending-date" class="form-control datepicker" id="datepicker" placeholder="End Date">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <button class="btn btn-primary fluid" id="generate-offer-report">Generate</button>
                        </div>
                    </div>
                    <div>
                        <table id="offer-table" class="table table-scroll sales-history-table">
                            <thead>
                                <tr>
                                    <th scope="col">S.N.</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Customer Name</th>
                                    <th scope="col">Offer</th>
                                </tr>
                            </thead>
                            <tbody style="height: 136px;overflow-y:auto;">
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('script')
<script>
    $.ajax({
        type: 'GET',
        url: '/api/sales/reports/weekly-report/',
        contentType: 'application/json',
    }).done(function(data) {
        var currDate = moment(moment().subtract(7, 'days').format("YYYY-MM-DD")).startOf('day');
        var lastDate = moment(moment().format("YYYY-MM-DD")).startOf('day');
        var days = [];
        var amount = []

        while (currDate.add(1, 'days').diff(lastDate) <= 0) {
            var dataFound = false;
            for (var i = 0; i < data.length; i++) {

                if (moment(currDate).format("YYYY-MM-DD") == data[i].day) {
                    days.push(currDate.format('dddd'));
                    amount.push(data[i].amount);
                    dataFound = true;
                    break;
                }
            }

            if (!dataFound) {

                console.log("data not found");
                days.push(currDate.format('dddd'));
                amount.push(0);

            }

        }
        console.log(amount);
        loadLineGraph(amount, days);

    }).fail(function(msg) {
        console.log('FAIL');
    }).always(function(msg) {

    });

    function loadLineGraph(data, labels) {
        var ctx = document.getElementById('myChart');
        var chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'line',

            // The data for our dataset
            data: {
                labels: labels,
                datasets: [{
                    fill: false,
                    label: 'Sales Amount',
                    // backgroundColor: 'rgb(255, 99, 132)',
                    borderColor: 'rgb(255, 99, 132)',
                    data: data
                }]
            },

            // Configuration options go here
            // options: {
            //     title: {
            //         display: true,
            //         text: 'World population per region (in millions)'
            //     }
            // }
        });
    }

    $('#generate-sales-report').click(function() {
        loadSalesReport($('#starting-date').val(), $('#ending-date').val());
    });
    $('#generate-offer-report').click(function() {
        loadOfferClaimedReport($('#offer-starting-date').val(), $('#offer-ending-date').val());
    });

    function loadSalesReport(startDate, endDate) {
        $.ajax({
            type: 'GET',
            url: '/api/sales/reports/custom-report/' + startDate + '/' + endDate,
            contentType: 'application/json',
        }).done(function(data) {
            console.log(data);
            for (var i = 0; i < data.sales_report.length; i++) {
                var row = '<tr>' +
                    '<td>' + (i + 1) + '</td>' +
                    '<td>' + moment(data.sales_report[i].created_at).format('YYYY/MM/DD') + '</td>' +
                    '<td>' + data.sales_report[i].name + '</td>' +
                    '<td>' + data.sales_report[i].description + '</td>' +
                    '<td>' + data.sales_report[i].amount + '</td>' +
                    '</tr>';
                $('#transaction-table').append(row);
            }
            $('#total-amount').html("Total Amount: " + data.total_amount);
        }).fail(function(msg) {
            console.log('FAIL');
        }).always(function(msg) {

        });
    }

    function loadOfferClaimedReport(startDate, endDate) {
        $.ajax({
            type: 'GET',
            url: '/api/offer/reports/custom-offer-claims/' + startDate + '/' + endDate,
            contentType: 'application/json',
        }).done(function(data) {
            console.log(data);
            for (var i = 0; i < data.length; i++) {
                var row = '<tr>' +
                    '<td>' + (i + 1) + '</td>' +
                    '<td>' + moment(data[i].created_at).format('YYYY/MM/DD') + '</td>' +
                    '<td>' + data[i].name + '</td>' +
                    '<td>' + data[i].title + '</td>' +
                    '</tr>';
                $('#offer-table').append(row);
            }
            $('#total-amount').html("Total Amount: " + data.total_amount);
        }).fail(function(msg) {
            console.log('FAIL');
        }).always(function(msg) {

        });
    }

    $("#starting-date").datepicker({
        dateFormat: "yy-mm-dd",
        duration: "fast"
    });
    $("#ending-date").datepicker({
        dateFormat: "yy-mm-dd",
        duration: "fast"
    });
    $("#offer-starting-date").datepicker({
        dateFormat: "yy-mm-dd",
        duration: "fast"
    });
    $("#offer-ending-date").datepicker({
        dateFormat: "yy-mm-dd",
        duration: "fast"
    });
</script>
@stop