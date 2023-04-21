@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />

        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" />
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
            <h1 class="page-title page-title-sep">Order Report</h1>
        </div>
        <div>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="row input-daterange">
                            <div class="col-md-3">
                                <input type="text" name="from_date" id="from_date" class="form-control"
                                    placeholder="From Date" readonly />
                            </div>

                            <div class="col-md-3">
                                <input type="text" name="to_date" id="to_date" class="form-control"
                                    placeholder="To Date" readonly />
                            </div>
                            {{-- @hasanyrole('super-admin|developer-admin|branch-manager')
                                <div class="col-md-3">
                                    <div class="btn-group" role="group" aria-label="Basic outlined example">
                                        <button type="button" class="btn btn-outline-primary category "
                                            id="category">Category</button>
                                        <button type="button" class="btn btn-outline-primary product"
                                            id="product">Product</button>
                                    </div>
                                </div>
                            </div>
                        @endhasanyrole --}}
                            <div class="col-md-3">
                                <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                                <button type="button" name="refresh" id="refresh"
                                    class="btn btn-default">Refresh</button>
                                {{-- <button type="button" name="refresh" id="refresh" class="btn btn-default">Print</button> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div id="cat">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered w-100 data-table" id="dt-filter">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th> Category ID</th>
                                    <th>Category Name</th>
                                    <th>Quantity</th>
                                    <th>Products</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div> --}}
            <div id="prod">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered w-100 data-table" id="dt-filter">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th> Order ID</th>
                                        <th>Customer Details</th>
                                        <th>Shipping Address</th>
                                        <th>Order Date</th>
                                        <th>Total Amount</th>
                                        {{-- <th>Payment method</th> --}}
                                        <th>Note</th>
                                        <th>Delivery Status</th>
                                        <th>Delivery date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script src="{{ asset('backend/assets/vendors/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                // $('#prod').show();
                // $('#cat').hide();
                $('.input-daterange').datepicker({
                    todayBtn: 'linked',
                    format: 'yyyy-mm-dd',
                    autoclose: true
                });
                $('.select2').select2({
                    placeholder: "---All Scheme----",
                });
                load_data($('#from_date').val(), $('#to_date').val());

                function load_data(from_date = '', to_date = '') {
                    $('.data-table').DataTable({
                        processing: true,
                        serverSide: true,
                        dom: 'Bfrltip',
                        buttons: [
                            'excel', 'pdf', 'print',

                        ],

                        ajax: {
                            url: "/admin/reports/order-reports",
                            data: {
                                from_date: from_date,
                                to_date: to_date,
                                branch_id: $('#branch_id').val(),
                                scheme_id: $('#scheme_id').val(),
                            },
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                width: '10%'
                            },
                            {
                                data: 'order_id',
                                name: 'order_id',
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: 'customer_details',
                                name: 'customer_details',
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: 'shipping_address',
                                name: 'shipping_address',
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: 'order_date',
                                name: 'order_date',
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: 'net_amount',
                                name: 'net_amount',
                                orderable: true,
                                searchable: true
                            },
                            // {
                            //     data: 'payment_method',
                            //     name: 'payment_method',
                            //     orderable: true,
                            //     searchable: true
                            // },
                            {
                                data: 'note',
                                name: 'note',
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: 'delivery_status',
                                name: 'delivery_status',
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: 'delivery_date',
                                name: 'Delivery Date',
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: 'status',
                                name: 'status',
                                orderable: true,
                                searchable: true
                            },
                        ]
                    });
                }
                $('#filter').click(function() {
                    var from_date = $('#from_date').val();
                    var to_date = $('#to_date').val();
                    var branch_id = $('#branch_id').val();
                    var scheme_id = $('#scheme_id').val();
                    if (from_date != '' && to_date != '') {
                        $('.data-table').DataTable().destroy();
                        load_data(from_date, to_date);
                    } else {
                        alert('All field is required');
                    }
                });
            });
            $('#refresh').click(function() {
                $("#from_date").datepicker().datepicker("setDate", new Date());
                $("#to_date").datepicker().datepicker("setDate", new Date());
                $("#branch_id").select2("val", "0");
                $('#scheme_id').find('option').not(':first').remove();
                $("#scheme_id").select2("val", "0");
                $('.data-table').DataTable().destroy();
                load_data($('#from_date').val(), $('#to_date').val());
            });
            var branch_id = $("#branch_id :selected").val();;
            if (branch_id != '') {
                getData(branch_id);
            }
            $('#branch_id').on('change', function(e) {
                var branch_id = $("#branch_id :selected").val();
                getData(branch_id);
            });

            function getData(branch_id) {
                if (branch_id) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'GET',
                        url: "/admin/reports/get-data",
                        data: {
                            branch_id: branch_id,
                        },
                        success: function(data) {
                            console.log(data);
                            setScheme(data.scheme);
                        },
                    });
                }
            }

            function setScheme(scheme) {
                $('.scheme_select2').empty();
                scheme.forEach(element => {
                    var newOption = new Option(element.scheme_id + ' ' + element.name, element.id, false, false);
                    $('.scheme_select2').append(`<option></option`);
                    $('.scheme_select2').append(newOption);
                });
            }
            $('.btn').click(function() {
                var cal = $(this).attr('id');
                if (cal === 'category') {
                    $('#prod').hide();
                    $('#cat').show();
                    $('.category').addClass('active');
                    $('.product').removeClass('active');
                } else if (cal === 'product') {
                    $('#prod').show();
                    $('#cat').hide();
                    $('.category').removeClass('active');
                    $('.product').addClass('active');
                }
            });
        </script>
    @endpush
