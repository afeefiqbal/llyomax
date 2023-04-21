@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <link href="{{ asset('backend/assets/vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        {{-- <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" /> --}}
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
        <style>
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #444;
                line-height: 11px;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                top: 15px;
            }
        </style>
    @endpush
    <div class="page-heading">
        <div class="page-breadcrumb">
            <h1 class="page-title page-title-sep">
                @if (isset($luckyDraw))
                Eligible Customers list
                @else
                Eligible Customers list
                @endif
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Branch</li>
                <li class="breadcrumb-item">Lucky Draw</li>
                <li class="breadcrumb-item">
                    @if (isset($luckyDraw))
                   Eligible Customers list
                    @else
                    Eligible Customers list
                    @endif
                </li>
            </ol>
        </div>

    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-fullheight">
                <div class="card-body">
                    @if (isset($luckyDraw))
                        <form action="/admin/branch/luckydraws/winners-list{{ $luckyDraw->id }}" method="PATCH" id="luckydraw-form">
                            @method('PATCH')
                        @else
                            <form action="/admin/branch/luckydraws/winners-list" method="POST" id="luckydraw-form">
                    @endif
                    @csrf
                    <div class="row input-daterange">
                        <div class="col-md-4">
                            <label>From date<span class="text-danger">*</span></label>
                            <input type="text" name="from_date" id="from_date" class="form-control"
                                value="<?php echo date('Y-m-d'); ?>" placeholder="From Date"/>
                        </div>

                        <div class="col-md-4">
                            <label>To Date<span class="text-danger">*</span></label>
                            <input type="text" name="to_date" id="to_date" class="form-control"
                                value="<?php echo date('Y-m-d'); ?>" placeholder="To Date"/>
                        </div>
                        <div class="clearfix"> <br> </div>
                        <div class="col-sm-4 form-group mb-12">
                            <label>Clusters<span class="text-danger">*</span></label>
                            <select class="select2_demo form-control "  id="cluster_id" name="cluster_id" required>
                                <option value="">--select-an-option--</option>
                                @foreach ($clusters as $cluster)
                                    <option value="{{ $cluster->id }}" @if (isset($branchTarget) && $branchTarget->branch_id == $branch->id) {{ 'selected' }} @endif
                                        >{{ $cluster->cluster_id }}-{{ $cluster->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4 form-group mb-12">
                            <label>Branches<span class="text-danger">*</span></label>
                            @hasanyrole('super-admin|developer-admin')
                                <select class="select2_demo form-control branch_id" id="branch_id" name="branch_id" required>
                                    <option value="">--select-an-option--</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" @if (isset($branchTarget) && $branchTarget->branch_id == $branch->id)
                                            {{ 'selected' }}
                                    @endif>{{ $branch->id }}-{{ $branch->branch_name }}</option>
                                    @endforeach
                                </select>
                            @endhasanyrole
                            @role('branch-manager')
                                @php
                                    $user = auth()->user();
                                    $manager = \App\Models\Master\Manager::where('user_id', $user->id)->first();
                                @endphp
                                <select class="select2_demo form-control" id="branch_id" name="branch_id" required>
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
                            @endrole
                            <label id="branch_id-error" class=" invalid-feedback active branch_id" for="branch_id"></label>
                        </div>
                        <div class="col-sm-4 form-group mb-12">
                            <label>Schemes<span class="text-danger">*</span></label>
                            <select class="select2_demo form-control scheme_select2" onchange="getWeek()" name="scheme_id"
                                id="scheme_id" required>
                                <option></option>
                            </select>
                            <label id="scheme_id-error" class=" invalid-feedback active scheme_id" for="scheme_id"></label>
                        </div>
                        <div class="col-sm-4 form-group mb-12">
                            <label>Scheme weeks<span class="text-danger">*</span></label>
                            <select class="select2_demo form-control week_select2" id="week" name="week"
                               required>
                                <option selected value="">--select-an-option--</option>
                            </select>
                            <span class="text-danger error-text week"></span>
                            {{-- <sub class="text-danger week" for="week"></sub> --}}
                        </div>

                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary mr-2" id="submitForm"  onclick="getCustomers()" type="button">Submit</button>
                        <button class="btn btn-light" type="reset">Clear</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered w-100 data-table" id="dt-filter">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Customer ID</th>
                                    <th>Customer Name</th>
                                    <th>Phone</th>
                                    <th>Branch</th>
                                    <th>Area</th>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
    <!-- CORE SCRIPTS-->
    <script>
         $(document).ready(function() {
            getScheme()
            $('.input-daterange').datepicker({
                // todayBtn: 'linked',
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                width: '100%',
            });
            $(".select2_demo").select2({
                placeholder: "Select an option",
            });
        })
        $('#cluster_id').on('change', function() {
            var cl_id = $(this).val();
            if (cl_id != '') {
               setB(cl_id)
            }
        });
        function getCustomers() {
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            var scheme_id = $("#scheme_id :selected").val();
            var week = $("#week :selected").val();
            var branch_id = $("#branch_id :selected").val();
            $('.data-table').DataTable().destroy();
            // getWinnerCustomers(branch_id, scheme_id, week);
            $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'pdf', 'print',

                ],
                ajax: {
                    url: "/admin/branch/get-customers",
                    data: {
                        from_date: from_date,
                        to_date :to_date,
                        scheme_id: scheme_id,
                        week: week,
                        branch_id: branch_id,
                        cluster_id: $("#cluster_id :selected").val(),
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        width: '10%'
                    },
                    {
                        data: 'customerId',
                        name: 'customerId'
                    },
                    {
                        data: 'customername',
                        name: 'customername'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'branch',
                        name: 'branch'
                    },
                    {
                        data: 'area',
                        name: 'area'
                    },
                ]
            });
        }
        function setB(id) {
            $('#branch_id').empty();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'GET',
                url: "/admin/branch/get-branch",
                data: {
                    id: id,
                },
                success: function(data) {
                    setBranch(data);
                },
                error: function(err) {
                    if (err.responseJSON['errors']) {
                        let error = err.responseJSON['errors'];
                        var msg = '';
                        $.each(error, (i, j) => {
                            msg += j + '<br/>';
                        });
                        let errKeys = Object.keys(err.responseJSON['errors']);
                        errKeys.map((item) => {
                            $('[name=' + item + ']').addClass('is-invalid');
                        });
                    }
                    swal(
                        'Something went wrong!',
                        msg,
                        'error'
                    )
                }
            });
        }
        function setBranch(data) {
            $('.branch_id').empty();
            //adding all branch option to select
            var newOption = new Option('All Branches', 'all', false, false);
                $('.branch_id').append(`<option selected></option`);
                $('.branch_id').append(newOption);
            data.forEach(element => {
                var newOption = new Option(element.branch_id + ' ' + element.branch_name, element.id, false, false);
                $('.branch_id').append(`<option selected></option`);
                $('.branch_id').append(newOption);
            });
        }
        // function getWinnerCustomers(branch_id, scheme_id, week) {
        //     $.ajaxSetup({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         }
        //     });
        //     $.ajax({
        //         type: 'GET',
        //         url: "/admin/branch/get-select-customers",
        //         data: {
        //             branch_id: branch_id,
        //             scheme_id: scheme_id,
        //             week: week,
        //         },
        //         success: function(data) {
        //             // console.log(data);
        //             setCustomers(data);
        //         },
        //         error: function(err) {
        //             if (err.responseJSON['errors']) {
        //                 let error = err.responseJSON['errors'];
        //                 var msg = '';
        //                 $.each(error, (i, j) => {
        //                     msg += j + '<br/>';
        //                 });
        //                 let errKeys = Object.keys(err.responseJSON['errors']);
        //                 errKeys.map((item) => {
        //                     $('[name=' + item + ']').addClass('is-invalid');
        //                 });
        //             }
        //             // swal(
        //             //     'Something went wrong!',
        //             //     msg,
        //             //     'error'
        //             // )
        //         }
        //     });
        // }
        function getWeek() {
            $('#week').empty();
            $('#customer_id').empty();
            var scheme_id = $("#scheme_id :selected").val();
            var branch_id = $("#branch_id :selected").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'GET',
                url: "/admin/branch/get-week",
                data: {
                    scheme_id: scheme_id,
                    branch_id: branch_id,
                },
                success: function(data) {
                    setSchemeDate(data);
                },
                error: function(err) {
                    if (err.responseJSON['errors']) {
                        let error = err.responseJSON['errors'];
                        var msg = '';
                        $.each(error, (i, j) => {
                            msg += j + '<br/>';
                        });
                        let errKeys = Object.keys(err.responseJSON['errors']);
                        errKeys.map((item) => {
                            $('[name=' + item + ']').addClass('is-invalid');
                        });
                    }
                    swal(
                        'Something went wrong!',
                        msg,
                        'error'
                    )
                }
            });
        }
        function getScheme() {
            $('#week').empty();
            $('#customer_id').empty();
            var branch_id = $("#branch_id :selected").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'GET',
                url: "/admin/branch/get-schemes",
                data: {
                    branch_id: branch_id,
                },
                success: function(data) {
                    // console.log(data);
                    setSchemes(data);
                },
                error: function(err) {
                    if (err.responseJSON['errors']) {
                        let error = err.responseJSON['errors'];
                        var msg = '';
                        $.each(error, (i, j) => {
                            msg += j + '<br/>';
                        });
                        let errKeys = Object.keys(err.responseJSON['errors']);
                        errKeys.map((item) => {
                            $('[name=' + item + ']').addClass('is-invalid');
                        });
                    }
                    swal(
                        'Something went wrong!',
                        msg,
                        'error'
                    )
                }
            });
        }
        // function setCustomers(data) {
        //     $('.customer_select2').empty();
        //     data.forEach(element => {
        //         var newOption = new Option(element.customer_id + ' ' + element.name, element.id, false, false);
        //         $('.customer_select2').append(`<option selected></option`);
        //         $('.customer_select2').append(newOption);
        //     });
        // }
        function setSchemes(data) {
            $('.scheme_select2').empty();
            $('.customer_select2').empty();
            data.forEach(element => {
                var scheme_id = element.scheme_a_id+'-'+element.scheme_n_id;
                var newOption = new Option(scheme_id + ' ' + element.name, element.id, false, false);
                $('.scheme_select2').append(`<option selected></option`);
                $('.scheme_select2').append(newOption);
            });
        }
        function setSchemeDate(data) {
            data.forEach((element, index) => {
                var newOption = new Option(element, (index + 1), false, false);
                $('.week_select2').append(`<option></option`);
                $('.week_select2').append(newOption);
            });
        }
    </script>
@endpush
