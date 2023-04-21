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
                @if (isset($executiveTarget))
                {{ request()->is('*/branch/scheme-targets*') ? 'Edit Scheme Targets' : 'Edit Branch Targets' }}
                @else
                {{ request()->is('*/branch/scheme-targets*') ? 'Create Scheme Targets' : 'Create Branch Targets' }}
                @endif
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Branches </li>
                <li class="breadcrumb-item">{{ request()->is('*/branch/scheme-targets*') ? 'Scheme Targets' : 'Branch Targets' }}</li>
                <li class="breadcrumb-item">
                    @if (isset($executiveTarget))
                {{ request()->is('*/branch/scheme-targets*') ? 'Edit Scheme Targets' : 'Edit Branch Targets' }}
                @else
                {{ request()->is('*/branch/scheme-targets*') ? 'Create Scheme Targets' : 'Create Branch Targets' }}
                @endif
                </li>
            </ol>
        </div>
        <div>
            <a href="{{ request()->is('*/branch/scheme-targets*') ? '/admin/branch/scheme-targets' : '/admin/branch/branch-targets' }}">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i>{{ request()->is('*/branch/scheme-targets*') ? 'Scheme Targets List' : 'Branch Targets List' }} </button>
            </a>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="col-lg-12">
            <div class="card card-fullheight">
                <div class="card-body">
                    @if (isset($branchTarget))
                        <form action="{{ request()->is('*/branch/scheme-targets*') ? '/admin/branch/scheme-targets/'.$branchTarget->id : '/admin/branch/branch-targets/'.$branchTarget->id }}" method="PATCH"
                            id="branchTarget-form">
                            @method('PATCH')
                        @else
                            <form action="{{ request()->is('*/branch/scheme-targets*') ? '/admin/branch/scheme-targets' : '/admin/branch/branch-targets' }}" method="POST"
                                id="branchTarget-form">
                    @endif
                    @csrf
                    <div class="row">
                        <div class="col-sm-4 form-group mb-4">
                            <label>Branches<span class="text-danger">*</span></label>
                            @hasanyrole('super-admin|developer-admin')
                            <select class="select2 col-sm-12 form-group" id="branch_id" name="branch_id" required>
                                <option value="">--select-an-option--</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" @if (isset($branchTarget) && $branchTarget->branch_id == $branch->id)
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
                            <span class="text-danger error-text branch_id" for="branch_id"></span>
                        </div>
                        <div class="col-sm-4 form-group mb-4" {{ request()->is('*/branch/scheme-targets*') ? '' : 'hidden' }}>
                            <label>Scheme<span class="text-danger">*</span></label>
                            <select class="select2 col-sm-12 scheme_select2  form-group" id="scheme_id" name="scheme_id"
                                >
                                <option selected value="">--select-an-option--</option>
                            </select>
                            <sub class="text-danger scheme_id" for="scheme_id"></sub>
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label>Target per month<span class="text-danger">*</span></label>
                            <input type="number" name="target_per_month" id="target_per_month"
                                value="{{ isset($branchTarget) ? $branchTarget->target_per_month : old('target_per_month') }}"
                                class="form-control">
                            <span class="text-danger target_per_month" for="target_per_month"></span>
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
    var scheme_selected_id = '<?php if (isset($branchTarget)) { echo $branchTarget->scheme_id;} ?>';
    var branch =  $("#branch_id :selected").val();
    if (branch !='') {
        getScheme(branch);
    }
      $('#branch_id').on('change', function() {
            branch_id = ($(this).val());
            getScheme(branch_id);
        });

      function getScheme(branch_id)
       {
        if (branch_id) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/admin/branch/branch-schemes",
                    method: 'POST',
                    data: {
                        "branch_id": branch_id,
                    },
                    success: function(response) {
                        if(response.schemes != null) {
                            $('#scheme_id').html('');
                            setScheme(response.schemes);
                        }
                        else{
                            $('#scheme_id').html('');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr)
                    }
                });
            }
       }



        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select an option",
            });

            $('#branchTarget-form').submit(function(e) {
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
                    data: $('#branchTarget-form').serialize(),
                    beforeSend: function() {
                        $(document).find('span.error-text').text('');
                    },
                    success: function(data) {
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true,
                            "timeOut": "1500",
                        }
                        toastr.success(data.success)
                        setTimeout(() => {
                            window.history.back();
                        }, 1500);;
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



        function setScheme(resp) {

           if(resp){
            $('.scheme_select2').empty();

            if (scheme_selected_id) {
                if (resp.id == scheme_selected_id) {
                    var scheme_id = resp.scheme_a_id+'-'+resp.scheme_n_id;
                    var newOption = new Option(scheme_id + ' ' + resp.name, resp.id, false, true);
            $('.scheme_select2').append(`<option></option`);
            $('.scheme_select2').append(newOption);
                } else {
                    var scheme_id = resp.scheme_a_id+'-'+resp.scheme_n_id;
                    var newOption = new Option(scheme_id + ' ' + resp.name, resp.id, false, false);
            $('.scheme_select2').append(`<option></option`);
            $('.scheme_select2').append(newOption);
                }
            } else {
                var scheme_id = resp.scheme_a_id+'-'+resp.scheme_n_id;
                var newOption = new Option(scheme_id + ' ' + resp.name, resp.id, false, false);
            $('.scheme_select2').append(`<option></option`);
            $('.scheme_select2').append(newOption);
            }
        }
        }
    </script>
@endpush
