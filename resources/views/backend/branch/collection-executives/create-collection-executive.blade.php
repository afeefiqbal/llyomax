@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        {{-- <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" /> --}}
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
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
                Create Assign Customer Collection Executive
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Collection </li>
                <li class="breadcrumb-item">Collection Executive</li>
                <li class="breadcrumb-item">
                    Create Assign Customer Collection Executive
                </li>
            </ol>
        </div>
        <div>
            <a href="/admin/branch/collection-executives">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i>Collection Executives List</button>
            </a>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="col-lg-12">
            <div class="card card-fullheight">
                <div class="card-body">
                    <form action="/admin/branch/collection-executives" method="POST" id="collectionExecutive-form">
                        @csrf
                        <div class="row">
                            <div class="col-sm-4 form-group mb-4">
                                <label>Branches<span class="text-danger">*</span></label>
                                @hasanyrole('super-admin|developer-admin')
                                <select class="select2 col-sm-12 form-group" id="branch_id" name="branch_id" required>
                                    <option value="">--select-an-option--</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" @if (isset($customerExecutive) && $customerExecutive->branch_id == $branch->id)
                                            {{ 'selected' }}
                                    @endif>{{ $branch->id }}-{{ $branch->branch_name }}</option>
                                    @endforeach
                                </select>
                                @endhasanyrole
                                @role('branch-manager')
                                @php
                                $user = auth()->user();
                                $manager = \App\Models\Master\Manager::where('user_id', $user->id)->first();
                            @endphp
                           <select class="select2 col-sm-12 form-group" id="branch_id" name="branch_id" required>
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
                                @endrole
                                <sub class="text-danger branch_id" for="branch_id"></sub>
                            </div>

                            <div class="col-sm-4 form-group mb-4">
                                <label>Scheme<span class="text-danger">*</span></label>
                                <select class="select2 col-sm-12 scheme_select2  form-group" id="scheme_id" name="scheme_id"
                                    required>

                                    <option selected value="">--select-an-option--</option>
                                    @foreach ($schemes as $scheme)
                                        <option value="{{ $scheme->id }}">{{ $scheme->scheme_a_id.'-'.$scheme->scheme_n_id.'-'.$scheme->name }}</option>
                                    @endforeach
                                </select>
                                <sub class="text-danger scheme_id" for="scheme_id"></sub>
                            </div>
                            <div class="col-sm-4 form-group mb-4">
                                <label>Customer<span class="text-danger">*</span></label>
                                <select class="select2 col-sm-12 customer_select2  form-group" id="customer_id" required
                                    name="customer_id">
                                    <option selected value="">--select-an-option--</option>
                                </select>
                                <sub class="text-danger customer_id" for="customer_id"></sub>
                            </div>
                            <div class="col-sm-4 form-group mb-4">
                                <label>Branch Area<span class="text-danger">*</span></label>
                                <select class="select2 col-sm-12 area_select2  form-group" id="area_id" name="area_id"
                                    required>
                                    <option selected value="">--select-an-option--</option>
                                </select>
                                <sub class="text-danger area_id" for="area_id"></sub>
                            </div>
                            <div class="col-sm-4 form-group mb-4">
                                <label>Collection Executive<span class="text-danger">*</span></label>
                                <select class="select2 col-sm-12 collection_executive_select2  form-group"
                                    id="collection_executive_id" required name="collection_executive_id">
                                    <option selected value="">--select-an-option--</option>
                                </select>
                                <sub class="text-danger collection_executive_id" for="collection_executive_id"></sub>
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
    <script src="{{ asset('backend/assets/vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <!-- CORE SCRIPTS-->
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: "Select an option",
            });
        });
        $('#scheme_id').on('change', function () {
            var scheme_id = $(this).val();
            $('#customer_id').find('option').not(':first').remove();
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
            $.ajax({
                url: "/admin/branch/get-customer",
                type: 'POST',
                data: {
                    scheme_id: scheme_id
                },
                success: function (data) {
                    $.each(data, function (key, value) {
                        $('#customer_id').append('<option value="' + value.id + '">' + value.customer_id + '-' + value.name + '</option>');
                    });
                }
            });

        });
        $('#customer_id').on('change', function () {
            var customer_id = $(this).val();
            $('#area_id').find('option').not(':first').remove();
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
            $.ajax({
                url: "/admin/branch/get-area",
                type: 'POST',
                data: {
                    customer_id: customer_id
                },
                success: function (data) {
                    $.each(data, function (key, value) {
                        $('#area_id').append('<option value="' + value.id + '">' + value.area_id + '-' + value.name + '</option>');
                    });
                }
            });
        });
        $('#area_id').on('change', function() {

            area_id = ($(this).val());

            var customer_id = document.getElementById("customer_id").value;
            if (area_id) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/admin/branch/get-executive",
                    method: 'POST',
                    data: {
                        "area_id": area_id,
                        "customer_id": customer_id,
                    },
                    success: function(response) {
                        $('#collection_executive_id').find('option').not(':first').remove();
                        $.each(response, function(key, value) {
                            $('#collection_executive_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });

                    },
                    error: function(xhr) {
                        console.log(xhr)
                    }
                });
            }
        });

        $(document).ready(function() {



            $('#collectionExecutive-form').submit(function(e) {
                e.preventDefault();
                let form = $(this);
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
                    data: $('#collectionExecutive-form').serialize(),
                    success: function(data) {
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true,
                            "timeOut": "1500",
                        }
                        toastr.success(data.success);
                        $('#collectionExecutive-form')[0].reset();
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    },
                    error: function(err) {
                        if (err.responseJSON['errors']) {
                            let error = err.responseJSON['errors'];
                            var msg = '';
                            $.each(error, (i, j) => {
                                $('.' + i).text(j);
                                msg += j + '<br/>';
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
        $('#branch_id').on('change', function() {
            // $('#customer_id').find('option').not(':first').remove();
            // $('#scheme_id').find('option').not(':first').remove();
            branch_id = ($(this).val());
            getData(branch_id);
        });
        function getData(branch_id) {
            if (branch_id) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/admin/branch/get-branch-schemes",
                    method: 'POST',
                    data: {
                        "branch_id": branch_id,
                    },
                    success: function(response) {

                    },
                    error: function(xhr) {
                        console.log(xhr)
                    }
                });
            }
        }

        //
    </script>
@endpush
