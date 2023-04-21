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
            <h1 class="page-title page-title-sep">Daily Report By Branch </h1>
            <ol class="breadcrumb">
            </ol>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="row input-daterange">
                        <div class="col-md-3">
                            <input type="text" name="from_date" id="from_date" class="form-control"
                                value="<?php echo date('Y-m-d'); ?>" placeholder="From Date" readonly />
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="to_date" id="to_date" class="form-control"
                                value="<?php echo date('Y-m-d'); ?>" placeholder="To Date" readonly />
                        </div>

                    <div class="col-md-3">
                        {{-- <label>Branches<span class="text-danger">*</span></label> --}}
                        @hasanyrole('super-admin|developer-admin')
                        <select class="select2 col-sm-12 form-group" id="branch_id" name="branch_id" required>
                            <option value="0">--All Branch--</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" >{{ $branch->branch_id }}-{{ $branch->branch_name }}</option>
                            @endforeach
                        </select>
                        @endhasanyrole
                        @hasanyrole('branch-manager')
                        @php
                            $user = auth()->user();
                            $manager = \App\Models\Master\Manager::where('user_id', $user->id)->first();
                        @endphp
                        <select class="select2 col-sm-12 form-group" id="branch_id" name="branch_id" required>
                            @isset($branches)
                                @foreach ($branches as $branch)
                                    @isset($manager)
                                        @if ($manager->branch_id == $branch->id)
                                            <option value="{{ $branch->id }}" selected>
                                                {{ $branch->branch_name }}</option>
                                        @else
                                            <option value="{{ $branch->id }}" disabled>
                                                {{ $branch->branch_name }}</option>
                                        @endif
                                    @endisset
                                @endforeach
                            @endisset
                        </select>
                    @endhasanyrole
                        <sub class="text-danger branch_id" for="branch_id"></sub>
                    </div>

                    <div class="col-md-3">
                        <br>
                        <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                        <button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button>
                        {{-- <button type="button" name="refresh" id="refresh" class="btn btn-default">Print</button> --}}
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered w-100 data-table" id="dt-filter">
                        <thead class="thead-light">
                            <tr>
                                <th>Sl.No</th>
                                <th>Scheme Name</th>
                                <th>New Joining Count</th>
                                <th>New Joining Payment</th>
                                <th>Weekly Payment</th>
                                <th>Pending Payment </th>
                                <th>Advance Payment </th>
                                <th>Total</th>
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
            load_data($('#from_date').val(), $('#to_date').val());
            function load_data(from_date = '', to_date = '') {
                $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    dom: 'Bfrtip',
                    buttons: [
                       'excel', 'pdf', 'print',

                    ],
                    ajax: {
                        url: "/admin/reports/scheme-report-by-branch",
                        data: {
                            from_date: from_date,
                            to_date: to_date,
                            branch_id: $('#branch_id').val(),
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            width: '10%'
                        },
                        {
                            data: 'scheme_name',
                            name: 'scheme_name',
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
                            data: 'new_joining_payment',
                            name: 'new_joining_payment',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'new_joining_with_paymt',
                            name: 'new_joining_with_paymt',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'new_joining_without_paymt',
                            name: 'new_joining_without_paymt',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'new_joining_advance_paymt',
                            name: 'new_joining_advance_paymt',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'cash_collection',
                            name: 'cash_collection'
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
                    load_data(from_date, to_date);
                } else {
                    alert('All field is required');
                }
            });
            $('#refresh').click(function() {
                $("#from_date").datepicker().datepicker("setDate", new Date());
                $("#to_date").datepicker().datepicker("setDate", new Date());
                $("#branch_id").select2("val", "0");
                $('.data-table').DataTable().destroy();
                load_data($('#from_date').val(), $('#to_date').val());
            });
        });
    </script>
@endpush
