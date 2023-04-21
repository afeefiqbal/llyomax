@extends('backend.layouts.backend')

@section('content')

    @push('styles')
        <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
        <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
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
                @if (isset($customer))
                    Edit customer
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
                    @if (isset($customer))
                        Edit customer
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

                    @if (isset($customer))
                        <form action="/admin/customer/customers/{{ $customer->id }}" method="PATCH" id="customer-form">
                            @method('PATCH')
                        @else
                            <form action="/admin/customer/customers" method="POST" id="customer-form">
                    @endif
                    @csrf

                    <div class="tab-content mt-4">
                        <div class="tab-pane fade show active" id="tab-info">
                            <div class="row">
                                <div class="col-sm-3 form-group mb-4">
                                    <label>customer Name<span class="text-danger  ">*</span></label>
                                    <input class="form-control" type="text" id="name" placeholder="Name"
                                        value="{{ isset($customer) ? $customer->name : old('name') }}" name="name">
                                    <sub class="text-danger name" for="scheme_name"></sub>
                                </div>
                                {{-- <div class="col-sm-3 form-group mb-4">
                                    <label>Parent Name<span class="text-danger  ">*</span></label>
                                    <input class="form-control" type="text" id="parent_name" placeholder="Parent Name"
                                        value="{{ isset($customer) ? $customer->name : old('parent_name') }}"
                                        name="parent_name">
                                    <sub class="text-danger parent_name" for="scheme_name"></sub>
                                </div> --}}
                                <div class="col-sm-3 form-group mb-4">
                                    <label>phone number<span class="text-danger  ">*</span></label>
                                    <input class="form-control" type="number" id="phone" placeholder="Phone"
                                        value="{{ isset($customer) ? $customer->phone : old('phone') }}" name="phone">
                                    <sub class="text-danger phone" for="scheme_name"></sub>
                                </div>
                                {{-- <div class="col-sm-3 form-group mb-4">
                                    <label>Second phone number <span class="text-danger  ">*</span></label>
                                    <input class="form-control" type="number" id="phone_2" placeholder="Second phone"
                                        value="{{ isset($customer) ? $customer->phone_2 : old('phone_2') }}"
                                        name="phone_2">
                                    <sub class="text-danger phone_2" for="scheme_name"></sub>
                                </div> --}}
                                <div class="col-sm-3 form-group mb-4">
                                    <label>Email<span class="text-danger  ">*</span></label>
                                    <input class="form-control" type="email" id="email" placeholder="Email"
                                        value="{{ isset($customer) ? $customer->email : old('email') }}" name="email">
                                    <sub class="text-danger email" for="email"></sub>
                                </div>
                                <div class="col-sm-3 form-group mb-4">
                                    <label>Password<span class="text-danger  ">*</span></label>
                                    <input class="form-control" type="password" id="password" placeholder=" Password"
                                        value="{{ isset($customer) ? $customer->password : old('password') }}"
                                        name="password">
                                    <sub class="text-danger password" for="scheme_name"></sub>
                                </div>
                                {{-- <div class="col-sm-3 form-group mb-4">
                                    <label>Username<span class="text-danger  ">*</span></label>
                                    <input class="form-control" type="text" id="username" placeholder="Username"
                                        value="{{ isset($customer) ? $customer->username : old('username') }}"
                                        name="username">
                                    <sub class="text-danger username" for="scheme_name"></sub>
                                </div> --}}
                                {{-- <div class="col-sm-3 form-group mb-4">
                                    <label>Pincode<span class="text-danger  ">*</span></label>
                                    <input class="form-control" type="text" id="pincode" placeholder=" Pincode"
                                        value="{{ isset($customer) ? $customer->pincode : old('pincode') }}"
                                        name="pincode">
                                    <sub class="text-danger pincode" for="scheme_name"></sub>
                                </div> --}}
                                {{-- <div class="col-sm-3 form-group mb-4">
                                    <label>House name<span class="text-danger  ">*</span></label>
                                    <input class="form-control" type="text" id="house_name" placeholder=" House name"
                                        value="{{ isset($customer) ? $customer->house_name : old('house_name') }}"
                                        name="house_name">
                                    <sub class="text-danger house_name" for="scheme_name"></sub>
                                </div> --}}
                                {{-- <div class="col-sm-3 form-group mb-4">
                                    <label>Building<span class="text-danger  ">*</span></label>
                                    <input class="form-control" type="text" id="building" placeholder="Building Name"
                                        value="{{ isset($customer) ? $customer->building : old('building') }}"
                                        name="building">
                                    <sub class="text-danger building" for="scheme_name"></sub>
                                </div> --}}
                                <div class="col-sm-3 form-group mb-4">
                                    <label>Area<span class="text-danger  ">*</span></label>
                                    <select name="area" class="select2  area_select2 form-control" id="area" required>
                                        <option value="" {{ isset($customer) ?: 'selected' }}></option>
                                    </select>
                                    <sub class="text-danger area" for="scheme_name"></sub>
                                </div>
                                {{-- <div class="col-sm-3 form-group mb-4">
                                    <label>Land mark<span class="text-danger  ">*</span></label>
                                    <input class="form-control" type="text" id="land_mark" placeholder="Land Mark"
                                        value="{{ isset($customer) ? $customer->land_mark : old('land_mark') }}"
                                        name="land_mark">
                                    <sub class="text-danger land_mark" for="scheme_name"></sub>
                                </div> --}}
                                <div class="col-sm-3 form-group mb-4">
                                    <label>City<span class="text-danger  ">*</span></label>
                                    <input class="form-control" type="text" id="city" placeholder="City"
                                        value="{{ isset($customer) ? $customer->name : old('name') }}" name="city">
                                    <sub class="text-danger city" for="scheme_name"></sub>
                                </div>
                                <div class="col-sm-4 form-group mb-4">
                                    <label>Address<span class="text-danger  ">*</span></label>
                                    <textarea name="address" id="address" class="form-control" cols="4"
                                        rows="3"></textarea>
                                    <sub class="text-danger address" for="scheme_name"></sub>
                                </div>
                                    <div class="col-sm-3 form-group mb-4">
                                        <label>remarks<span class="text-danger remarks ">*</span></label>
                                        <input class="form-control" type="text" id="remarks" placeholder="Remarks"
                                            value="{{ isset($customer) ? $customer->remarks : old('remarks') }}"
                                            name="remarks">
                                        <sub class="text-danger remarks" for="scheme_name"></sub>
                                    </div>
                                <div class="form-group col-md-2">
                                    <br>
                                    <label class="ui-switch switch-solid"> &nbsp;
                                        <input type="checkbox"
                                            {{ isset($product) ? ($product->status == '1' ? 'checked' : '') : 'checked' }}
                                            name="status">
                                        <span class="ml-0"></span>Active
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                @if (!isset($customer))
                                <div class="col-sm-6 form-group mb-4">
                                    <label>branch_id<span class="text-danger  ">*</span></label>

                                    <select name="branch_id" class=" select2 form-control" id="branch_id">
                                        <option value="" {{ isset($customer) ?: 'selected' }}></option>
                                        @isset($branches)
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}"
                                                    >{{ $branch->branch_name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    <sub class="text-danger branch_id" for="branch_id"></sub>
                                </div>
                                <div class="col-sm-6 form-group mb-4">
                                    <label>executive_id<span class="text-danger">*</span></label>
                                    <select name="executive_id" class="select2 form-control executive_select2" id="" required>
                                        <option value="" {{ isset($customer) ?: 'selected' }}></option>

                                    </select>
                                </div>
                                <div class="col-sm-6 form-group mb-4">
                                    <label>scheme_id<span class="text-danger  ">*</span></label>
                                    <select name="scheme_id" class=" select2  scheme_select2 form-control" id="" required>
                                        <option value="" {{ isset($customer) ?: 'selected' }}></option>
                                    </select>
                                    <sub class="text-danger scheme_id" for="scheme_id"></sub>
                                </div>
                                @else
                                <div class="col-sm-6 form-group mb-4">
                                    <label>scheme_id<span class="text-danger  ">*</span></label>
                                    @php
                                        $schemes = \App\Models\Scheme::where('branch_id',$customer->branch_id)->get();
                                    @endphp
                                    <select name="scheme_id" class=" select2  scheme_select2 form-control" id="" required>
                                        <option value="" {{ isset($customer) ?: 'selected' }}></option>
                                        @foreach ($schemes as $scheme)
                                        <option value="{{$scheme->id}}">{{$scheme->name}}</option>
                                        @endforeach
                                    </select>
                                    <sub class="text-danger scheme_id" for="scheme_id"></sub>
                                </div>
                                @endif

                                <div class="col-sm-6 form-group mb-4">
                                    <label>advance_amount<span class="text-danger  ">*</span></label>
                                    <input class="form-control" type="number" id="advance_amount"
                                        placeholder="advance_amount"
                                        value="{{ isset($customer) ? $customer->advance_amount : old('advance_amount') }}"
                                        name="advance_amount">
                                    <sub class="text-danger advance_amount" for="scheme_name"></sub>
                                </div>
                                <div class="col-sm-6 form-group mb-4">
                                    <label>collection_day<span class="text-danger ">*</span></label>
                                    <select name="collection_day" class="select2_demo form-control" id="">
                                        <option value="" selected></option>
                                        <option value="sunday">Sunday</option>
                                        <option value="monday">Monday</option>
                                        <option value="tuesday">Tuesday</option>
                                        <option value="wednesday">Wednesday</option>
                                        <option value="thursday">Thursday</option>
                                        <option value="friday">Friday</option>
                                        <option value="saturday">Saturday</option>
                                    </select>
                                    <sub class="text-danger collection_day" for="scheme_name"></sub>
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

    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>
    <script src="{{ asset('backend/assets/vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    {{-- <script src="{{ asset('backend/assets/vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- CORE SCRIPTS-->
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select an option",
            });
            $(".select2_demo").select2({
                placeholder: "Select an option",
            });

            $(".select2_demo").select2({
                placeholder: "Select an option",
            });

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
                    success: function(response) {
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true,
                            "timeOut": "1500",
                        }
                        toastr.success(response.success);
                        setTimeout(function() {
                            // window.history.back();
                        }, 2000);


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
            $(document).ready(function() {
                $('.js-example-basic-single').select2();
            });
        });
        $('#branch_id').on('change', function() {
            branch_id = ($(this).val());
            if (branch_id) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/admin/customer/get-schemes",
                    method: 'POST',
                    data: {
                        "branch_id": branch_id,
                    },
                    success: function(response) {
                        setScheme(response);
                    },
                    error: function(xhr) {
                        console.log(xhr)
                    }
                });
                $.ajax({
                    url: "/admin/customer/get-areas",
                    method: 'POST',
                    data: {
                        "branch_id": branch_id,
                    },
                    success: function(response) {
                        setArea(response);
                    },
                    error: function(xhr) {
                        console.log(xhr)
                    }
                });
            }
        });
        $('#area').on('change', function() {
            area_id = ($(this).val());
            var branch_id = $('#branch_id').val();
            if (area_id) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/admin/customer/get-executives",
                    method: 'POST',
                    data: {
                        "branch_id": branch_id,
                        "area_id": area_id,
                    },
                    success: function(response) {
                        console.log(response);
                        setExecutive(response);
                    },
                    error: function(xhr) {
                        console.log(xhr)
                    }
                });
            }
        });
        function setArea(resp) {
            $('.area_select2').empty();
            resp.forEach(element => {
                var newOption = new Option(element.area_id + ' - ' + element.name, element.id, false, false);
                $('.area_select2').append(newOption).trigger('change');
            });
        }
        function setExecutive(resp) {
            $('.executive_select2').empty();
            resp.forEach(element => {
                var newOption = new Option(element.executive_id + ' ' + element.name, element.id, false, false);
                $('.executive_select2').append(newOption).trigger('change');
            });
        }

        function setScheme(resp) {
            $('.scheme_select2').empty();
            resp.forEach(element => {
                var newOption = new Option(element.scheme_id + ' ' + element.name, element.id, false, false);
                $('.scheme_select2').append(newOption).trigger('change');
            });
        }

    </script>
@endpush
