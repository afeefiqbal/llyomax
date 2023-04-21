@extends('backend.layouts.backend')

@section('content')

    @push('styles')
        {{-- <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" /> --}}
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <style>
            .select2-selection__arrow {
                top: 15px !important;
            }

            .select2-selection__rendered {
                line-height: 10px !important;
            }

        </style>
    @endpush

    <!-- BEGIN: Page heading-->
    <div class="page-heading">
        <div class="page-breadcrumb">
            <h1 class="page-title page-title-sep">
                @if (isset($report))
                    Edit Collection Form
                @else
                    Create Collection Form
                @endif
            </h1>

            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Executives</li>
                <li class="breadcrumb-item">Collection Form</li>
                <li class="breadcrumb-item">
                    @if (isset($report))
                        Edit Collection Form
                    @else
                        Create Collection Form
                    @endif
                </li>
            </ol>
        </div>
        <div>
            <a href="/admin/executive/report-submission">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i>Executives Collection Form List</button>
            </a>
        </div>
    </div>
    <!-- End Page Heading -->


    <div>
        <div class="col-lg-12">

            <div class="card card-fullheight">
                <div class="card-body">
                    @if (isset($report))
                        <form action="/admin/executive/executives/{{ $report->id }}" method="PATCH" id="submission-form">
                            @method('PATCH')
                        @else
                            <form action="/admin/executive/report-submission" method="POST" id="submission-form">
                    @endif
                    @csrf
                    <div class="row">
                        <div class="col-sm-4 form-group mb-4">
                            <label>Branches<span class="text-danger">*</span></label>
                            <select class="select2 col-sm-12 form-group" id="branch_id" name="branch_id" required>
                                <option value="">--select-an-option--</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" @if (isset($report) && $report->branch_id == $branch->id)
                                        {{ 'selected' }}
                                @endif>{{ $branch->id }}-{{ $branch->branch_name }}</option>
                                @endforeach
                            </select>
                            <sub class="text-danger branch_id" for="branch_id"></sub>
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label>Scheme<span class="text-danger">*</span></label>
                            <select class="select2 col-sm-12 scheme_select2  form-group" id="scheme_id" name="scheme_id" required>
                                <option selected value="">--select-an-option--</option>

                            </select>
                            <sub class="text-danger scheme_id" for="scheme_id"></sub>
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label>Customer<span class="text-danger">*</span></label>
                            <select class="select2 col-sm-12 customer_select2  form-group" id="customer_id" required
                                name="customer_id">
                                <option selected value="">--select-an-option--</option>
                            </select>
                            <sub class="text-danger customer_id" for="customer_id"></sub>
                        </div>
                    </div>

                    <div>
                        <h6 style="float: right"><input type="button" class="btn btn-primary" value="Submit"
                                onclick="addRow();" id="rowButton" /></h6>
                        <table class="table mb-4" id="table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Customer ID</th>
                                    <th scope="col">Customer Name</th>
                                    <th scope="col">Total Scheme Amount</th>
                                    <th scope="col">Advance Amount</th>
                                    <th scope="col">Pending Amount</th>
                                    <th scope="col">Total Amount paid</th>
                                    <th scope="col">Amount to complete Scheme</th>
                                    <th scope="col">Collected Amount</th>
                                    {{-- <th scope="col">Executive ID</th> --}}
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary mr-2" id="submitForm" type="submit">Submit</button>
                        <button class="btn btn-light" type="reset">Clear</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('backend/assets/vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <!-- CORE SCRIPTS-->
    <script>
        $('#branch_id').on('change', function() {
            branch_id = ($(this).val());
            if (branch_id) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/admin/executive/get-schemes",
                    method: 'POST',
                    data: {
                        "branch_id": branch_id,
                    },
                    success: function(response) {

                        setScheme(response)
                    },
                    error: function(xhr) {
                        console.log(xhr)
                    }
                });
            }
        });
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select an option",
            });


            $('#submission-form').submit(function(e) {
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
                    data: $('#submission-form').serialize(),
                    success: function(data) {
                        swal(
                        'Success!',
                        ' Collection has been added.',
                        'success'
                        ).then(()=>{
                        location.reload();
                        });

                    },
                    error: function(err) {


                        if (err.responseJSON['errors']) {

                            let error = err.responseJSON['errors'];
                            var msg = '';
                            $.each(error, (i, j) => {
                                $('.' + i).text(j);
                                msg += j + '<br/>';
                            });

                            let errKeys = Object.keys(err.responseJSON['errors']);

                            errKeys.map((item) => {
                                $('[name=' + item + ']').addClass('is-invalid');
                            });

                        }

                    }
                });
            });

        });

        function setScheme(resp) {
            $('.scheme_select2').empty();
            resp.forEach(element => {
                var newOption = new Option(element.scheme_id + ' ' + element.name, element.id, false, false);
                $('.scheme_select2').append(`<option></option`);
                $('.scheme_select2').append(newOption);
            });
        }
    </script>
    <script>

        $('#scheme_id').on('change', function() {
            branch_id = $('#branch_id').val();
            scheme_id = $('#scheme_id').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/admin/executive/get-customer",
                method: 'POST',
                data: {
                    branch_id: branch_id,
                    scheme_id: scheme_id,
                },
                success: function(data) {
                    // $('#rowButton').prop('disabled', false);
                    setCustomer(data);

                },
                error: function(err) {


                    if (err.responseJSON['errors']) {

                        let error = err.responseJSON['errors'];
                        var msg = '';
                        $.each(error, (i, j) => {
                            $('.' + i).text(j);
                            msg += j + '<br/>';
                        });

                        let errKeys = Object.keys(err.responseJSON['errors']);

                        errKeys.map((item) => {
                            $('[name=' + item + ']').addClass('is-invalid');
                        });

                    }
                }
            });
        });
        function setCustomer(resp) {
            $('.customer_select2').empty();
            resp.forEach(element => {

                var newOption = new Option(element.customer.customer_id + ' ' + element.customer.name, element.customer.id, false, false);
                $('.customer_select2').append(`<option></option`);
                $('.customer_select2').append(newOption);
            });
        }
        function addRow() {
            branch_id = $('#branch_id').val();
            customer_id = $('#customer_id').val();
            scheme_id = $('#scheme_id').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/admin/executive/get-customer-details",
                method: 'POST',
                data: {
                    branch_id: branch_id,
                    scheme_id: scheme_id,
                    customer_id : customer_id
                },
                success: function(data) {
                    $('#rowButton').prop('disabled', false);
                    setCustomerTable(data);


                },
                error: function(err) {


                    if (err.responseJSON['errors']) {

                        let error = err.responseJSON['errors'];
                        var msg = '';
                        $.each(error, (i, j) => {
                            $('.' + i).text(j);
                            msg += j + '<br/>';
                        });

                        let errKeys = Object.keys(err.responseJSON['errors']);

                        errKeys.map((item) => {
                            $('[name=' + item + ']').addClass('is-invalid');
                        });

                    }
                }
            });
        }

        function setCustomerTable(element) {
            let i = 1;
            console.log(element)
            due = parseFloat(element.scheme.total_amount) - parseFloat(element.total_amount);
            $('table').append(`
                     <tr>
                      <td><input type="text" class="form-control" name="customer_id[]" value="` + element.customer
                .customer_id + `" placeholder="#" disabled ></td>
                      <td><input type="text" class="form-control" name="customer_id[]" value="` + element.customer
                .name + `" placeholder="#" disabled></td>
                <td><input type="text" class="form-control" name="total_scheme_amount[]" value="` + element.scheme
                .total_amount + `" placeholder="#" disabled></td>
                      <td><input type="text" class="form-control" name="advance_amount[]" value="` + element
                .advance_amount + `" placeholder="#" disabled></td>
                <td><input type="text" class="form-control" name="advance_amount[]" value="` + element
                .pending_amount + `" placeholder="#" disabled></td>
                <td><input type="text" class="form-control" name="total_paid[]" value="` + element
                .total_amount + `" placeholder="#" disabled></td>
                <td><input type="text" class="form-control" name="scheme_to_complete[]" value="` + due + `" placeholder="#" disabled></td>
                <td><input type="number" max="`+due+`" required class="form-control" name="collected_amount" value=""  required placeholder="#"></td>`+
                      `<td>

                      </td>
                     </tr>
                     `);
            $('#rowButton').prop('disabled', true);
        }
    </script>
    <script>
        $(document).on('click', '.remove', function() {

            $(this).closest("tr").remove();
        });
    </script>
@endpush
