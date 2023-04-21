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
                @if (isset($deliveryBoy))
                    Edit Delivery Boy
                @else
                    Create Delivery Boy
                @endif
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item">Delivery Boy</li>
                <li class="breadcrumb-item">
                    @if (isset($deliveryBoy))
                        Edit Delivery Boy
                    @else
                        Create Delivery Boy
                    @endif
                </li>
            </ol>
        </div>
        <div>
            <a href="/admin/warehouse/delivery-executives">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i> Delivery Boys List</button>
            </a>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="col-lg-12">
            <div class="card card-fullheight">
                <div class="card-body">
                    @if (isset($deliveryBoy))
                    <form action="/admin/warehouse/delivery-executives/{{ $deliveryBoy->id }}" method="POST" id="deliveryBoy-form">
                        @method('PATCH')
                        @else
                            <form action="/admin/warehouse/delivery-executives" method="POST" id="deliveryBoy-form">
                    @endif
                    @csrf
                    <div class="row">

                        <div class="col-sm-4 form-group mb-4">
                            <label>Delivery boy  ID<span class="text-danger">*</span></label>
                            <input class="form-control"  type="text" id="delivery_boy_id" placeholder="Delivery boy  ID"
                                value="{{ isset($deliveryBoy) ? $deliveryBoy->delivery_boy_id : old('delivery_boy_id') }}" name="delivery_boy_id">
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label> Name<span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="name" placeholder=" Name"
                                value="{{ isset($deliveryBoy) ? $deliveryBoy->name : old('name') }}" name="name"  >

                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label> User Name<span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="username" placeholder=" user name"
                                value="{{ isset($deliveryBoy) ? $deliveryBoy->username : old('username') }}" name="username"  >

                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label> Email</label>
                            <input class="form-control" type="text" id="email" placeholder="Email"
                                value="{{ isset($deliveryBoy) ? $deliveryBoy->email : old('email') }}" name="email"  >
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label> Phone<span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="phone" placeholder="Phone no."
                                value="{{ isset($deliveryBoy) ? $deliveryBoy->phone : old('phone') }}" name="phone"  >
                        </div>
                        <div class="col-sm-4 form-group mb-4" {{ isset($deliveryBoy) ? 'hidden' : ''}}>
                            <label>Password<span class="text-danger">*</span></label>
                            <input class="form-control" type="password" id="password"
                                value="{{ isset($deliveryBoy) ? $deliveryBoy->password : old('password') }}" name="password"  >
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label for="">Place <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="place"
                                value="{{ isset($deliveryBoy) ? $deliveryBoy->place : old('place') }}" name="place"    >
                        </div>
                        <div class="col-sm-4 form-group mb-4" >
                            <label for="">Address <span class="text-danger">*</span></label>
                            <textarea name="address" id="address" cols="12" rows="3" class="form-control" >{{ isset($deliveryBoy) ? $deliveryBoy->address : old('address') }}</textarea>
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
            $('#name').on('keyup', function() {
                $('#username').val($(this).val());
            });
            $(".select2_dem").select2({
                   placeholder: "Select an option",
               });
            // $('#branch').on('change', function(e) {
            //     e.preventDefault();
            //     let branch = $("#branch :selected").val();
            //     $.ajaxSetup({
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         }
            //     });
            //     $.ajax({
            //         type: 'GET',
            //         url: "/admin/master/area-id",
            //         data: {
            //             branch: branch,
            //         },
            //         success: function(data) {
            //             console.log(data);
            //             $("#deliveryBoy_id").val(data);
            //         },
            //         error: function(err) {
            //             console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
            //         }
            //     }).always(function(jqXHR, textStatus) {
            //         if (textStatus != "success") {
            //             alert("Error: " + jqXHR.statusText);
            //         }
            //     });
            // });
            $(".select2").select2({
                placeholder: "Select an option",
            });
            $("#deliveryBoy-form").validate({
                rules: {
                    name: {
                        // minlength: 2,
                        // required: !0
                    },
                    area_id: {
                        // required: !0
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
            $('#deliveryBoy-form').submit(function(e) {
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
                    data: $('#deliveryBoy-form').serialize(),
                    success: function(data) {
                        @if (!isset($deliveryBoy))
                            swal(
                            'Success!',
                            'Delivery Boy has been added.',
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
                            ' Delivery Boy has been updated.',
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
                                $(e).addClass("is-invalid").removeClass('is-valid');
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
