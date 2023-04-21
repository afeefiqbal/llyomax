@extends('backend.layouts.backend')
@section('content')

    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    {{-- <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" /> --}}
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #444;
    line-height: 11px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    top: 15px;
}
</style>
    @endpush

    <!-- BEGIN: Page heading-->
        <div class="page-heading">
            <div class="page-breadcrumb">
                <h1 class="page-title page-title-sep">
                    @if (isset($manager))
                        Edit Manager
                    @else
                        Create Manager
                    @endif
                </h1>

                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">Master</li>
                    <li class="breadcrumb-item">Manager</li>
                    <li class="breadcrumb-item">
                        @if (isset($manager))
                            Edit Manager
                        @else
                            Create Manager
                        @endif
                    </li>
                </ol>
            </div>
            <div>
                <a href="/admin/master/managers">
                    <button class="btn btn-primary"><i class="la la-arrow-left"></i> Managers List</button>
                </a>
            </div>
        </div>
    <!-- End Page Heading -->

    <div>
        <div class="col-lg-12">

            <div class="card card-fullheight">
                <div class="card-body">
                    @if (isset($manager))
                        <form action="/admin/master/managers/{{ $manager->id }}" method="PATCH" id="manager-form">
                            @method('PATCH')
                        @else
                            <form action="/admin/master/managers" method="POST" id="manager-form">
                    @endif
                    @csrf
                    <div class="row">

                        <div class="col-sm-6 form-group mb-4">
                            <label>Name<span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="name" placeholder=" Manager Name"
                                value="{{ isset($manager) ? $manager->name : old('name') }}" name="name" onkeyup="sync()">
                            <label id="manager_name-error" class="name invalid-feedback active" for="manager_name"></label>
                        </div>
                        <div class="col-sm-6 form-group mb-4" hidden>
                            <label>Username</label>
                            <input class="form-control" type="text" id="username" placeholder=" Manager username"
                                value="{{ isset($manager) ? $manager->username : old('username') }}" name="username">
                            <label id="manager_username-error" class=" invalid-feedback active username"
                                for="manager_username"></label>
                        </div>
                        <div class="col-sm-6 form-group mb-4">
                            <label>Email</label>
                            <input class="form-control" type="email" id="email" placeholder="Email"

                                value="{{ isset($manager) ? $manager->email : old('email') }}" name="email">
                            <label id="manager_email-error" class=" invalid-feedback active email"
                                for="manager_email"></label>
                        </div>

                        <div class="col-sm-6 form-group mb-4">
                            <label>Mobile<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="mobile" placeholder="Mobile"
                                value="{{ isset($manager) ? $manager->mobile : old('mobile') }}">
                            <label id="manager_mobile-error" class=" invalid-feedback active mobile"
                                for="manager_mobile"></label>
                        </div>
                        <div class="col-sm-6 form-group mb-4">
                            <label>Manager Type<span class="text-danger">*</span></label>
                            <select class="select2_demo form-control" id="manager_type" name="type">

                                <option value="1" @if (isset($manager) && $manager->type == 1)
                                    {{ 'selected' }}
                                    @endif>Branch Manager</option>
                                <option value="0" @if (isset($manager) && $manager->type == 0)
                                    {{ 'selected' }}
                                    @endif>Marketing Manager</option>
                                <option value="2" @if (isset($manager) && $manager->type == 2)
                                    {{ 'selected' }}
                                    @endif>Collection Manager</option>
                            </select>
                            <label id="manager_type-error" class=" invalid-feedback active type" for="manager_type"></label>
                        </div>
                        <div class="col-sm-6 form-group mb-4" id="branch-start">
                            <label>Branch<span class="text-danger ">*</span></label>
                            <br>
                            <select class="select2_demo col-sm-12 form-group" id="branch_name" name="branch">
                                <option value=""></option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" @if (isset($manager) && $manager->branch_id == $branch->id)
                                        {{ 'selected' }}
                                @endif>{{ $branch->branch_id }}-{{ $branch->branch_name }}
                                </option>
                                @endforeach
                            </select>
                            <label id="branch_id-error" class=" invalid-feedback active branch_id" for="branch_id"></label>

                        </div>
                        @if (!isset($manager))
                            <div class="col-sm-6 form-group mb-4">
                                <label>Password<span class="text-danger ">*</span></label>
                                <input class="form-control" id="password" type="password" name="password"
                                    placeholder="password" />
                                <label id="password-error" class=" invalid-feedback active password" for="password"></label>
                            </div>

                            <div class="col-sm-6 form-group mb-4">
                                <label>Confirm Password<span class="text-danger">*</span></label>
                                <input class="form-control" type="password" name="password_confirmation"
                                    placeholder="confirm password">
                                <label id="password_confirmation-error"
                                    class=" invalid-feedback active password_confirmation"
                                    for="password_confirmation"></label>

                            </div>
                        @endif
                        <div class="col-sm-6 form-group mb-4">
                            <label>Status<span class="text-danger">*</span></label>
                            <br>
                            <label class="ui-switch switch-solid"><input type="checkbox" id="status" checked name="status"
                                    {{ isset($manager) && $manager->status ? 'checked' : old('status') }}><span></span></label>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <!-- CORE SCRIPTS-->
    <script>
        function sync()
{
  var n1 = document.getElementById('name');
  var n2 = document.getElementById('username');
  n2.value = n1.value;
}
        $(document).ready(function() {
            @if (isset($manager))
                {
                let manager = $("#manager_type :selected").val();
                if (manager === '1') {
                    $('#branch-start').show();

                } if(manager === '2'){
                    $('#branch-start').hide();
                }
                if(manager === '0'){
                    $('#branch-start').hide();
                }
                }
            @endif
            $('#manager_type').on('change', function(e) {
                e.preventDefault();
                let manager = $("#manager_type :selected").val();
                if (manager === '1') {
                    $('#branch-start').show();

                } if(manager === '2'){
                    $('#branch-start').hide();
                }
                if(manager === '0'){
                    $('#branch-start').hide();
                }
            });
            $(".select2_demo").select2({
                placeholder: "Select an option",
            });
            $("#manager_type").select2({
                placeholder: "Select an option",
            });
            $("#manager-form").validate({
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
                    type: {
                        required: !0
                    },
                    branch: {
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

            $('#manager-form').submit(function(e) {
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
                    data: $('#manager-form').serialize(),
                    beforeSend: function() {
                        $(document).find('label.invalid-feedback').text('');
                        $(document).find('input.is-invalid').removeClass('is-invalid');
                    },
                    success: function(data) {
                        @if (!isset($manager))
                            swal(
                            'Success!',
                            ' Manager has been added.',
                            'success'
                            ).then(()=>{
                            location.reload();
                            });
                            form.trigger('reset');
                        @else
                            swal(
                            'Success!',
                            ' Manager has been updated.',
                            'success'
                            ).then(()=>{
                            location.reload();
                            });
                        @endif

                    },
                    error: function(err) {


                        if (err.responseJSON['errors']) {

                            let error = err.responseJSON['errors'];
                            var msg = '';
                            $.each(error, (i, j) => {
                                $('.' + i).css("display", "block");
                                $('.' + i).text(j);
                                msg += j;
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
