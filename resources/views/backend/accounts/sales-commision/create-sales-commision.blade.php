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
                @if (isset($salesCommision))
                    Edit Sales Commision
                @else
                    Create Sales Commision
                @endif
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Accounts </li>
                <li class="breadcrumb-item">Sales Commision</li>
                <li class="breadcrumb-item">
                    @if (isset($salesCommision))
                        Edit Sales Commision
                    @else
                        Create Sales Commision
                    @endif
                </li>
            </ol>
        </div>
        <div>
            <a href="/admin/accounts/sales-commisions">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i>Sales Commisions List</button>
            </a>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="col-lg-12">
            <div class="card card-fullheight">
                <div class="card-body">
                    @if (isset($salesCommision))
                        <form action="/admin/accounts/sales-commisions/{{ $salesCommision->id }}" method="PATCH" id="sales-commisions-form">
                            @method('PATCH')
                        @else
                            <form action="/admin/accounts/sales-commisions" method="POST" id="sales-commisions-form">
                    @endif
                    @csrf
                    <div class="row">
                        <div class="col-sm-4 form-group mb-4">
                            <label for="">Manager <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="manager_id" id="manager_id">
                                <option value="">Select Manager</option>
                                @foreach ($managers as $manager)
                                    <option value="{{ $manager->id }}" {{ isset($salesCommision) && $salesCommision->manager_id == $manager->id ? 'selected' : '' }}>{{ $manager->manager_id.'-'.$manager->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label for="">Month <span class="text-danger">*</span></label>
                            <select name="monthly" id="monthly" class="form-control select2">
                                <option value=""></option>
                                <option value="1" {{ isset($salesCommision) && $salesCommision->monthly == 1 ? 'selected' : '' }} >January</option>
                                <option value="2" {{ isset($salesCommision) && $salesCommision->monthly == 2 ? 'selected' : '' }}>February</option>
                                <option value="3" {{ isset($salesCommision) && $salesCommision->monthly == 3 ? 'selected' : '' }}>March</option>
                                <option value="4" {{ isset($salesCommision) && $salesCommision->monthly == 4 ? 'selected' : '' }}>April</option>
                                <option value="5" {{ isset($salesCommision) && $salesCommision->monthly == 5 ? 'selected' : '' }}>May</option>
                                <option value="6" {{ isset($salesCommision) && $salesCommision->monthly == 6 ? 'selected' : '' }}>June</option>
                                <option value="7" {{ isset($salesCommision) && $salesCommision->monthly == 7 ? 'selected' : '' }}>July</option>
                                <option value="8" {{ isset($salesCommision) && $salesCommision->monthly == 8 ? 'selected' : '' }}>August</option>
                                <option value="9" {{ isset($salesCommision) && $salesCommision->monthly == 9 ? 'selected' : '' }}>September</option>
                                <option value="10"{{ isset($salesCommision) && $salesCommision->monthly == 10 ? 'selected' : '' }}>October</option>
                                <option value="11"{{ isset($salesCommision) && $salesCommision->monthly == 11 ? 'selected' : '' }}>November</option>
                                <option value="12"{{ isset($salesCommision) && $salesCommision->monthly == 12 ? 'selected' : '' }}>December</option>
                            </select>
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label>From Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control datepicker" name="from_date" id="from_date" value="{{ isset($salesCommision) ? $salesCommision->from_date : '' }}"  >
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label>To Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control datepicker" name="to_date" id="to_date" value="{{ isset($salesCommision) ? $salesCommision->to_date : '' }}"  >
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label for="">   amount <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="amount" id="amount" value="{{ isset($salesCommision) ? $salesCommision->amount : '' }}">
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

            $('#sales-commisions-form').submit(function(e) {
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
                    data: $('#sales-commisions-form').serialize(),
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
