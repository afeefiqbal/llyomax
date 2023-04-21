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
                @if (isset($expenseBill))
                    Edit Expense bill
                @else
                    Create Expense bill
                @endif
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Accounts </li>
                <li class="breadcrumb-item">Expense bill</li>
                <li class="breadcrumb-item">
                    @if (isset($expenseBill))
                        Edit Expense bill
                    @else
                        Create Expense bill
                    @endif
                </li>
            </ol>
        </div>
        <div>
            <a href="/admin/accounts/expense-bills">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i>Expense bill List</button>
            </a>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="col-lg-12">
            <div class="card card-fullheight">
                <div class="card-body">
                    @if (isset($expenseBill))
                        <form action="/admin/accounts/expense-bills/{{ $expenseBill->id }}" method="PATCH" id="expense-bills-form">
                            @method('PATCH')
                        @else
                            <form action="/admin/accounts/expense-bills" method="POST" id="expense-bills-form">
                    @endif
                    @csrf
                    <div class="row">
                        <div class="col-sm-4 form-group mb-4">
                            <label>Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control datepicker" name="date" id="date" value="{{ isset($expenseBill) ? $expenseBill->date : '' }}"  >
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label class="form-control-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="amount" value="{{ $expenseBill->amount ?? old('amount') }}" placeholder="Enter amount" required>
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label class="form-control-label">Electricity Bill :     <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="electricity_bill" value="{{ $expenseBill->electricity_bill ?? old('electricity_bill') }}" placeholder="Enter electricity bill no." required>
                        </div>
                        <div class="col-sm-6 form-group mb-4">
                            <label for="">Upload Bills <span class="text-danger">*</span></label>
                            <input type="file" class="bill_doc" name="bill_doc" id="bill_doc">
                            @isset($expenseBill)

                            <iframe src="{{$expenseBill->getFirstMediaUrl('bills','bill')}}" alt="" srcset="" height="200" width="400px"></iframe>
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

            $('#expense-bills-form').submit(function(e) {
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
                    data: $('#expense-bills-form').serialize(),
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
                            // window.location.href = '/admin/accounts/expense-bills';
                        }, 1500);
                    },
                    error: function(err) {
                        if (err.responseJSON['errors']) {
                            let error = err.responseJSON['errors'];
                            var msg = '';
                            $.each(error, (i, j) => {
                                $('.' + i).text(j);
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

    </script>
@endpush
