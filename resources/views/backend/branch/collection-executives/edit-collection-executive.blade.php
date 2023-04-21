@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
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
                Edit Assign Customer Collection Executive
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Collection </li>
                <li class="breadcrumb-item">Collection Executive</li>
                <li class="breadcrumb-item">
                    Edit Assign Customer Collection Executive
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
                    <form action="/admin/branch/collection-executives/{{ $customerExecutive->id }}" method="PATCH"
                        id="collectionExecutive-form">
                        @method('PATCH')
                        @csrf
                        <div class="row">
                      
                            <div class="col-sm-4 form-group mb-4">
                                <label>Scheme<span class="text-danger">*</span></label>
                                <input type="hidden" id="scheme_id" value="{{ $customerExecutive->scheme->id }}">
                                <input type="text" class="form-control" value="{{ $customerExecutive->scheme->name }}"
                                    name="scheme_id" readonly>
                            </div>
                            <div class="col-sm-4 form-group mb-4">
                                <label>Customer<span class="text-danger">*</span></label>
                                <input type="hidden" id="customer_id" value="{{ $customerExecutive->customer->id }}">
                                <input type="text" class="form-control" value="{{ $customerExecutive->customer->name }}({{$customerExecutive->customer->area->name }})"
                                    name="customer_id" readonly>
                            </div>
                            <div class="col-sm-4 form-group mb-4">
                                <label>Branch Area<span class="text-danger">*</span></label>
                                <select class="select2 col-sm-12 form-group" id="area_id" name="area_id" required>
                                    <option value="">--select-an-option--</option>
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->id }}" @if (isset($customerExecutive) && $customerExecutive->executive->collection_area_id == $area->id)
                                            {{ 'selected' }}
                                    @endif>{{ $area->area_id }}-{{ $area->name }}</option>
                                    @endforeach
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


        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select an option",
            });
            $("#collection_executive_id").select2({
                placeholder: "select an option",
                ajax: {
                    url: '/admin/branch/get-executive',
                    data: function(params) {
                        var query = {
                            area_id: $("#area_id :selected").val(),
                            branch_id: $("#branch_id").val(),
                            customer_id: $("#customer_id").val(),
                        }
                        return query;
                    },
                    dataType: 'json',
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.executive_id + '-' + item.name + '(' + item
                                        .count + ')',
                                    id: item.id,
                                }
                            })
                        };
                    }
                }
            });
            $('#area_id').on('change', function() {
                area_id = ($(this).val());
                var branch_id = document.getElementById("branch_id").value;
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
                            "branch_id": branch_id,
                            "customer_id": customer_id,
                        },
                        success: function(response) {
                            setExecutive(response);
                        },
                        error: function(xhr) {
                            console.log(xhr)
                        }
                    });
                }
            });
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

        function setExecutive(resp) {
            $('.collection_executive_select2').empty();
            resp.forEach(element => {
                var newOption = new Option(element.executive_id + ' ' + element.name + '(' + element.count + ')',
                    element.id, false, false);
                $('.collection_executive_select2').append(`<option></option`);
                $('.collection_executive_select2').append(newOption);
            });
        }
    </script>
@endpush
