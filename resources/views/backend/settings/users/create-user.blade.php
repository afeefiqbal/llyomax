@extends('backend.layouts.backend')

@section('content')

@push('styles')
    <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
    <style>
        .select2-selection__arrow{
            top: 15px !important ;
        }
        .select2-selection__rendered{
            line-height: 10px !important;
        }
        </style>
    @endpush

<!-- BEGIN: Page heading-->
<div class="page-heading">
    <div class="page-breadcrumb">
        <h1 class="page-title page-title-sep">
            @if(isset($user))
                Edit User
            @else
                Create User
            @endif
        </h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Settings</li>
            <li class="breadcrumb-item">User</li>
            <li class="breadcrumb-item">
                @if(isset($user))
                    Edit User
                @else
                    Create User
                @endif
            </li>
        </ol>
    </div>
    <div>
        <a href="/admin/settings/users">
            <button class="btn btn-primary"><i class="la la-arrow-left"></i>User List</button>
        </a>
    </div>
</div>
<!-- End Page Heading -->

<div>
    <div class="col-lg-12">

        <div class="card card-fullheight">
            <div class="card-body">
                @if (isset($user))
                    <form action="/admin/settings/users/{{$user->id}}" method="POST" id="userForm">
                    @method('PATCH')
                @else
                    <form action="/admin/settings/users" method="POST" id="userForm">
                @endif
                    @csrf
                    <div class="row">

                        <div class="col-sm-6 form-group mb-4">
                            <label>Name</label>
                            <input class="form-control" type="text" placeholder="Full name" name="name" value="{{isset($user)? $user->name : old('name')}}" required>
                        </div>

                        <div class="col-sm-6 form-group mb-4">
                            <label>Email</label>
                            <input class="form-control" type="email" placeholder="Email" name="email" min="0" value="{{isset($user)? $user->email : old('email')}}" required>
                        </div>

                        <div class="col-sm-6 form-group mb-4">
                            <label>Phone</label>
                            <input class="form-control" type="text" placeholder="Phone" name="phone" value="{{isset($user)? $user->mobile : old('phone')}}" required>
                        </div>
                        <div class="col-sm-6 form-group mb-4">
                            <label>Role</label>
                            <select class="form-control select-role" name="role" required>
                            @if(isset($user))
                                @foreach ($user->roles as $role)
                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                @endforeach
                            @endif
                            </select>
                        </div>

                        <div class="col-sm-6 form-group mb-4">
                            <label>Username</label>
                            <input class="form-control" type="text" placeholder="Username" name="username" value="{{isset($user)? $user->username : old('username')}}" required>
                        </div>

                        <div class="col-sm-6 form-group mb-4">
                            <label>Password</label>
                            <input class="form-control" type="password" placeholder="Password" name="password" {{isset($user)? "":"required"}}>
                        </div>

                    </div>


                    <div class="col-md-12 row mb-5">
                        <div class="form-group col-md-3">
                            <label class="ui-switch switch-solid">
                                <input type="checkbox" name="is_active" {{isset($user) ? $user->status == '1' ? 'checked':'' : 'checked'}}>
                                <span class="ml-0"></span> Enabled
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary mr-2" type="submit">Submit</button>
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
    <script src="{{ asset('backend/assets/vendors/jquery-validation/dist/jquery.validate.min.js')}}"></script><!-- CORE SCRIPTS-->

    <script>
       $(document).ready(function() {
        $("#userForm").validate({
            rules: {
                name: {
                    required: !0
                },
                email: {
                    required: !0,
                    email: !0
                },
                phone: {
                    required: !0,
                    minlength: 10
                },
                role: {
                    required: !0
                },
                username: {
                    required: !0
                },
                password: {
                    minlength: 8
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
            $('.select-role').select2({
                placeholder: "Select a role",
                ajax: {
                    url: '/admin/settings/list-roles',
                    dataType: 'json',
                    processResults: function(response){
                        return {
                            results: response
                        }
                    }
                }
            });

            $('#userForm').submit(function (e) {
                e.preventDefault();

                let form = $(this);
                if (!form.valid()) return false;
                let url = form.attr('action');
                let method = form.attr('method');

                $.ajax({
                    type: method,
                    url: url,
                    headers: {
                            'X-CSRF-Token': '{{ csrf_token() }}'
                        },
                    data: form.serialize(),
                    success: function(data) {

                        @if(!isset($user))
                            form.trigger('reset');
                            $('.select-role').val(null).trigger('change');
                            swal(
                            'Success!',
                            'User has been added.',
                            'success'
                            )
                        @else
                            swal(
                            'Success!',
                            'User has been updated.',
                            'success'
                            )
                        @endif
                    },
                    error: function(data) {

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
