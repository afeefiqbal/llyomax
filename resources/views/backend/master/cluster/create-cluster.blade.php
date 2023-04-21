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
                @if (isset($cluster))
                    Edit cluster
                @else
                    Create cluster
                @endif
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item">cluster</li>
                <li class="breadcrumb-item">
                    @if (isset($cluster))
                        Edit cluster
                    @else
                        Create cluster
                    @endif
                </li>
            </ol>
        </div>
        <div>
            <a href="/admin/master/clusters">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i> clusters List</button>
            </a>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="col-lg-12">
            <div class="card card-fullheight">
                <div class="card-body">
                    @if (isset($cluster))
                        <form action="/admin/master/clusters/{{ $cluster->id }}" method="PATCH" id="cluster-form">
                            @method('PATCH')
                        @else
                            <form action="/admin/master/clusters" method="POST" id="cluster-form">
                    @endif
                    @csrf
                    <div class="row">
                        <div class="col-sm-6 form-group mb-4" id="branch-start">
                            <label>District</label>
                            <br>
                            <select class="select2 col-sm-12 form-group" id="district_id" name="district_id">
                                <option value=""></option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}"
                                        @if (isset($cluster) && $cluster->district_id == $district->id) {{ 'selected' }} @endif>
                                        {{ $district->district_id }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-sm-6" id="branch-start">
                            <label>Branches  <sub class="error-text" style="color: red"></sub></label>
                            <br>
                            <select class="select2-multiple col-sm-12 form-group" multiple name="branches[]" id="branches">
                                <option value=""></option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        @if (isset($cluster) && $cluster->branches->contains($branch->id)) {{ 'selected' }} @endif>
                                        {{ $branch->branch_id }}-{{ $branch->branch_name }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-sm-6 form-group mb-4">
                            <label>cluster ID<span class="text-danger">*</span></label>
                            <input class="form-control" type="number" id="cluster_id" placeholder="cluster ID"
                                value="{{ isset($cluster) ? $cluster->cluster_id : old('cluster_id') }}"
                                name="cluster_id">
                        </div>
                        <div class="col-sm-6 form-group mb-4">
                            <label>cluster Name<span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="cluster" placeholder="cluster Name"
                                value="{{ isset($cluster) ? $cluster->name : old('name') }}" name="name" required>
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
        $('.select2-multiple').on("change",function() {
            var selected = $(this).val();
        console.log(selected.length);
        if(selected.length < 3){
            $('.error-text').text('    *Select atleast 3 branches');
            return false
        }
        else
        {
            $('.error-text').text('');
            return true
        }
        });
        $(document).ready(function() {
            $(document).ready(function() {
                $('.select2-multiple').select2({
                    placeholder: "Select an option",
                    maximumSelectionLength: 6
                });
            });
            $(".select2_dem").select2({
                placeholder: "Select an option",
            });

            $(".select2").select2({
                placeholder: "Select an option",
            });
            $("#cluster-form").validate({
                rules: {
                    name: {
                        minlength: 2,
                        required: !0
                    },
                    cluster_id: {
                        required: !0
                    },

                    branches: {
                        required: !0
                    },
 
                },
                errorClass: 'invalid-feedback',
                validClass: 'valid-feedback',
                highlight: function(e) {
                    $(e).addClass("is-invalid").removeClass('is-valid');
                },
                unhighlight: function(e) {
                    $(e).removeClass("is- ").addClass('is-valid');
                },
            });
            $('#cluster-form').submit(function(e) {
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
                    data: $('#cluster-form').serialize(),
                    success: function(data) {
                        @if (!isset($cluster))
                            swal(
                            'Success!',
                            'cluster has been added.',
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
                            ' cluster has been updated.',
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
