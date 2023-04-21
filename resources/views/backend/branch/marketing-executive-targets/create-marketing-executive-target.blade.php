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
                    Edit Marketing Executive Target
                @else
                    Create Marketing Executive Target
                @endif
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Marketing </li>
                <li class="breadcrumb-item">Marketing Executive Target</li>
                <li class="breadcrumb-item">
                    @if (isset($executiveTarget))
                        Edit Marketing Executive Target
                    @else
                        Create Marketing Executive Target
                    @endif
                </li>
            </ol>
        </div>
        <div>
            <a href="/admin/branch/marketing-executive-targets">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i>Marketing Executive Target List</button>
            </a>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="col-lg-12">
            <div class="card card-fullheight">
                <div class="card-body">
                    @if (isset($executiveTarget))
                        <form action="/admin/branch/marketing-executive-targets/{{ $executiveTarget->id }}" method="PATCH"
                            id="MarketingExecutiveTarget-form">
                            @method('PATCH')
                        @else
                            <form action="/admin/branch/marketing-executive-targets" method="POST"
                                id="MarketingExecutiveTarget-form">
                    @endif
                    @csrf
                    <div class="row">
                        <div class="col-sm-4 form-group mb-4">
                            <label>Branches<span class="text-danger">*</span></label>
                            @hasanyrole('super-admin|developer-admin')
                            <select class="select2 col-sm-12 form-group" id="branch_id" name="branch_id" required>
                                <option value="">--select-an-option--</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" @if (isset($executiveTarget) && $executiveTarget->executive->branch_id == $branch->id)
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
                        <div class="col-sm-4 form-group mb-4" hidden>
                            <label>Branch Area<span class="text-danger">*</span></label>
                            <select class="select2 col-sm-12 area_select2  form-group" id="area_id" name="area_id" >
                                <option selected value="">--select-an-option--</option>
                            </select>
                            <span class="text-danger error-text area_id" for="area_id"></span>
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label>Marketing Executive<span class="text-danger">*</span></label>
                            <select class="select2 col-sm-12 marketing_executive_select2  form-group" id="executive_id"
                                required name="executive_id">
                                <option selected value="">--select-an-option--</option>
                            </select>
                            <span class="text-danger error-text executive_id" for="executive_id"></span>
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label>Target Per Month<span class="text-danger">*</span></label>
                            <input type="number" name="target_per_month" id="target_per_month"
                                value="{{ isset($executiveTarget) ? $executiveTarget->target_per_month : old('target_per_month') }}"
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
        var branch_id = $("#branch_id :selected").val();
        var area_id = $("#area_id :selected").val();
        var area_selected_id = '<?php if (isset($executiveTarget)) { echo $executiveTarget->executive->collection_area_id;} ?>';
        var executive_selected_id = '<?php if (isset($executiveTarget)) { echo $executiveTarget->executive_id; } ?>';
    
        $('#branch_id').on('change', function() {
            $('#executive_id').find('option').not(':first').remove();
            // $('#area_id').find('option').remove();
            // $('#executive_id').find('option').remove();
            branch_id = ($(this).val());
            areaExecutive(branch_id);
        });
        function areaExecutive(branch_id) {
            if (branch_id) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/admin/branch/get-maraketing-executive",
                    method: 'POST',
                    data: {
                        "branch_id": branch_id,
                    },
                    success: function(response) {
                        setExecutive(response);
                    },
                    error: function(xhr) {
                    }
                });
            }
        }
        var branch_id = $("#branch_id :selected").val();
            var area_id = $('#area_id :selected').val();
            if (area_id != '' && area_id != '' && branch_id != '' && branch_id != '') {
                areaExecutive(area_id, branch_id);
            }
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select an option",
            });

            $('#MarketingExecutiveTarget-form').submit(function(e) {
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
                    data: $('#MarketingExecutiveTarget-form').serialize(),
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
                        setTimeout(function() {
                            window.history.back();
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

        function setArea(resp) {
            $('.area_select2').empty();
            resp.forEach(element => {
                if (area_selected_id) {
                    if (element.id == area_selected_id) {
                        var newOption = new Option(element.area_id + ' ' + element.name, element.id, false, true);
                        $('.area_select2').append(`<option></option`);
                        $('.area_select2').append(newOption);
                    } else {
                        var newOption = new Option(element.area_id + ' ' + element.name, element.id, false, false);
                        $('.area_select2').append(`<option></option`);
                        $('.area_select2').append(newOption);
                    }
                } else {
                    var newOption = new Option(element.area_id + ' ' + element.name, element.id, false, false);
                    $('.area_select2').append(`<option></option`);
                    $('.area_select2').append(newOption);
                }
            });
        }

        function setExecutive(resp) {
            $('.marketing_executive_select2').empty();
            resp.forEach(element => {
                if (executive_selected_id) {
                    if (element.id == executive_selected_id) {
                        var newOption = new Option(element.executive_id + ' ' + element.name,
                            element.id, false, true);
                        $('.marketing_executive_select2').append(`<option></option`);
                        $('.marketing_executive_select2').append(newOption);
                    } else {
                        var newOption = new Option(element.executive_id + ' ' + element.name,
                            element.id, false, false);
                        $('.marketing_executive_select2').append(`<option></option`);
                        $('.marketing_executive_select2').append(newOption);
                    }
                } else {
                    var newOption = new Option(element.executive_id + ' ' + element.name,
                        element.id, false, false);
                    $('.marketing_executive_select2').append(`<option></option`);
                    $('.marketing_executive_select2').append(newOption);
                }
            });
        }
    </script>
@endpush
