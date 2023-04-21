@extends('backend.layouts.backend')

@push('styles')
    {{-- <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" /> --}}
    <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush
@section('content')

<!-- BEGIN: Page heading-->
<div class="page-heading">
    <div class="page-breadcrumb">
        <h1 class="page-title page-title-sep">
            @if(isset($role))
                Edit Role
            @else
                Create Role
            @endif
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Settings</li>
            <li class="breadcrumb-item">Role</li>
            <li class="breadcrumb-item">
                @if(isset($role))
                    Edit Role
                @else
                    Create Role
                @endif
            </li>
        </ol>
    </div>
    <div>
        <a href="/admin/settings/roles">
            <button class="btn btn-primary"><i class="la la-arrow-left"></i>Role List</button>
        </a>
    </div>
</div>
<!-- End Page Heading -->


<div>
    <div class="col-lg-12">

        <div class="card card-fullheight">
            <div class="card-body">
                @if (isset($role))
                    <form action="/admin/settings/roles/{{$role->id}}" method="POST" id="roleForm">
                    @method('PATCH')
                @else
                    <form action="/admin/settings/roles" method="POST" id="roleForm">
                @endif
                    @csrf
                    <div class="row">
                        <div class="col-sm-6 form-group mb-4">
                            <label>Role Name<span class="text-danger">*</span></label>
                            <input class="form-control" type="text" placeholder="Role Name" value="{{isset($role)? $role->name : old('name')}}" name="display_name" required>
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
<script src="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/jquery-validation/dist/jquery.validate.min.js')}}"></script><!-- CORE SCRIPTS-->
    <script>
       $(document).ready(function() {
        $("#roleForm").validate({
            rules: {
                display_name: {
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
            $('#roleForm').submit(function (e) {
                e.preventDefault();

                let form = $(this);
                if (!form.valid()) return false;
                let url = form.attr('action');
                let method = form.attr('method');

                $.ajax({
                    type: method,
                    url: url,
                    data: form.serialize(),
                    success: function(data) {

                        @if(!isset($role))
                            form.trigger('reset');
                            $('.select2').val(null).trigger('change');
                            swal(
                            'Success!',
                            'Role has been added.',
                            'success'
                            )
                        @else
                            swal(
                            'Success!',
                            'Role has been updated.',
                            'success'
                            )
                        @endif



                    },
                    error: function(data) {

                        if(data.responseJSON['errors']){

                            let err = data.responseJSON['errors'];

                            $.each(err, (i,j)=>{
                                $('#errorMsg').append(j+'<br/>');
                            });

                            let errKeys = Object.keys(data.responseJSON['errors']);

                            errKeys.map((item) => {
                                $('[name='+item+']').addClass('is-invalid');
                            });

                        }

                    }
                });
            });
        });
    </script>
@endpush
