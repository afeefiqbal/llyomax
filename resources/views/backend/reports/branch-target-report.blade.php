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
            <h1 class="page-title page-title-sep">Branch Target Report</h1>
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
                            <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date"
                                readonly />
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date"
                                readonly />
                        </div>
                        <div class="col-md-3">
                            {{-- <label>Branches<span class="text-danger">*</span></label> --}}
                            <select class="select2 col-sm-12 form-group" id="branch_id" name="branch_id" required>
                                <option value="">--select-branch--</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->id }}-{{ $branch->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                            <sub class="text-danger branch_id" for="branch_id"></sub>
                        </div>
                        <div class="col-md-3">
                            <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                            <button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button>
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
                                <th>join date</th>
                                <th>Target</th>
                                <th>Achieved</th>
                                <th>To Achieved</th>
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

            function load_data(branch_id = '', from_date = '', to_date = '') {
                $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    dom: 'Bfrtip',
                    buttons: [
                       'excel', 'pdf', 'print',
                       
                    ],
                    ajax: {
                        url: "/admin/reports/branch-target",
                        data: {
                            branch_id: $('#branch_id').val(),
                            from_date: from_date,
                            to_date: to_date
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
                            data: 'joining_date',
                            name: 'joining_date'
                        },
                        {
                            data: 'target',
                            name: 'target'
                        },
                        {
                            data: 'achieved',
                            name: 'achieved'
                        },
                        {
                            data: 'to_achieved',
                            name: 'to_achieved'
                        },
                        {
                            data: 'remark',
                            name: 'remark'
                        },
                    ]
                });
            }
            $('#filter').click(function() {
                var branch_id = $('#branch_id').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                if (branch_id != '' && from_date != '' && to_date != '') {
                    $('.data-table').DataTable().destroy();
                    load_data(branch_id, from_date, to_date);
                } else {
                    alert('All field is required');
                }
            });
            $('#refresh').click(function() {
                $('#from_date').val('');
                $('#to_date').val('');
                $("#branch_id").select2("val", "0");
                $('.data-table').DataTable().destroy();
                load_data();
            });
        });
    </script>
@endpush
