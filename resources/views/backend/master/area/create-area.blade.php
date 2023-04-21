@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
        <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
        <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
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
                @if (isset($area))
                    Edit Area
                @else
                    Create Area
                @endif
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item">Area</li>
                <li class="breadcrumb-item">
                    @if (isset($area))
                        Edit Area
                    @else
                        Create Area
                    @endif
                </li>
            </ol>
        </div>
        <div>
            <a href="/admin/master/areas">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i> Areas List</button>
            </a>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="col-lg-12">
            <div class="card card-fullheight">
                <div class="card-body">
                    @if (isset($area))
                        <form action="/admin/master/areas/{{ $area->id }}" method="PATCH" id="area-form">
                            @method('PATCH')
                        @else
                            <form action="/admin/master/areas" method="POST" id="area-form">
                    @endif
                    @csrf
                    <div class="row">
                        <div class="col-sm-4" id="branch-start">
                            <label>Districts</label>

                            <br>
                            <select class=" col-sm-12 select2 form-group"  name="district_id" id="district_id">
                                <option value=""></option>
                               @foreach ($districts as $district)
                                    <option value="{{ $district->id }}"
                                        @if (isset($area) && $area->district->id == $district->id)) {{ 'selected' }} @endif>
                                        {{ $district->district_id }}</option>
                                @endforeach
                              </select>
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label>Area ID<span class="text-danger">*</span></label>
                            <input class="form-control"  type="text" id="area_id" placeholder="Area ID"
                                value="{{ isset($area) ? $area->area_id : old('area_id') }}" name="area_id">
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label>Area Name<span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="area" placeholder="Area Name"
                                value="{{ isset($area) ? $area->name : old('name') }}" name="name" required>
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
    <script src="{{ asset('backend/assets/vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <!-- CORE SCRIPTS-->
    <script>

        $(document).ready(function() {
            $(".select2_dem").select2({
                   placeholder: "Select an option",
               });
            $('#branch').on('change', function(e) {
                e.preventDefault();
                let branch = $("#branch :selected").val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'GET',
                    url: "/admin/master/area-id",
                    data: {
                        branch: branch,
                    },
                    success: function(data) {
                        console.log(data);
                        $("#area_id").val(data);
                    },
                    error: function(err) {
                        console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
                    }
                }).always(function(jqXHR, textStatus) {
                    if (textStatus != "success") {
                        alert("Error: " + jqXHR.statusText);
                    }
                });
            });
            $(".select2").select2({
                placeholder: "Select an option",
            });
            $("#area-form").validate({
                rules: {
                    name: {
                        minlength: 2,
                        required: !0
                    },
                    area_id: {
                        required: !0
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
            $('#area-form').submit(function(e) {
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
                    data: $('#area-form').serialize(),
                    success: function(data) {
                        @if (!isset($area))
                            swal(
                            'Success!',
                            'Area has been added.',
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
                            ' Area has been updated.',
                            'success'
                            ).then(()=>{
                            setTimeout(function() {
                            window.location = document.referrer;
                            }, 1000);
                            });
                        @endif
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
            });
        });
    </script>
@endpush
