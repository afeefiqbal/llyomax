@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        {{-- <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" /> --}}
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
        <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
        <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">

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
                @if (isset($weeklyGift))
                    Edit Weekly Gifts
                @else
                    Create Weekly Gifts
                @endif
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Accounts </li>
                <li class="breadcrumb-item">Weekly Gifts</li>
                <li class="breadcrumb-item">
                    @if (isset($weeklyGift))
                        Edit Weekly Gifts
                    @else
                        Create Weekly Gifts
                    @endif
                </li>
            </ol>
        </div>
        <div>
            <a href="/admin/accounts/weekly-gifts">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i>Weekly Gifts List</button>
            </a>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="col-lg-12">
            <div class="card card-fullheight">
                <div class="card-body">
                    @if (isset($weeklyGift))
                        <form action="/admin/accounts/weekly-gifts/{{ $weeklyGift->id }}" method="PATCH" id="weekly-gifts-form">
                            @method('PATCH')
                        @else
                            <form action="/admin/accounts/weekly-gifts" method="POST" id="weekly-gifts-form">
                    @endif
                    @csrf
                    <div class="row">
                        <div class="col-sm-4 form-group mb-4">
                            <label>Scheme<span class="text-danger">*</span></label>
                            <select class="select2 col-sm-12 scheme_select2  form-group" id="scheme_id" name="scheme_id"  onchange="getWeek()"
                                >
                                <option selected value="">--select-an-option--</option>

                                    @foreach ($schemes as $scheme)
                                        <option value="{{ $scheme->id }}"
                                            @if (isset($weeklyGift) && $scheme->id == $scheme->id) {{ 'selected' }} @endif>
                                            {{ $scheme->scheme_id . '-' . $scheme->name }}</option>
                                    @endforeach
                            </select>
                            <sub class="text-danger scheme_id" for="scheme_id"></sub>
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label>Branches<span class="text-danger">*</span></label>
                            <select class="select2 col-sm-12 form-group "  id="branch_id"
                                name="branch_id" >
                                <option value="">--select-an-option--</option>
                                @isset($weeklyGift)
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            @if (isset($weeklyGift) && $branch->id == $weeklyGift->branch_id) {{ 'selected' }} @endif>
                                            {{ $branch->branch_id . '-' . $branch->branch_name }}</option>
                                    @endforeach
                                @endisset
                            </select>

                        </div>
                        <div class="col-sm-4 form-group mb-12">
                            <label>Scheme weeks<span class="text-danger">*</span></label>
                            <select class="select2 form-control week_select2" id="week" name="week"   onchange="getCustomers()">
                                <option selected value="">--select-an-option--</option>
                                @isset($weeklyGift)
                                @endisset
                            </select>
                            <span class="text-danger error-text week"></span>
                            {{-- <sub class="text-danger week" for="week"></sub> --}}
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label>Item Given From : </label>
                            <select class="select2 col-sm-12 form-group "  id="given_by" name="given_by" >
                                <option value="">--select-an-option--</option>
                                <option value="1" @if (isset($weeklyGift))
                                    @if ($weeklyGift->given_by == 1) {{ 'selected' }} @endif
                                @else
                                @endif>Branch</option>
                                <option value="2" @if (isset($weeklyGift))
                                @if ($weeklyGift->given_by == 2) {{ 'selected' }} @endif
                            @else
                            @endif>Shop</option>
                            </select>

                        </div>

                        <div class="col-sm-4 form-group mb-4">
                            <label>Customers<span class="text-danger">*</span></label>
                            <select class="select2 col-sm-12 form-group "  id="customer_id"
                                name="customer_id" >
                                <option value="">--select-an-option--</option>
                                @isset($weeklyGift)
                                @foreach ($customers as $customer)
                                     <option value="{{ $customer->id }}"
                                        @if (isset($weeklyGift) && $customer->id == $weeklyGift->customer_id) {{ 'selected' }} @endif>
                                        {{ $customer->customer_id . '-' . $customer->name }}</option>
                                @endforeach
                                @endisset
                            </select>

                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label for="">Gift Item</label>
                            <input type="text" name="gift_items" id="gift_items" class="form-control" value="{{ isset($weeklyGift) ? $weeklyGift->gift_items : '' }}">
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label for="">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control" value="{{ isset($weeklyGift) ? $weeklyGift->amount : '' }}">
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label>Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control datepicker" name="date" id="date" value="{{ isset($weeklyGift) ? $weeklyGift->date : '' }}"  >
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label for="">Upload Bills <span class="text-danger">*</span></label>
                            <input type="file" class="bill_doc" name="bill_doc" id="bill_doc">
                            @isset($weeklyGift)

                            <iframe src="{{$weeklyGift->getFirstMediaUrl('weeklyGifts','weeklyGift')}}" alt="" srcset="" height="200" width="100%"></iframe>
                            @endisset
                        </div>
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
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('backend/assets/vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>

    <!-- CORE SCRIPTS-->
    <script>

        $(document).ready(function() {
            getEditData();
            FilePond.registerPlugin(
                FilePondPluginFileValidateSize,
                FilePondPluginFileValidateType,
                FilePondPluginImagePreview,
                FilePondPluginFileEncode
            );

            $('.bill_doc').filepond({
                allowFileTypeValidation: true,
                allowFileSizeValidation: true,
                maxFileSize: '5MB',
                acceptedFileTypes: ['application/pdf','application/docs'],
                allowImagePreview: true,
                allowFileEncode: true,
                credits: false,

            });
            $('.select2').select2({
                placeholder: "Select an option",
            });

            $('#weekly-gifts-form').submit(function(e) {
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
                    data: $('#weekly-gifts-form').serialize(),
                    beforeSend: function() {
                        $(document).find('span.error-text').text('');
                    },
                    success: function(data) {
                        console.log(data);
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true,
                            "timeOut": "1500",
                        }
                        toastr.success(data.success);
                        setTimeout(function() {
                            // window.location.href = '/admin/accounts/weekly-gifts';
                        }, 1500);
                    },
                    error: function(err) {
                        if (err.responseJSON['errors']) {
                            let error = err.responseJSON['errors'];
                            var msg = '';
                            $.each(error, (i, j) => {
                                // $('.' + i).text(j);
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
            });
        });
        $('#scheme_id').on('change', function() {
            let scheme_id = $(this).val();
            let url = '/admin/get-branchs/';
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    scheme_id: scheme_id
                },
                success: function(data) {
                    setBranches(data.branches);
                }
            });

        });
        function getCustomers() {
            let branch_id = $('#branch_id').val();
            let scheme_id =$('#scheme_id').val();
            let week = $('#week').val();
            let url = '/admin/get-customers/';
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    branch_id: branch_id,
                    scheme_id: scheme_id,
                    week: week
                },
                success: function(data) {
                    setCustomers(data.customers);
                }
            });
        }
        function setBranches(data) {

            let html = '';
            data.map((item) => {
                html += `<option value="">--Select Options--</option>`;
                html += `<option value="${item.id}">${item.branch_id}-${item.branch_name}</option>`;
            });
            $('#branch_id').html(html);

        }
        function setCustomers(data){
            let html = '';
            data.map((item) => {
                html += `<option value="">--Select Options--</option>`;
                html += `<option value="${item.id}">${item.customer_id}-${item.name}</option>`;
            });
            $('#customer_id').html(html);
        }
        $('#branch_id').on('change', function() {
            // $('#week').empty();
            // $('#customer_id').empty();
        });
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
        function setSchemeDate(data) {
            data.forEach((element, index) => {
                var newOption = new Option(element, (index + 1), false, false);
                $('.week_select2').append(`<option></option`);
                $('.week_select2').append(newOption);
            });
        }
        function getEditData() {
            var scheme_id = $("#scheme_id :selected").val();
            var branch_id = $("#branch_id :selected").val();
            if(scheme_id){
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
            else{

            }
         }
    </script>
@endpush
