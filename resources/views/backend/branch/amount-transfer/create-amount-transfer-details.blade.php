@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        {{-- <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" /> --}}
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
        <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
        <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
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
                @if (isset($executiveTarget))
                    Edit Transfer Amount Details
                @else
                    Create Transfer Amount Details
                @endif
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Branches </li>
                <li class="breadcrumb-item">Transfer Amount Details</li>
                <li class="breadcrumb-item">
                    @if (isset($executiveTarget))
                        Edit Transfer Amount Details
                    @else
                        Create Transfer Amount Details
                    @endif
                </li>
            </ol>
        </div>
        <div>
            @hasanyrole('super-admin|developer-admin|branch-manager')
            <a href="/admin/executive/amount-transfer">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i>Manager Transfer Amount Details List</button>
                @endhasanyrole
                @hasanyrole('collection-executive|marketing-executive')
                <a href="/admin/executive/amount-transfer-executive">
                    <button class="btn btn-primary"><i class="la la-arrow-left"></i>Executive Transfer Amount Details List</button>
             @endhasanyrole

            </a>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="col-lg-12">
            <div class="card card-fullheight">
                <div class="card-body">
                    @if (isset($amountTransferDetails))
                        <form action="/admin/branch/amount-transfer/{{ $amountTransferDetails->id }}" method="PATCH"
                            id="amountTransferDetails-form">
                            @method('PATCH')
                        @else
                            <form action="/admin/branch/amount-transfer" method="POST" id="amountTransferDetails-form">
                    @endif
                    @csrf
                    <div class="row">
                        <div class="col-sm-4 form-group mb-4">
                            <label>Branches<span class="text-danger">*</span></label>
                            @hasanyrole('super-admin|developer-admin')
                            <select class="select2 col-sm-12 form-group" id="branch_id" name="branch_id" required>
                                <option value="">--select-an-option--</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" @if (isset($amountTransferDetails) && $amountTransferDetails->executive->branch_id == $branch->id)
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
                            @hasanyrole('collection-executive|marketing-executive')
                                @php
                                    $user = auth()->user();
                                    $executive = \App\Models\Executive\Executive::where('user_id', $user->id)->first();
                                @endphp
                              <select class="select2 col-sm-12 form-group" id="branch_id" name="branch_id" required>
                                    @isset($branches)
                                        @foreach ($branches as $branch)
                                            @isset($executive)
                                                @if ($executive->branch_id == $branch->id)
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
                            <span class="text-danger error-text branch_id" for="branch_id"></span>
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label>Date<span class="text-danger">*</span></label>
                            <input type="date" class="col-sm-12 form-control" id="date" min="{{ date('Y-m-d') }}" name="date">
                            <span class="text-danger error-text date" for="date"></span>
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label>Transfer Amount<span class="text-danger">*</span></label>
                            <input type="text" class="col-sm-12 form-control" readonly  value="{{isset($transferAmount) ? $transferAmount : ''}}" id="transfer_amount" name="transfer_amount">
                            <span class="text-danger error-text transfer_amount" for="transfer_amount"></span>
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label>Transfer Time<span class="text-danger">*</span></label>
                            <input type="time" class="col-sm-12 form-control" id="transfer_time" name="transfer_time">
                            <span class="text-danger error-text transfer_time" for="transfer_time"></span>
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label class="radio radio-inline radio-primary"><input type="radio" name="transfer_type"
                                    value="1"
                                    {{ isset($amountTransferDetails) ? ($amountTransferDetails->type == 1 ? 'checked' : '') : 'checked' }}><span>By
                                    Hand</span></label>
                            <label class="radio radio-inline radio-primary"><input type="radio" name="transfer_type"
                                    value="2"
                                    {{ isset($amountTransferDetails) ? ($amountTransferDetails->type == 2 ? 'checked' : '') : '' }}><span>Bank</span></label>
                        </div>
                        <div class="col-sm-4 form-group mb-4" id="receipt_image">
                            <label>Recipt Image</label>
                            <input type="file" class="receipt_image" name="receipt_image"
                                value="{{ isset($scheme) ? $scheme->image : old('receipt_image') }}">
                            <label id="receipt_image-error" class=" invalid-feedback active receipt_image"
                                for="receipt_image"></label>
                        </div>
                    </div>
                    @hasanyrole('collection-executive|marketing-executive')
                    @php
                        $user = auth()->user();
                        $executive = \App\Models\Executive\Executive::where('user_id', $user->id)->first();
                    @endphp
                    <input type="hidden" name="executive_id" value="{{$executive->id}}" id="executive_id">
                     @endhasanyrole
                    <div class="row">
                        <div class="form-group">
                            <button class="btn btn-primary mr-2" id="submitForm" type="submit">Submit</button>
                            <button class="btn btn-light" type="reset">Clear</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
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
        $('#receipt_image').hide();
        $('input[type=radio][name=transfer_type]').change(function() {
            changeTransferType();
        });

        function changeTransferType() {
            var res_val = $('input[type=radio][name=transfer_type]:checked').val();
            if (res_val == '1') {
                $('#receipt_image').hide();
            } else if (res_val == '2') {
                $('#receipt_image').show();
            }
        }
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select an option",
            });
            FilePond.registerPlugin(
                FilePondPluginFileValidateSize,
                FilePondPluginFileValidateType,
                FilePondPluginImagePreview,
                FilePondPluginFileEncode
            );
            $('.receipt_image').filepond({
                allowFileTypeValidation: true,
                allowFileSizeValidation: true,
                maxFileSize: '5MB',
                acceptedFileTypes: ['image/*'],
                allowImagePreview: true,
                allowFileEncode: true,
                credits: false,
                @if (isset($amountTransferDetails) && $amountTransferDetails->hasMedia('receipt_images'))
                    files: [{
                    source: "{{ $amountTransferDetails->getFirstMediaUrl('receipt_images') }}",
                    }],
                @endif
            });
            $('#amountTransferDetails-form').submit(function(e) {
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
                    data: $('#amountTransferDetails-form').serialize(),
                    beforeSend: function() {
                        $(document).find('span.error-text').text('');
                    },
                    success: function(data) {
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
                    }
                });
            });
        });
    </script>
@endpush
