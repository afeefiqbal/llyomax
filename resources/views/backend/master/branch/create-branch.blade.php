@extends('backend.layouts.backend')

@section('content')

@push('styles')
    <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
    <style>
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #888 transparent transparent transparent;
            border-style: solid;
            border-width: 5px 4px 0 4px;
            height: 0;
            left: 50%;
            margin-left: -10px;
            margin-top: 10px;
            position: absolute;
            top: 50%;
            width: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 9px;
        }

    </style>
@endpush

<!-- BEGIN: Page heading-->
<div class="page-heading">
    <div class="page-breadcrumb">
        <h1 class="page-title page-title-sep">
            @if(isset($branch))
                Edit Branch
            @else
                Create Branch
            @endif
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Master</li>
            <li class="breadcrumb-item">Branch</li>
            <li class="breadcrumb-item">
                @if(isset($branch))
                Edit Branch
                @else
                Create Branch
                @endif
            </li>
        </ol>
    </div>
    <div>
        <a href="/admin/master/branches">
            <button class="btn btn-primary"><i class="la la-arrow-left"></i> Branches List</button>
        </a>
    </div>
</div>
<!-- End Page Heading -->


<div>
    <div class="col-lg-12">

        <div class="card card-fullheight">
            <div class="card-body">
                @if(isset($branch))
                    <form action="/admin/master/branches/{{ $branch->id }}" method="PATCH" id="branch-form">
                        @method('PATCH')
                    @else
                        <form action="/admin/master/branches" method="POST" id="branch-form">
                @endif
                @csrf
                <div class="row">

                    <div class="col-sm-6 form-group mb-4">
                        <label>Branch ID<span class="text-danger">*</span></label>
                        <input class="form-control" readonly type="text" id="branch_id" placeholder=" Branch ID"
                            value="{{ isset($branch)? $branch->branch_id : '' }}"
                            name="branch_id">
                    </div>
                    <div class="col-sm-6 form-group mb-4">
                        <label>Branch Name<span class="text-danger">*</span></label>
                        <input class="form-control" type="text" id="branch_name" placeholder=" Branch Name"
                            value="{{ isset($branch)? $branch->branch_name : old('name') }}"
                            name="name">
                    </div>
                    <div class="col-sm-6 form-group mb-4">
                        <label>District<span class="text-danger">*</span></label>
                        @if (isset($branch))
                        <input type="text" readonly class="form-control" id="district" name="district" value="{{ $branch->district }}">
                        @else
                        <select class="select2_demo form-control" id="district_id" name="district_id">
                            <option></option>
                            @foreach ($districts as $district)
                            <option value="{{ $district->id }}">{{ $district->district_id }}</option>
                            @endforeach
                        </select>
                        @endif
                    </div>
                    <div class="col-sm-6 form-group mb-4">
                        <label>Address<span class="text-danger">*</span></label>
                        <textarea class="form-control" id="branch_address" placeholder="Address"
                            name="address">{{ isset($branch)? $branch->address : old('address') }}</textarea>
                    </div>
                    <div class="col-sm-6 form-group mb-4">
                        <label>Place<span class="text-danger">*</span></label>
                        <input class="form-control" type="text" id="place" placeholder="Place"
                            value="{{ isset($branch)? $branch->place : old('place') }}"
                            name="place">
                    </div>
                    <div class="col-sm-6 form-group mb-4">
                        <label>Mobile<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="mobile" placeholder="Mobile"
                            value="{{ isset($branch)? $branch->mobile : old('mobile') }}">
                    </div>
                    <div class="col-sm-6 form-group mb-4">
                        <label>Branch Image</label>
                        <input type="file" class="branch_image" name="branch_image"
                            value="{{ isset($branch)? $branch->image : old('branch_image') }}">
                    </div>
                    <div class="col-sm-6 form-group mb-4">
                        <label>Status<span class="text-danger">*</span></label>
                        <br>
                        <label class="ui-switch switch-solid"><input type="checkbox" id="status" checked
                            name="status" {{ isset($branch) && $branch->status ? 'checked' : old('status') }}><span></span></label>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary mr-2"
                       id="submitForm" type="submit">Submit</button>
                    <button class="btn btn-light" type="reset">Clear</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="https://pqina.github.io/filepond-plugin-image-crop/dist/filepond-plugin-image-crop.js"></script>
    <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>
    <script src="{{ asset('backend/assets/vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/jquery-validation/dist/jquery.validate.min.js')}}"></script><!-- CORE SCRIPTS-->
    <script>
        $(document).ready(function () {
            $('#district_id').on('change', function(e) {
                e.preventDefault();
                let id='';
                let district =   $("#district_id :selected").val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'GET',
                    url: "/admin/master/branch-id",
                    data: {
                        district: district,
                    },
                    success: function (data) {
                        $("#branch_id").val(data);
                    },
                    error: function (err) {


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
            });
            FilePond.registerPlugin(
                FilePondPluginFileValidateSize,
                FilePondPluginFileValidateType,
                FilePondPluginImagePreview,
                FilePondPluginFileEncode,
                FilePondPluginImageCrop
            );

            $('.branch_image').filepond({
                allowImageCrop : true,

                allowFileTypeValidation: true,
                allowFileSizeValidation: true,
                maxFileSize: '5MB',
                acceptedFileTypes: ['image/*'],
                allowImagePreview: true,
                allowFileEncode: true,
                credits: false,
                @if(isset($branch) && $branch->hasMedia('branch_images'))
                files: [{
                source: "{{$branch->getFirstMediaUrl('branch_images')}}",
                }],
                @endif
            });
            $(".select2_demo").select2({
                placeholder: "Select an option",
            });

             $("#branch-form").validate({
            rules: {
                name: {
                    minlength: 2,
                    required: !0
                },
                address: {
                    required: !0
                },
                place: {
                    required: !0
                },
                district: {
                    required: !0
                },
                email: {
                    required: !0,
                    email: !0
                },
                mobile: {
                    required: !0,
                    number: !0,
                    minlength: 10
                },
            },
            errorClass: 'invalid-feedback',
            validClass: 'valid-feedback',
            highlight: function(e) {
                $(e).addClass("is-invalid").removeClass('is-valid');
            },
            unhighlight: function(e) {
                $(e).removeClass("is-invalid").addClass('is-valid');
            },
        });

            $('#branch-form').submit(function (e) {
                e.preventDefault();
                let form = $(this);
                if (!form.valid()) return false;
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
                    data:$('#branch-form').serialize(),
                    beforeSend: function() {
                        $(document).find('span.error-text').text('');
                    },
                    success: function (data) {
                        @if(!isset($branch))
                        swal(
                        'Success!',
                        ' Branch has been added.',
                        'success'
                        ).then(()=>{
                            setTimeout(function() {
                            window.location = document.referrer;
                        }, 1000);
                            });
                        form.trigger('reset');
                        @else
                        swal(
                            'Success!',
                            ' Branch has been updated.',
                            'success'
                            )
                        @endif
                    },
                    error: function (err) {
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
            });

        });
    </script>
@endpush
