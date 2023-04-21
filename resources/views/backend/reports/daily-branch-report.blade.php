@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
            <h1 class="page-title page-title-sep">Daily Branch Report By Marketing Manager</h1>
            <ol class="breadcrumb">
            </ol>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="card">
            <div class="card-body">
                {{-- <div class="row"> --}}

                    <div class="row input-daterange">
                        <label for="">Date</label>
                        <div class="col-md-3">

                            <input type="text" name="from_date" id="from_date" class="form-control"
                                value="<?php echo date('Y-m-d'); ?>" placeholder="From Date" readonly />
                        </div>
                        <div class="col-md-3">

                            <input type="text" name="to_date" id="to_date" class="form-control"
                                value="<?php echo date('Y-m-d'); ?>" placeholder="To Date" readonly />
                        </div>
                        <div class="col-md-3">
                            @isset($branches)
                            <select name="branch_id" id="branch_id" class="form-control">
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                @endforeach
                            </select>
                            @endisset
                        </div>

                        <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                        <button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button>

                </div>
                {{-- </div> --}}
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered w-100 data-table" id="dt-filter">
                        <thead class="thead-light">
                            <tr>
                                <th>Sl.No</th>
                                <th>Branch Name</th>
                                <th>Branch Manager</th>
                                <th>New Joining(1PM - 6PM)</th>
                                <th>Cash Collection From New joining</th>
                                <th>weekly Payment </th>
                                <th>Pending Payment </th>
                                <th>Advance Payment </th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('backend/assets/vendors/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
   <script type="text/javascript">
        $(document).ready(function() {
            $('.input-daterange').datepicker({
                todayBtn: 'linked',
                format: 'yyyy-mm-dd',
                autoclose: true
            });
            $('.select2').select2({
                placeholder: "Select an option",
            });
            load_data($('#from_date').val(), $('#to_date').val(), $('#branch_id').val());

            function load_data(from_date = '', to_date = '', branch_id = '') {
                $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    dom: 'Bfrtip',
                    buttons: [
                        'excel', 'pdf', 'print',

                    ],
                    ajax: {
                        url: "/admin/reports/branch-report-by-marketing",
                        data: {
                            from_date: from_date,
                            to_date: to_date,
                            branch_id: branch_id
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            width: '10%'
                        },
                        {
                            data: 'branch_name',
                            name: 'branch_name',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'branch_manager',
                            name: 'branch_manager',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'new_joining',
                            name: 'new_joining',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'cash_collection_new_joining',
                            name: 'cash_collection_new_joining',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'weekly_paymt',
                            name: 'weekly_paymt',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'weekly_paymt_pending',
                            name: 'weekly_paymt_pending'
                        },
                        {
                            data: 'weekly_paymt_advance',
                            name: 'weekly_paymt_advance'
                        },
                        {
                            data: 'remark',
                            name: 'remark'
                        },
                    ]
                });
            }
            $('#filter').click(function() {
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                var branch_id = $('#branch_id').val();
                if (from_date != '' && to_date != '') {
                    $('.data-table').DataTable().destroy();
                    load_data(from_date, to_date    , branch_id);
                } else {
                    alert('All field is required');
                }
            });
            $('#refresh').click(function() {
                $("#from_date").datepicker().datepicker("setDate", new Date());
                $("#to_date").datepicker().datepicker("setDate", new Date());
                $('#branch_id').val('').trigger('change');
                $('.data-table').DataTable().destroy();
                load_data();
            });
        });
    </script>
@endpush
