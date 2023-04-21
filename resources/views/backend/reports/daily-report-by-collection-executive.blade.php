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
            <h1 class="page-title page-title-sep">Daily Report By Collection Executive</h1>
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
                        <label>Date</label>
                        <input type="date" name="date" id="date" value="<?php echo date('Y-m-d'); ?>" class="col-sm-12 form-group"
                            placeholder=" Date" />
                    </div>
                    @hasanyrole('super-admin|developer-admin|branch-manager')
                        @php
                            $user = Auth::user();
                            $userRole = $user->roles->pluck('name')->first();
                            $user = auth()->user();
                            $manager = \App\Models\Master\Manager::where('user_id', $user->id)->first();
                        @endphp
                        <div class="col-md-3">
                            <label>Branches</label>
                            @if ($userRole == 'branch-manager')
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
                            @elseif($userRole == 'super-admin' || $userRole == 'developer-admin')
                                <select class="select2 col-sm-12 form-group" id="branch_id" name="branch_id" required>
                                    <option value="0">--All Branch--</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" @if (isset($customerExecutive) && $customerExecutive->branch_id == $branch->id)
                                            {{ 'selected' }}
                                    @endif>{{ $branch->id }}-{{ $branch->branch_name }}</option>
                            @endforeach
                            </select>
                            @endif
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
                            <label>Branch Area</label>
                            <select class="select2 col-sm-12 area_select2  form-group" id="area_id" name="area_id" required>
                                <option selected value="0">--All Area--</option>
                            </select>
                            <sub class="text-danger area_id" for="area_id"></sub>
                        </div>
                        {{-- <div class="row"> --}}
                        <div class="col-md-3">
                            <label>Collection Executive</label>
                            <select class="select2 col-sm-12 collection_executive_select2  form-group" id="executive_id"
                                required name="executive_id">
                                <option selected value="0">--select-an-option--</option>
                            </select>
                            <sub class="text-danger collection_executive_id" for="collection_executive_id"></sub>
                        </div>
                    @endhasanyrole
                    {{-- </div> --}}
                    <div class="col-md-3">
                        <br>
                        <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                        <button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button>
                        {{-- <button type="button" name="refresh" id="refresh" class="btn btn-default">Print</button> --}}
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
                                <th>Customer No</th>
                                <th>Customer Name</th>
                                <th>Weekly Payement(200)</th>
                                <th>Pending Payement</th>
                                <th>Advance Payement</th>
                                <th>Balance Amount</th>
                                <th>Collection Executive</th>
                                <th>Last Paid date</th>
                                <th>Last Paid Scheme date</th>
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

            function load_data(date = '') {
                $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    dom: 'Bfrtip',
                    buttons: [
                        'excel', 'pdf', 'print',
                       
                    ],
                    ajax: {
                        url: "/admin/reports/daily-report-by-collection",
                        data: {
                            date: $('#date').val(),
                            branch_id: $('#branch_id').val(),
                            area_id: $('#area_id').val(),
                            scheme_id: $('#scheme_id').val(),
                            executive_id: $('#executive_id').val(),
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
                            data: 'weekly_paymt',
                            name: 'weekly_paymt'
                        },
                        {
                            data: 'pending_paymt',
                            name: 'pending_paymt'
                        },
                        {
                            data: 'advance_paymt',
                            name: 'advance_paymt'
                        },
                        {
                            data: 'balance_amount',
                            name: 'balance_amount'
                        },
                        {
                            data: 'collection_executive',
                            name: 'collection_executive'
                        },
                        {
                            data: 'last_paid_date',
                            name: 'last_paid_date'
                        },
                        {
                            data: 'last_paid_scheme_date',
                            name: 'last_paid_scheme_date'
                        },
                    ]
                });
            }
            $('#filter').click(function() {
                var date = $('#date').val();
                var branch_id = $('#branch_id').val();
                var area_id = $('#area_id').val();
                var scheme_id = $('#scheme_id').val();
                var executive_id = $('#executive_id').val();
                if (date != '' && branch_id != '') {
                    $('.data-table').DataTable().destroy();
                    load_data(date, branch_id, area_id, scheme_id, executive_id);
                } else {
                    alert('All field is required');
                }
            });
            $('#refresh').click(function() {
                document.getElementById("date").valueAsDate = new Date()
                $("#branch_id").select2("val", "0");
                $('#area_id').find('option').not(':first').remove();
                $("#area_id").select2("val", "0");
                $('#scheme_id').find('option').not(':first').remove();
                $("#scheme_id").select2("val", "0");
                $('#executive_id').find('option').not(':first').remove();
                $("#executive_id").select2("val", "0");
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
            $('#area_id').on('change', function() {
                area_id = ($(this).val());
                var branch_id = document.getElementById("branch_id").value;
                if (area_id) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "/admin/reports/get-collection-executive",
                        method: 'GET',
                        data: {
                            "area_id": area_id,
                            "branch_id": branch_id,
                        },
                        success: function(response) {
                            setExecutive(response);
                        },
                        error: function(xhr) {
                            console.log(xhr)
                        }
                    });
                }
            });
        });

        function getData(branch_id) {
            $('#executive_id').find('option').not(':first').remove();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'GET',
                url: "/admin/reports/get-data-scheme-area",
                data: {
                    branch_id: branch_id,
                },
                success: function(data) {
                    console.log(data);
                    setScheme(data.scheme);
                    setArea(data.areas);
                },
            });
        }

        function setScheme(scheme) {
            $('.scheme_select2').empty();
            scheme.forEach(element => {
                var newOption = new Option(element.scheme_id + ' ' + element.name, element.id, false, false);
                $('.scheme_select2').append(`<option></option`);
                $('.scheme_select2').append(newOption);
            });
        }

        function setArea(areas) {
            $('.area_select2').empty();
            areas.forEach(element => {
                var newOption = new Option(element.area_id + ' ' + element.name, element.id, false, false);
                $('.area_select2').append(`<option></option`);
                $('.area_select2').append(newOption);
            });
        }

        function setExecutive(resp) {
            $('.collection_executive_select2').empty();
            resp.forEach(element => {
                var newOption = new Option(element.executive_id + ' ' + element.name,
                    element.id, false, false);
                $('.collection_executive_select2').append(`<option></option`);
                $('.collection_executive_select2').append(newOption);
            });
        }
    </script>
@endpush
