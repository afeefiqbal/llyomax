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
                @if (isset($staff))
                    Edit Staff
                @else
                    Create Staff
                @endif
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Office-Admins</li>
                <li class="breadcrumb-item">Staff</li>
                <li class="breadcrumb-item">
                    @if (isset($staff))
                        Edit Staff
                    @else
                        Create Staff
                    @endif
                </li>
            </ol>
        </div>
        <div>
            <a href="/admin/office-admin/staffs">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i> Staffs List</button>
            </a>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="col-lg-12">
            <div class="card card-fullheight">
                <div class="card-body">
                    @if (isset($staff))
                        <form action="/admin/office-admin/staffs/{{ $staff->id }}" method="PATCH" id="staff-form">
                            @method('PATCH')
                        @else
                            <form action="/admin/office-admin/staffs" method="POST" id="staff-form">
                    @endif
                    @csrf
                    <div class="row">
                        <div class="col-sm-6 form-group mb-4" id="branch-start">
                            <label>Branch<span class="text-danger">*</span></label>
                            <br>
                            @hasanyrole('super-admin|developer-admin')
                                <select class="select2_demo col-sm-12 form-group" id="branch" name="branch">
                                    <option value=""></option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" @if (isset($staff) && $staff->branch_id == $branch->id)
                                            {{ 'selected' }}
                                    @endif>{{ $branch->branch_id }}-{{ $branch->branch_name }}</option>
                                    @endforeach
                                </select>
                            @endhasanyrole
                            @hasanyrole('office-administrator')
                                @php
                                    $user = auth()->user();
                                    $officeadmin = \App\Models\Master\OfficeAdmin::where('user_id', $user->id)->first();
                                @endphp
                                <select class="select2_demo col-sm-12 form-group" id="branch" name="branch">
                                    @isset($branches)
                                        @foreach ($branches as $branch)
                                            @isset($officeadmin)
                                                @if ($officeadmin->branch_id == $branch->id)
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
                            @hasanyrole('branch-manager')
                                @php
                                    $user = auth()->user();
                                    $manager = \App\Models\Master\Manager::where('user_id', $user->id)->first();
                                @endphp
                                <select class="select2_demo col-sm-12 form-group" id="branch" name="branch">
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
                        </div>
                        <div class="col-sm-6 form-group mb-4">
                            <label>Staff ID<span class="text-danger">*</span></label>
                            <input class="form-control" readonly type="text" id="staff_id" placeholder=" staff ID"
                                value="{{ isset($staff) ? $staff->staff_id : '' }}" name="staff_id">
                        </div>
                        <div class="col-sm-6 form-group mb-4">
                            <label>Staff Name<span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="staff_name" placeholder=" staff Name"
                                value="{{ isset($staff) ? $staff->name : old('name') }}" name="name">
                        </div>
                        <div class="col-sm-6 form-group mb-4">
                            <label>Designation<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="designation" placeholder="Designation"
                                value="{{ isset($staff) ? $staff->designation : old('designation') }}">
                        </div>
                        <div class="col-sm-6 form-group mb-4">
                            <label>Mobile<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="mobile" placeholder="Mobile"
                                value="{{ isset($staff) ? $staff->phone : old('mobile') }}">
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
            var branch = $("#branch :selected").val();;
            if (branch != '') {
                getData(branch);
            }
            $('#branch').on('change', function() {
                let branch = $("#branch :selected").val();
                getData(branch);
            });
            $(".select2_demo").select2({
                placeholder: "Select an option",
            });
            $("#staff-form").validate({
                rules: {
                    name: {
                        minlength: 2,
                        required: !0
                    },
                    branch: {
                        required: !0
                    },
                    mobile: {
                        required: !0,
                        number: !0,
                        minlength: 10
                    },
                    designation: {
                        required: !0,
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
            $('#staff-form').submit(function(e) {
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
                    data: $('#staff-form').serialize(),
                    success: function(data) {
                        @if (!isset($staff))
                            swal(
                            'Success!',
                            ' staff has been added.',
                            'success'
                            )
                            form.trigger('reset');
                            $("#manager").val("").trigger( "change" );
                        @else
                            swal(
                            'Success!',
                            ' staff has been updated.',
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

        function getData(branch) {
            if (branch) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'GET',
                    url: "/admin/office-admin/staff-id",
                    data: {
                        branch: branch,
                    },
                    success: function(data) {
                        $("#staff_id").val(data);
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
        }
    </script>
@endpush
