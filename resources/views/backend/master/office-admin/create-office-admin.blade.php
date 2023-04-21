@extends('backend.layouts.backend')

@section('content')
    @push('styles')
        <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
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
                @if (isset($office))
                    Edit Office-Admin
                @else
                    Create Office-Admin
                @endif
            </h1>

            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item">Office Admin</li>
                <li class="breadcrumb-item">
                    @if (isset($office))
                        Edit Office Admin
                    @else
                        Create Office Admin
                    @endif
                </li>
            </ol>
        </div>
        <div>
            <a href="/admin/master/office-admins">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i>Office Admins List</button>
            </a>
        </div>
    </div>
    <!-- End Page Heading -->


    <div>
        <div class="col-lg-12">

            <div class="card card-fullheight">
                <div class="card-body">
                    @if (isset($office))
                        <form action="/admin/master/office-admins/{{ $office->id }}" method="PATCH" id="office-form">
                            @method('PATCH')
                        @else
                            <form action="/admin/master/office-admins" method="POST" id="office-form">
                    @endif
                    @csrf
                    <div class="row">

                        <div class="col-sm-6 form-group mb-4" id="branch-start">
                            <label>Branch</label>
                            <br>
                            <select class="select2_demo col-sm-12 form-group" id="branch_name" name="branch">
                                <option value=""></option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        @if (isset($office) && $office->branch_id == $branch->id) {{ 'selected' }} @endif>
                                        {{ $branch->branch_id }}-{{ $branch->branch_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 form-group mb-4">
                            <label>Name<span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="name" placeholder="Name" onkeyup="sync()" value="{{ isset($office) ? $office->name : old('name') }}" name="name">
                        </div>
                        <div class="col-sm-6 form-group mb-4" hidden>
                            <label>Username<span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="username" placeholder="username"  value="{{ isset($office) ? $office->username : old('username') }}" name="username">
                        </div>
                        <div class="col-sm-6 form-group mb-4">
                            <label>Email</label>
                            <input class="form-control" type="email" id="email" placeholder="Email" value="{{ isset($office) ? $office->email : old('email') }}" name="email">
                        </div>

                        <div class="col-sm-6 form-group mb-4">
                            <label>Mobile<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="mobile" placeholder="Mobile" value="{{ isset($office) ? $office->phone : old('mobile') }}">
                        </div>
                        <div class="col-sm-6 form-group mb-4">
                            <label>Address<span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" placeholder="Address" name="address">{{ isset($office) ? $office->address : old('address') }}</textarea>
                        </div>
                        @if (!isset($office))
                            <div class="col-sm-6 form-group mb-4">
                                <label>Password<span class="text-danger">*</span></label>
                                <input class="form-control" id="password" type="password" name="password" placeholder="password" />
                            </div>

                            <div class="col-sm-6 form-group mb-4">
                                <label>Confirm Password<span class="text-danger">*</span></label>
                                <input class="form-control" type="password" name="password_confirmation" placeholder="confirm password">
                            </div>
                        @endif
                        <div class="col-sm-6 form-group mb-4">
                            <label>Status<span class="text-danger">*</span></label>
                            <br>
                            <label class="ui-switch switch-solid"><input type="checkbox" checked id="status"  placeholder="Address" name="status"{{ isset($office) && $office->status ? 'checked' : old('status') }}><span></span></label>
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
    <script src="{{ asset('backend/assets/vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script><!-- CORE SCRIPTS-->
    <script>
        function sync() {
            var n1 = document.getElementById('name');
            var n2 = document.getElementById('username');
            n2.value = n1.value;
        }
        $(document).ready(function() {
            $(".select2_demo").select2({
                placeholder: "Select an option",
            });
            $("#office-form").validate({
                rules: {

                    name: {
                        minlength: 2,
                        required: !0
                    },
                    username: {
                        required: !0
                    },

                    mobile: {
                        required: !0,
                        number: !0,
                        minlength: 10
                    },
                    address: {
                        required: !0
                    },
                    password: {
                        required: !0,
                        minlength: 8
                    },
                    password_confirmation: {
                        required: !0,
                        minlength: 8,
                        equalTo: "#password"
                    }
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

            $('#office-form').submit(function(e) {
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
                    data: $('#office-form').serialize(),
                    success: function(data) {
                        @if (!isset($office))
                            swal(
                            'Success!',
                            'Office Admin has been added.',
                            'success'
                            ).then(()=>{
                            location.reload();
                            });
                            form.trigger('reset');
                        @else
                            swal(
                            'Success!',
                            'Office Admin has been updated.',
                            'success'
                            )
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
