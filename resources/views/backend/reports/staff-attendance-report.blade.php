@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
    @endpush
    <!-- BEGIN: Page heading-->
    <div class="page-heading">
        <div class="page-breadcrumb">
            <h1 class="page-title page-title-sep">Staff attendance Report</h1>
        </div>
        <div>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3">
                        <input type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control" name="date" id="date">
                    </div>
                    <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                    <button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button>
                </div>
            </div>
        </div>
    </div>
    </div>
    <br>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered w-100 data-table" id="dt-filter">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Branch ID</th>
                            <th>Branch Name</th>
                            <th>No. of present Staffs</th>
                            <th>No. of Absent Staffs</th>
                            <th>No. of Late Staffs</th>
                            <th>Action</th>
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            load_data($('#date').val());

            function load_data(date = '') {
                $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    dom: 'Bfrtip',
                    buttons: [
                        'excel', 'pdf', 'print',
                       
                    ],
                    ajax: {
                        url: "/admin/reports/staff-attendance-reports",
                        data: {
                            date: $('#date').val(),
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            width: '10%'
                        },
                        {
                            data: 'branch_id',
                            name: 'branch_id'
                        },
                        {
                            data: 'branch_name',
                            name: 'branch_name'
                        },
                        {
                            data: 'present',
                            name: 'present'
                        },
                        {
                            data: 'absent',
                            name: 'absent'
                        },
                        {
                            data: 'late',
                            name: 'late',
                        },
                        {
                            data: 'attendence_details',
                            name: 'attendence_details',
                        },
                    ]
                });
            }
            $('#filter').click(function() {
                var date = $('#date').val();
                if (date != '') {
                    $('.data-table').DataTable().destroy();
                    load_data(date);
                } else {
                    alert('All field is required');
                }
            });
            $('#refresh').click(function() {
                $("#date").datepicker().datepicker("setDate", new Date());
                $('.data-table').DataTable().destroy();
                load_data();
            });
        });
    </script>
@endpush
