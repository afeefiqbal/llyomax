@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
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
                @if (isset($customerSchemeDetail))
                    Scheme Register
                @else
                    Create customer
                @endif
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Branch</li>
                <li class="breadcrumb-item">customer</li>
                <li class="breadcrumb-item">
                    @if (isset($customerSchemeDetail))
                        Scheme Register
                    @else
                        Create customer
                    @endif
                </li>
            </ol>
        </div>
        <div>
            <a href="/admin/customer/customers">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i> customers List</button>
            </a>
        </div>
    </div>
    <!-- End Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-fullheight">
                <div class="card-body">
                    @if (isset($customerSchemeDetail))
                        <form action="/admin/customer/customers/{{ $customerSchemeDetail->id }}" method="PATCH"
                            id="customer-form">
                            @method('PATCH')
                        @else
                            <form action="/admin/customer/customers" method="POST" id="customer-form">
                    @endif
                    @csrf
                    <div class="tab-content mt-4">
                        <div class="tab-pane fade show active" id="tab-info">
                            <div class="row">
                                <div class="col-sm-6 form-group mb-4">
                                    <label>Branch</label>
                                    @if (isset($customerSchemeDetail))
                                        <input type="hidden" value="{{ $customerSchemeDetail->branch_id }}"
                                            name="branch_id">
                                        <input type="hidden" value="{{ $customerSchemeDetail->customer_id }}"
                                            name="customer_id">
                                        <input type="text" class="form-control"
                                            value="{{ $customerSchemeDetail->branch->branch_name }}" readonly>
                                    @else
                                        @hasanyrole('super-admin|developer-admin')
                                            <select name="branch_id" class=" select2 form-control" id="branch_id">
                                                <option value="" {{ isset($customer) ?: 'selected' }}></option>
                                                @isset($branches)
                                                    @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                                    @endforeach
                                                @endisset
                                            </select>
                                        @endhasanyrole
                                        @hasanyrole('collection-executive|marketing-executive')
                                            @php
                                                $user = auth()->user();
                                                $executive = \App\Models\Executive\Executive::where('user_id', $user->id)->first();
                                            @endphp
                                            <select name="branch_id" class=" select2 form-control" id="branch_id">
                                                @isset($branches)
                                                    @foreach ($branches as $branch)
                                                        @isset($executive)
                                                            @if ($executive->branch_id == $branch->id)
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
                                        @endhasanyrole
                                        @role('branch-manager')
                                            @php
                                                $user = auth()->user();
                                                $manager = \App\Models\Master\Manager::where('user_id', $user->id)->first();
                                            @endphp
                                            <select name="branch_id" class=" select2 form-control" id="branch_id">
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
                                    @endif
                                    <sub class="text-danger error-text branch_id" for="branch_id"></sub>
                                </div>
                                <div class="col-sm-6 form-group mb-4">
                                    <label>Scheme<span class="text-danger  ">*</span></label>
                                    <select name="scheme_id" class=" select2  scheme_select2 form-control" id="scheme_id">
                                        <option value="" {{ isset($customer) ?: 'selected' }}></option>
                                    </select>
                                    <sub class="text-danger error-text scheme_id" for="scheme_id"></sub>
                                </div>
                                <div class="col-sm-3 form-group mb-4">
                                    <label>customer Name<span class="text-danger  ">*</span></label>
                                    <input class="form-control" type="text" id="name" placeholder="Name"
                                        value="{{ isset($customerSchemeDetail) ? $customerSchemeDetail->customer->name : old('name') }}"
                                        name="name" @if (isset($customerSchemeDetail)) readonly @endif>
                                    <sub class="text-danger error-text name" for="name"></sub>
                                </div>
                                <div class="col-sm-3 form-group mb-4">
                                    <label>phone number<span class="text-danger  ">*</span></label>

                                    <div class="input-group mb-4">
                                        <input type="text" class="form-control" type="number" id="phone"
                                            placeholder="Phone" aria-label="Phone" aria-describedby="basic-addon2"
                                            value="{{ isset($customerSchemeDetail) ? $customerSchemeDetail->customer->phone : old('phone') }}"
                                            name="phone" @if (isset($customerSchemeDetail)) readonly @endif>
                                        @if (!isset($customerSchemeDetail))
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-success" type="button"
                                                    onclick="sendOTP()">Send OTP</button>
                                            </div>
                                        @endif
                                    </div>
                                    <sub class="text-danger error-text phone" for="phone"></sub>
                                    <br>
                                </div>
                                @if (!isset($customerSchemeDetail))
                                    <div class="col-sm-3 form-group mb-4">
                                        <label>OTP<span class="text-danger  ">*</span></label>
                                        <div class="input-group mb-4">
                                            <input type="text" class="form-control" type="number" id="otp"
                                                name="otp" placeholder="otp" aria-label="otp"
                                                aria-describedby="basic-addon2">

                                            <div class="input-group-append">
                                                <button class="btn btn-outline-success" type="button"
                                                    onclick="validateOTP()">Validate OTP</button>
                                            </div>

                                        </div>
                                        <sub class="text-danger error-text otp" for="otp"></sub>
                                    </div>
                                @endif
                                <div class="col-sm-3 form-group mb-4">
                                    <label>Place<span class="text-danger  ">*</span></label>
                                    <input class="form-control" type="text" id="place" placeholder="Place"
                                        value="{{ isset($customerSchemeDetail) ? $customerSchemeDetail->customer->place : old('place') }}"
                                        name="place" @if (isset($customerSchemeDetail)) readonly @endif>
                                    <sub class="text-danger error-text place" for="place"></sub>
                                </div>
                                <div class="col-sm-3 form-group mb-4">
                                    <label>Address (Home/Shop)</label>
                                    <textarea class="form-control" name="address" id="address" cols="5" rows="3" rows="6">{{ isset($customer) ? $customer->address : old('address') }}</textarea>
                                </div>
                                <div class="col-sm-3 form-group mb-4" hidden>
                                    <label>Area<span class="text-danger  ">*</span></label>
                                    <select name="area" class="select2  area_select2 form-control" id="area">
                                        <option value="" selected></option>
                                        <option value="" {{ isset($customer) ? 'selected' : '' }}></option>
                                    </select>
                                    <sub class="text-danger error-text area" for="area"></sub>
                                </div>
                                <div class="col-sm-3 form-group mb-4">
                                    <label>Scheme Start Date<span class="text-danger  ">*</span></label>
                                    <input class="form-control" type="text" id="scheme_start_date"
                                        name="scheme_start_date" readonly>
                                </div>
                                <div class="col-sm-3 form-group mb-4">
                                    <label>Scheme Collection Day<span class="text-danger  ">*</span></label>
                                    <input class="form-control" type="text" id="scheme_collection_day"
                                        name="scheme_collection_day" readonly>
                                </div>
                                <div class="col-sm-3 form-group mb-4">
                                    <label>Customer Collection Day<span class="text-danger  ">*</span></label>
                                    <select name="customer_collection_day"
                                        class="select2  collection_day_select2 form-control" id="customer_collection_day"
                                        required>
                                        <option value="Sunday">Sunday</option>
                                        <option value="Monday">Monday</option>
                                        <option value="Tuesday">Tuesday</option>
                                        <option value="Wednesday">Wednesday</option>
                                        <option value="Thursday">Thursday</option>
                                        <option value="Friday">Friday</option>
                                        <option value="Saturday">Saturday</option>
                                    </select>
                                    <sub class="text-danger error-text customer_collection_day"
                                        for="customer_collection_day"></sub>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-2 form-group mb-4">
                                    <div class="form-group col-md-2">
                                        <br>
                                        <label class="ui-switch switch-solid"> &nbsp;
                                            <input type="checkbox"
                                                {{ isset($customer) ? ($customer->status == '1' ? 'checked' : '') : 'checked' }}
                                                name="status">
                                            <span class="ml-0"></span>Active
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-4 form-group mb-4 custom">

                                    <label>Joining Amount<span class="text-danger  ">* <sub
                                                class="text-danger error-text amount" for="amount"></sub></span></label>
                                    <sub class="text-danger error-text custom_amount"
                                        for="custom_amount"></sub></span></label>
                                    <label class="checkbox checkbox-outline-primary checkbox-circle"><input type="radio"
                                            name="amount" value="200"><span>Paid 200</span></label><br>
                                    <label class="checkbox checkbox-outline-success checkbox-circle"><input type="radio"
                                            name="amount" value="0"><span>Start from next week</span></label> <br>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="input-group mb-3" style="width : 86%">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">
                                                        <label class="checkbox checkbox-outline-secondary checkbox-circle">
                                                            <input type="radio"name="amount" value="custom">
                                                            <span></span>
                                                        </label>
                                                    </span>
                                                </div>
                                                <input type="text" type="text" class="form-control" type="number"
                                                    id="amount" placeholder="Enter  Custom Amount"
                                                    name="custom_amount" placeholder="Username" aria-label="Username"
                                                    aria-describedby="basic-addon1">
                                            </div>
                                        </div>
                                        {{-- <input type="text" type="text" class="form-control" type="number"
                                        id="amount" placeholder="password"
                                        name="password" placeholder="password" aria-label="password"
                                        aria-describedby="basic-addon1"> --}}
                                        {{-- </div> --}}
                                    </div>

                                </div>
                                <div class="col-sm-5">
                                   <label for="">Select Prducts</label>
                                    <select name="products[]" class="select2  products_select2 form-control"
                                        id="products" multiple>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    <br>
                                        <h6 for="S">  <br> Selected Product : </h6>
                                        <table class="table table-auto">
                                            <thead>
                                                <th>Prdouct Code</th>
                                                <th>Name</th>
                                                <th>Price</th>
                                            </thead>
                                            <tbody id="product_list">

                                            </tbody>
                                        </table>
                                        <label for=""><b>Total :</b>  </label> &nbsp; &nbsp; &nbsp;<b> <span id="total"> 0.00 Rs. </span></b>
                                </div>
                                <div>
                                </div>
                            </div>
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
    </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('backend/assets/vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    {{-- <script src="{{ asset('backend/assets/vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- CORE SCRIPTS-->
    <script>
        $(document).ready(function() {
            getData();
            $('.select2').select2({
                placeholder: "Select an option",
            });
        });

        function sendOTP() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/admin/customer/get-otp",
                method: 'POST',
                data: {
                    "phone": $('#phone').val(),
                },
                success: function(response) {
                    console.log(response)
                    $("#phone").attr("readonly", true);
                },
                error: function(err) {
                    if (err.responseJSON['errors']) {
                        let error = err.responseJSON['errors'];
                        var msg = '';
                        $.each(error, (i, j) => {

                            $('.' + i).text(j)
                        });
                        let errKeys = Object.keys(err.responseJSON['errors']);
                        errKeys.map((item) => {
                            $('[name=' + item + ']').addClass('is-invalid');
                        });
                    }
                }
            });
        }

        function validateOTP() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/admin/customer/validate-otp",
                method: 'POST',
                data: {
                    "otp": $('#otp').val(),
                },
                success: function(response) {

                    if (response.success) {
                        $("#otp").attr("readonly", true);
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true,
                            "timeOut": "1500",
                        }
                        toastr.success(response.success);
                    }
                    if (response.error) {
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true,
                            "timeOut": "1500",
                        }

                        toastr.error(response.error);
                    }
                    console.log(response)
                },
                error: function(err) {
                    if (err.responseJSON['errors']) {
                        let error = err.responseJSON['errors'];
                        var msg = '';
                        $.each(error, (i, j) => {

                            $('.' + i).text(j)
                        });
                        let errKeys = Object.keys(err.responseJSON['errors']);
                        errKeys.map((item) => {
                            $('[name=' + item + ']').addClass('is-invalid');
                        });
                    }
                }
            });
        }
        $(function() {

            var td = new Date().getDay();
            td = (td == 0) ? 7 : td;
            $('select[name=customer_collection_day]').find('option').eq(td).prop('selected', true)
                .end().change();
        });
        var branch_selected_id = `<?php if (isset($customerSchemeDetail)) {
            echo $customerSchemeDetail->branch_id;
        } ?>`;
        if (branch_selected_id) {
            getData(branch_selected_id);
        }
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select an option",
            });
            $(".select2_demo").select2({
                placeholder: "Select an option",
            });
            var branch_id = $("#branch_id :selected").val();;
            if (branch_id != '') {
                getData(branch_id);
            }
            $('#customer-form').submit(function(e) {
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
                    data: $('#customer-form').serialize(),
                    beforeSend: function() {
                        $(document).find('sub.error-text').text('');
                    },
                    success: function(response) {
                        @if (!isset($customer))
                            swal(
                            'Success!',
                            'Customer details added.',
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
                            ' Area has been updated.',
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
                                $('.' + i).text(j)
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
        // $('#branch_id').on('change', function() {
        //     branch_id = ($(this).val());
        //     getData();
        // });

        function getData() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/admin/customer/get-schemes-area",
                method: 'POST',
                success: function(response) {
                    globalThis.scheme = response.schemes;
                    setScheme(response.schemes);
                    setArea(response.areas);
                },
                error: function(xhr) {
                    console.log(xhr)
                }
            });

        }

        function setArea(resp) {
            $('.area_select2').empty();
            resp.forEach(element => {
                var newOption = new Option(element.area_id + ' - ' + element.name, element.id, false, false);
                $('.area_select2').append(newOption).trigger('change');
            });
        }

        function setScheme(resp) {
            $('.scheme_select2').empty();

            resp.forEach(element => {
                var scheme_id = element.scheme_a_id + '-' + element.scheme_n_id;
                var newOption = new Option(scheme_id + ' ' + element.name, element.id, false, false);
                $('.scheme_select2').append('<option value="">--Select Option--</option>');
                $('.scheme_select2').append(newOption).trigger('    ');

            });
        }
        $('#scheme_id').on('change', function() {
            scheme_id = $(this).val();
            scheme.forEach(element => {
                if (element.id == scheme_id) {

                    document.getElementById("scheme_collection_day").value = element.scheme_collection_day;
                    document.getElementById("scheme_start_date").value = element.start_date;
                }
            });
        });
        $('.products_select2').on('change', function() {
            var product_id = $(this).val();

            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            $.ajax({
                url: "/admin/get-product-price",
                method: 'POST',
                data: {
                    product_id: product_id
                },
                success: function(response) {
                    $('#product_list').empty();
                    let sum = 0;
                   response.forEach(element => {
                   $('#product_list').append('<tr><td>'+element.product_code+'</td><td>'+element.name+'</td><td>'+element.mrp+'</tr>');
                    sum += element.mrp
                   });
                   console.log(sum);
                   $('#total').text(sum+' '+'Rs.');
                }
            });
        });
    </script>
@endpush
