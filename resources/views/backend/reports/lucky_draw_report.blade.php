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
            <h1 class="page-title page-title-sep">Lucky Draw Winners Report</h1>

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
                                    value="<?php echo date('Y-m-d'); ?>" placeholder="From Date" readonly />
                            </div>

                            <div class="col-md-3">
                                <input type="text" name="to_date" id="to_date" class="form-control"
                                    value="<?php echo date('Y-m-d'); ?>" placeholder="To Date" readonly />
                            </div>
                        @hasanyrole('super-admin|developer-admin|branch-manager')
                     @php
                         $user = Auth::user();
                        $userRole = $user->roles->pluck('name')->first();

                        $user = auth()->user();
                        $manager = \App\Models\Master\Manager::where('user_id', $user->id)->first();
                  @endphp
                        <div class="col-md-3">

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
                            @elseif($userRole == 'super-admin' ||$userRole == 'developer-admin')
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

                            <select class="select2 col-sm-12 scheme_select2  form-group" id="scheme_id" name="scheme_id"
                                required>
                                <option selected value="0">--All Scheme--</option>
                            </select>
                            <sub class="text-danger scheme_id" for="scheme_id"></sub>
                        </div>

                    </div>


                        @endhasanyrole
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
                                <th>#</th>
                                <th> Padhathi Number</th>
                                <th>Padhathi Name</th>
                                <th>Week</th>
                                <th>Draw Date</th>
                                {{-- <th>Previous Draw Date</th> --}}
                                <th>Lucky Draw Winner</th>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
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
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'pdf', 'print',

                ],
                ajax: {
                    url: "/admin/reports/lucky-draw-reports",
                    data: {
                        from_date: from_date,
                            to_date: to_date,
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
                        data: 'scheme_id',
                        name: 'scheme_id',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'scheme_name',
                        name: 'scheme_name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'week',
                        name: 'week',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'draw_date',
                        name: 'draw_date',
                        orderable: true,
                        searchable: true
                    },
                    // {
                    //     data: 'previous_draw_date',
                    //     name: 'previous_draw_date',
                    //     orderable: true,
                    //     searchable: true
                    // },
                    {
                        data: 'lucky_draw_winner',
                        name: 'lucky_draw_winner',
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
</script>


@endpush
