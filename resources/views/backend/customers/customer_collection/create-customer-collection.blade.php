@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <link href="{{ asset('backend/assets/vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
    @endpush
    <!-- BEGIN: Page heading-->
    <div class="page-heading">
        <div class="page-breadcrumb">
            <h1 class="page-title page-title-sep"> Customer Collection Form</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Branch</li>
                <li class="breadcrumb-item">Customer Collection</li>
            </ol>
        </div>
        <div>
            <a href="/admin/customer/customer-collection">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i> Customers List</button>
            </a>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="card">
            <div class="card-body">
                <h5>Customer Details</h5>
                <hr>
                <div class="row">
                    <div class="col-sm-3 form-group mb-4">
                        <label>Customer ID </label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>{{ strtoupper($customerSchemeDetails->customer->customer_id) }}</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>Customer Name </label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>{{ strtoupper($customerSchemeDetails->customer->name) }}</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>Phone </label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>{{ $customerSchemeDetails->customer->phone }}</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>Place </label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>{{ strtoupper($customerSchemeDetails->customer->place) }}</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>Branch Name</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        @php
                            $branch = $customerSchemeDetails->branch;
                        @endphp
                        <label>{{ $branch->branch_id ?? '' }}-{{ strtoupper($branch->branch_name ?? '') ??
                        '' }}</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>Scheme Name</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        @php

                        @endphp
                        <label>{{ $customerSchemeDetails->scheme->scheme_a_id.'-'.$customerSchemeDetails->scheme->scheme_n_id }}-{{ strtoupper($customerSchemeDetails->scheme->name) }}</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>Total Scheme Amount</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>{{ $customerSchemeDetails->scheme->total_amount }}</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>Total Amount Paid</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>{{ $customerSchemeDetails->total_amount }}</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>Pending Amount</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>{{ $customerSchemeDetails->scheme->total_amount - $customerSchemeDetails->total_amount }}</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>Executive</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>{{ $executive == null ? ' ' : $executive->executive->name }}</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>Collection Day</label>
                    </div>

                    <div class="col-sm-3 form-group mb-4">
                        <label>{{ $customerSchemeDetails->collection_day }}</label>
                    </div>
                    @isset($customerSchemeDetails->last_paid_date )
                    <div class="col-sm-3 form-group mb-4">
                        <label>Last paid Date</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>{{ ($customerSchemeDetails->last_paid_date == null ? "" :$customerSchemeDetails->last_paid_date)  }}</label>
                    </div>
                    @endisset
                    <div class="col-sm-3 form-group mb-4">
                        <label>Joined By</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>{{ ($customerSchemeDetails->executive == null ? "" : $customerSchemeDetails->executive->name)  }}</label>
                    </div>
                    @isset($customerSchemeDetails->completed_date )
                    <div class="col-sm-3 form-group mb-4">
                        <label>Completed Date</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>{{ ($customerSchemeDetails->completed_date == null ? "" :$customerSchemeDetails->completed_date)  }}</label>
                    </div>
                    @endisset

                </div>
                <div class="table-responsive">
                    <h5>Weekly Paid Details</h5>
                    <button id="show" class="btn btn-secondary" onclick="onClickShow()">Show</button><button
                        class="btn btn-secondary" id="hide" onclick="onClickHide()">Hide</button>
                    <hr>
                    <div id="paid_details_table">
                        <table class="table table-bordered w-100 data-table" id="dt-filter">
                            <thead class="thead-light">
                                <tr>
                                    <th>Week No:</th>
                                    <th>Paid Week</th>
                                    <th>Paid Amount</th>
                                    <th>Due Amount </th>
                                    <th>Paid Date</th>
                                </tr>
                            </thead>
                            @foreach ($schemeDetails as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if ($item->paid_week == 1)
                                            {{ $customerSchemeDetails->scheme->start_date }}
                                        @else
                                            @php
                                                   $days = ($item->paid_week - 1) * 7;
                                                $date = strtotime($customerSchemeDetails->scheme->start_date);
                                                $date = strtotime("+$days day", $date);
                                                $date = date('Y-m-d', $date);
                                            @endphp
                                            {{ $date }}
                                        @endif
                                    </td>
                                    <td>{{ $item->paid_amount }}</td>
                                    <td>{{ $item->due_amount }}</td>
                                    <td>{{ $item->paid_date }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                <h5>Payement</h5>
                <hr>
                <div class="row">
                    @if ($customerSchemeDetails->status == 0 || $customerSchemeDetails->status == 1)
                        <form action="/admin/customer/customer-collection/{{ $customerSchemeDetails->id }}"
                            method="PATCH" id="customer-form">
                            @method('PATCH')
                            @csrf
                            <div id="payNow">


                            <div class="col-sm-12 form-group mb-6" id="payNow">

                                 &nbsp;
                                    <button class=" btn btn-success mr-4" type="submit"  onclick="AmountClick();">Pay 200</button> <br>
                                    <br>
                                    -OR- <br>
                                        <label for="">Enter an amount to pay</label>
                                        <br>
                                <input style="    width: 56%;" class="form-control" type="text"  id="amount" oninput ="amountChange()">
                                <input class="form-control" type="text" name="amount" id="amount1" hidden>
                                <sub class="text-danger error-text amount" for="amount"></sub>
                            </div>
                            <div class="col-sm-6 form-group mb-4">
                                <button class="submit btn btn-primary mr-4" id="submitForm" type="submit">Pay</button>
                            </div>
                            <div class="col-sm-6 form-group mb-4">
                                <button class="submit btn btn-danger mr-4" id="payLater" type="button" >Pay Later <br></button>
                            </div>
                        </form>
                    </div>
                        <div class="col-sm-6 form-group mb-4">

                            <div id="payLaterID">
                                <div class="col-sm-6 form-group mb-4">

                                </div>
                                <form action="/admin/customer/customer-collection/{{ $customerSchemeDetails->id }}" method="PATCH" id="customer-payLater-form">
                                    @method('PATCH') @csrf
                                <div class="col-sm-12 form-group mb-4">
                                    <label for="">Pending Reason</label>
                                    <sub class="text-danger error-text pending_reason" for="pending_reason"></sub>
                                    <textarea class="form-control" name="pending_reason" id="pending_reason" cols="10" rows="7" required></textarea>
                                    <input type="hidden" name="amount" value="0" id="">
                                </div>
                                <div class="col-sm-12 form-group mb-4">
                                    <button class="submit btn btn-primary mr-4" id="submitForm" type="submit">Submit</button>
                                    <button class="btn btn-light"  id="cancel" type="reset">Cancel</button>
                                </div>
                            </form>
                            </div>
                        </div>

                    @elseif ($customerSchemeDetails->status == 2)
                        <div class="col-sm-6 form-group mb-4">
                            <h5 style="color: green"> Scheme Payement Completed</h5>
                        </div>
                    @elseif ($customerSchemeDetails->status == 3)
                        <div class="col-sm-6 form-group mb-4">
                            <h5 style="color: green"> Lucky Winner</h5>
                        </div>
                    @else


                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('backend/assets/vendors/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script>
        $('#paid_details_table').hide();
        $('#hide').hide();
        $('#payLaterID').hide();
        $('#payLater').click(function(){
            $('#payLaterID').show();
            $('#payLater').hide();
            $('#payNow').hide();

        });
        $('#cancel').click(function(){
            $('#payLaterID').hide();
            $('#payLater').show();
            $('#payNow').show();

        });
        function AmountClick()
            {
                document.getElementById('amount1').value = "200";
                document.getElementById('amount').value = "200";


            }
            function amountChange(){
                $('#amount1').val($('#amount').val());
            }
        function onClickShow() {
            $('#paid_details_table').show();
            $('#hide').show();
            $('#show').hide();
        }

        function onClickHide() {
            $('#paid_details_table').hide();
            $('#hide').hide();
            $('#show').show();
        }
        $(document).ready(function() {
            $('#customer-payLater-form').submit(function(e) {
                e.preventDefault();
                let form = $(this);
                    var url = form.attr('action');
                    var method = form.attr('method');
                $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: method,
                        url: url,
                        data: $('#customer-payLater-form').serialize(),
                        beforeSend: function() {
                            $(document).find('sub.error-text').text('');
                        },
                        success: function(response) {
                            toastr.options = {
                                "closeButton": true,
                                "progressBar": true,
                                "timeOut": "1500",
                            }
                            toastr.success('Payment added to pending list');
                            setTimeout(function() {
                                // location.reload();
                                //  window.location = document.referrer;
                            }, 2000);
                        },
                        error: function(err) {
                            if (err.responseJSON['errors']) {
                                let error = err.responseJSON['errors'];
                                var msg = '';
                                $.each(error, (i, j) => {
                                    $('.' + i).text(j)
                                });
                                let errKeys = Object.keys(err.responseJSON['errors']);
                                errKeys.map((item) => {
                                    $('[name=' + item + ']').addClass(
                                        'is-invalid');
                                });
                            }
                        }
                    });
            });
            $('#customer-form').submit(function(e) {
                e.preventDefault();
                swal({
                    title: 'Are you sure?',
                    // text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    let form = $(this);
                    var url = form.attr('action');
                    var method = form.attr('method');
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: method,
                        url: url,
                        data: $('#customer-form').serialize(),
                        beforeSend: function() {
                            $(document).find('sub.error-text').text('');
                        },
                        success: function(response) {
                            toastr.options = {
                                "closeButton": true,
                                "progressBar": true,
                                "timeOut": "1500",
                            }
                            toastr.success(response.success);
                            setTimeout(function() {
                                location.reload();
                                //  window.location = document.referrer;
                            }, 2000);
                        },
                        error: function(err) {
                            if (err.responseJSON['errors']) {
                                let error = err.responseJSON['errors'];
                                var msg = '';
                                $.each(error, (i, j) => {
                                    $('.' + i).text(j)
                                });
                                let errKeys = Object.keys(err.responseJSON['errors']);
                                errKeys.map((item) => {
                                    $('[name=' + item + ']').addClass(
                                        'is-invalid');
                                });
                            }
                        }
                    });
                });
            });
        });
    </script>
@endpush
