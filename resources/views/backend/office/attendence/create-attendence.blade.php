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
                @if (isset($staffs))
                    Edit Attendance
                @else
                    Create Attendance
                @endif
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Office-Admins</li>
                <li class="breadcrumb-item">Attendance</li>
                <li class="breadcrumb-item">
                    @if (isset($staffs))
                        Edit Attendance
                    @else
                        Create Attendance
                    @endif
                </li>
            </ol>
        </div>
        <div>
            <a href="/admin/office-admin/attendances">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i>Attendance List</button>
            </a>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="col-lg-12">
            <div class="card card-fullheight">
                <div class="card-body">
                    @if (isset($staffs))
                        <form action="/admin/office-admin/attendances/update/{{ $staffs->id }}" method="post"
                            id="attendance-form">
                            @method('post')
                        @else
                            <form action="/admin/office-admin/attendances" method="POST" id="attendance-form">
                    @endif
                    @csrf
                    <div class="row">
                        <div class="col-sm-6 form-group mb-4" id="branch-start">
                            <label>Branch<span class="text-danger">*</span></label>
                            <br>
                            @hasanyrole('super-admin|developer-admin')
                                <select class="select2_demo col-sm-12 form-group" @if (isset($staffs))
                                    readonly="readonly"
                                    @endif id="branch" name="branch">
                                    <option value=""></option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" @if (isset($staffs) && $branch_id == $branch->id)
                                            {{ 'selected' }}
                                    @endif>{{ $branch->branch_id }}-{{ $branch->branch_name }}</option>
                                    @endforeach
                                </select>
                            @endhasanyrole
                            @role('office-administrator')
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
                            @endrole
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 form-group mb-4" id="attendance">
                            <label>Attendance<span class="text-danger">*</span></label>
                            <div id="staff">
                            </div>
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Staff Name</th>
                                        <th scope="col">Designation</th>
                                        <th scope="col">Present</th>
                                        <th scope="col">Late</th>
                                    </tr>
                                </thead>
                                <tbody class="attendance-row">
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-6 form-group mb-4">
                            <br>
                            <a class="btn btn-primary" style="color: white;" id="selectall">Mark All</a>
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
            $("#selectall").click(function() {
                $(".radioP").attr("checked", "checked");
            });
            // $("#selectall").dblclick(function() {
            //     $(".radioR").removeAttr("checked", "checked");
            // });
            @if (isset($staffs))
                {
                $('select[readonly="readonly"] option:not(:selected)').attr('disabled',true);
                }
            @endif
            $('#branch').on('change', function() {
                let branch = $("#branch :selected").val();
                getData(branch);
            });
            $(".select2_demo").select2({
                placeholder: "Select an option",
            });
            $("#attendance-form").validate({
                rules: {
                    branch: {
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
            $('#attendance-form').submit(function(e) {
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
                    data: $('#attendance-form').serialize(),
                    success: function(data) {
                        if (data.warning) {
                            swal(
                                'Entry Found!',
                                'Attendance for the day already entered.',
                                'warning'
                            )
                        } else {
                            @if (!isset($attendance))
                                swal(
                                'Success!',
                                'Attendance has been added.',
                                'success'
                                ,2000)
                                form.trigger('reset');
                                $("#branch").val("");
                                $("#table").remove();
                                setTimeout(() => {

                                    window.location = document.referrer;
                                }, 2000);
                            @else
                                swal(
                                'Success!',
                                'Attendance has been updated.',
                                'success'
                                )
                            @endif
                        }
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
                    url: "/admin/office-admin/get-staff",
                    data: {
                        branch: branch,
                    },
                    success: function(data) {
                        var i = 1;
                        $('.attendance-row').empty();
                        setAttandance(data)
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

        function setAttandance(data) {
            var i = 1;
            data.forEach(element => {
                var c = '';
                var d = '';
                if (element.attendance != null) {
                    if (element.attendance.late === 1) {
                        element.attendance.late = 'checked ';
                        c = element.attendance.late;
                    }
                    if (element.attendance.attendance == 1) {
                        element.attendance.attendance = 'checked';
                        d = element.attendance.attendance;
                    } else {
                        element.attendance = '';
                        c = element.attendance;
                        d = element.attendance;
                    }
                }
                $('.attendance-row').append(`<tr>
                                            <th scope="row">` + (i++) + `</th>
                                            <td>` + element.name + `</td>
                                            <td>` + element.designation +
                    `</td>
                                            <td><label class="checkbox checkbox-outline-primary checkbox-circle"><input id="staff" type="checkbox" ` + d +
                    `  true class="check radioP" name="attendance-` +
                    element.id + `" value="` + element.id +
                    `"><span></span></label></td>
                                            <td><label class="checkbox checkbox-outline-primary checkbox-circle"><input  id="staff" type="checkbox" ` +
                    c + `    class="check radioR" name="late-` +
                    element.id + `" value="` + element.id + `"><span></span></label></td>
                                        </tr>`);
            });
        }
    </script>
@endpush
