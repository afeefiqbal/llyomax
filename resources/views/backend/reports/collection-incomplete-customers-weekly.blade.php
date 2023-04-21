@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
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
            <h1 class="page-title page-title-sep">Collection Incomplete Customers (Weekly Report)</h1>
            <ol class="breadcrumb">
            </ol>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-md-3">
                        <label>Branches</label>
                        @hasanyrole('super-admin|developer-admin')
                            <select class="select2 col-sm-12 form-group" id="branch_id" name="branch_id" required>
                                <option value="0">--All Branch--</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" @if (isset($customerExecutive) && $customerExecutive->branch_id == $branch->id)
                                        {{ 'selected' }}
                                @endif>{{ $branch->id }}-{{ $branch->branch_name }}</option>
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
                        <label>Scheme</label>
                        <select class="select2 col-sm-12 scheme_select2  form-group" id="scheme_id" name="scheme_id"
                            required>
                            <option selected value="0">--All Scheme--</option>
                        </select>
                        <sub class="text-danger scheme_id" for="scheme_id"></sub>
                    </div>
                    <div class="col-md-3">
                        <label>Week</label>
                        <select class="select2 col-sm-12 week_select2  form-group" id="week" name="week" required>
                            <option selected value="">--select-an-option--</option>
                        </select>
                        <sub class="text-danger week" for="week"></sub>
                    </div>
                    <div class="col-md-3">
                        <br>
                        <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                        <button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button>
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
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Place</th>
                                <th>Mobile</th>

                                <th>executive</th>
                                <th>Reason</th>
                                <th>Scheme ID</th>
                                <th>Branch ID</th>
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select an option",
            });
            load_data();

            function load_data(week = '') {
                $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    dom: 'Bfrtip',
                    buttons: [
                         'excel', 'pdf', 'print',

                    ],
                    ajax: {
                        url: "/admin/reports/collection-incomplete-customers",
                        data: {
                            week: $('#week').val(),
                            branch_id: $('#branch_id').val(),
                            scheme_id: $('#scheme_id').val(),
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            width: '10%'
                        },
                        {
                            data: 'customer_id',
                            name: 'customer_id',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'customer_name',
                            name: 'customer_name',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'customer_place',
                            name: 'customer_place'
                        },
                        {
                            data: 'customer_phone',
                            name: 'customer_phone'
                        },
                        {
                            data: 'executive',
                            name: 'executive'
                        },
                        {
                            data: 'reason',
                            name: 'reason'
                        },
                        {
                            data: 'scheme_id',
                            name: 'scheme_id'
                        },
                        {
                            data: 'branch_id',
                            name: 'branch_id'
                        },
                    ]
                });
            }
            $('#filter').click(function() {
                var week = $('#week').val();
                var branch_id = $('#branch_id').val();
                var scheme_id = $('#scheme_id').val();
                if (week != '' || branch_id != '' || scheme_id != '') {
                    $('.data-table').DataTable().destroy();
                    load_data(week, branch_id, scheme_id);
                } else {
                    alert('All field is required');
                }
            });
            $('#refresh').click(function() {
                $('#week').find('option').not(':first').remove();
                $("#week").select2("val", "0");
                $("#branch_id").select2("val", "0");
                $('#scheme_id').find('option').not(':first').remove();
                $("#scheme_id").select2("val", "0");
                $('.data-table').DataTable().destroy();
                load_data();
            });
            var branch_id = $("#branch_id :selected").val();;
            if (branch_id != '') {
                getData(branch_id);
            }
            $('#branch_id').on('change', function(e) {
                var branch_id = $("#branch_id :selected").val();
                getData(branch_id);
            });
            $('#scheme_id').on('change', function(e) {
                var scheme_id = $("#scheme_id :selected").val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'GET',
                    url: "/admin/reports/get-scheme-date",
                    data: {
                        scheme_id: scheme_id,
                    },
                    success: function(data) {
                        console.log(data);
                        setSchemeDate(data);
                    },
                });
            });
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

        function setSchemeDate(data) {
            $('.week_select2').empty();
            data.forEach((element, index) => {
                var newOption = new Option(element, index, false, false);
                $('.week_select2').append(`<option></option`);
                $('.week_select2').append(newOption);
            });
        }
    </script>
@endpush
