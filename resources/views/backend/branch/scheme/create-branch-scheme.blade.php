@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        {{-- <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" /> --}}
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        {{-- <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" /> --}}
        <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
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
               Assign clusters to District
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>

                <li class="breadcrumb-item">
                   Assign clusters to District
                </li>
            </ol>
        </div>
        <div>
            <a href="/admin/master/branch-assigning">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i>Go back</button>
            </a>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="col-lg-12">
            <div class="card card-fullheight">
                <div class="card-body">
                    <form action="/admin/master/branch-assigning" method="POST" id="BranchExecutive-form">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6 form-group mb-4">
                                <label>District<span class="text-danger">*</span></label>
                                <select class="select2 col-sm-12 scheme_select2  form-group" id="district_id" name="district_id"
                                    required>
                                    <option selected value="">--select-an-option--</option>
                                    @if (isset($district))
                                        <option value="{{ $district->id }}" {{ 'selected' }}>
                                            {{ $district->district_id . '-' . $district->name }}</option>
                                    @else
                                        @foreach ($districts as $district)
                                            <option value="{{ $district->id }}"
                                                @if (isset($district) && $district->id == $district->id) {{ 'selected' }} @endif>
                                                {{ $district->district_id . '-' . $district->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <sub class="text-danger district_id" for="district_id"></sub>
                            </div>
                            <div class="col-sm-6 form-group mb-4">
                                <label>Cluster<span class="text-danger">*</span></label>
                                <select class="select2 col-sm-12 cluster_select2 multiple  form-group"  multiple id="cluster_id"
                                    name="cluster_id[]" required>

                                    @foreach ($clusters as $cluster)
                                        <option value="{{ $cluster->id }}">{{ $cluster->cluster_id . '-' . $cluster->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <sub class="text-danger cluster_id" for="cluster_id"></sub>
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
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select an option",
            });

            $('#BranchExecutive-form').submit(function(e) {
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
                    data: $('#BranchExecutive-form').serialize(),
                    success: function(data) {
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true,
                            "timeOut": "1500",
                        }
                        toastr.success(data.success);
                        setTimeout(function() {
                            window.location.href = "/admin/master/branch-assigning";
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
        $('#cluster_id').on('change', function() {
            let cluster_id = $(this).val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '/admin/get-branches',
                data: {
                    cluster_id: cluster_id
                },
                success: function(data) {
                  $('#branch_id').empty();
                    $('#branch_id').append('<option value="">--select-an-option--</option>');
                    $.each(data, function(key, value) {
                        $('#branch_id').append('<option value="' + value.id + '">' + value.branch_id + '-' + value.branch_name + '</option>');
                    });

                }
            });
        });
        $('#scheme_id').on('change', function() {
            let scheme_id = $(this).val();
            let url = '/admin/get-branches-by-scheme/';
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    scheme_id: scheme_id
                },
                success: function(data) {
                    setBranches(data.branches);
                }
            });
        });

        function setBranches(data) {
            let html = '';
            data.map((item) => {
                html += `<option value="${item.id}">${item.id}-${item.branch_name}</option>`;
            });
            $('#branch_id').html(html);

        }
    </script>
@endpush
