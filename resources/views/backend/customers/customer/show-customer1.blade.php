@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <style>
            .modal-dialog {
                max-width: 900px;
            }
        </style>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->
        <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
        <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
        <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
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
                    Customer ID: {{ $customer->customer_id }}
                @endif
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
            </ol>
        </div>
        <div>
            <a href="/admin/customer/customers">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i>Customer List</button>
            </a>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="col-lg-12">
            <div class="card  card-fullheight">
                <div class="card-body">
                    <div>
                        <ul class="nav nav-tabs nav-top-border nav-tabs-lg">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab-info"><i
                                        class="ti-user nav-tabs-icon"></i>Customer Details</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-items"><i
                                        class="ti-receipt nav-tabs-icon"></i>Scheme Details</a></li>
                        </ul>
                        <div class="tab-content mt-4">

                            <div class="row">
                                <div class="col-md-12" id="edit-customer">
                                    <div class="card-body">

                                        <h5>Edit Customer</h5>
                                        <hr>
                                        <form action="/admin/customer/edit-customer/{{ $customer->id }}" method="POST"
                                            id="customer-edit-form">
                                            @csrf
                                            <div class="tab-content mt-4">
                                                <div class="tab-pane fade show active" id="tab-info">
                                                    <div class="row">
                                                        <div class="col-sm-4 form-group mb-4">
                                                            <label>customer Name<span class="text-danger">*</span></label>
                                                            <input class="form-control" type="text" id="name"
                                                                placeholder="Name"
                                                                value="{{ isset($customer) ? $customer->name : old('name') }}"
                                                                name="name">
                                                            <sub class="text-danger name" for="name"></sub>
                                                            <br>
                                                            <label>Username<span class="text-danger">*</span></label>
                                                            <input class="form-control" type="text" id="username"
                                                                placeholder="Username"
                                                                value="{{ isset($customer) ? $customer->username : old('username') }}"
                                                                name="username">
                                                            <sub class="text-danger username" for="username"></sub>
                                                        </div>
                                                        <div class="col-sm-4 form-group mb-4" id="show-phone">
                                                            <label>Phone<span class="text-danger">*</span></label>
                                                            <hr>
                                                            {{-- <input class="form-control"  type="number" id="phone" aria-describedby="basic-addon2"  placeholder="Phone" value="{{ isset($customer) ? $customer->phone : old('phone') }}"  name="phone"> --}}
                                                            <h6>{{ $customer->phone }}</h6>
                                                            <hr>
                                                            <br>
                                                            <button type="button" class="btn btn-outline-info btn-sm"
                                                                id="request">Change number</button>
                                                        </div>
                                                        <div class="col-sm-4 form-group mb-4" id="edit-phone">
                                                            <label>Phone<span class="text-danger">*</span></label>
                                                            <sub class="text-danger error-text phone" for="phone"></sub>
                                                            <div class="input-group mb-4">
                                                                <input class="form-control" type="number" id="phone"
                                                                    aria-describedby="basic-addon2" placeholder="Phone"
                                                                    value="{{ isset($customer) ? $customer->phone : old('phone') }}"
                                                                    name="phone">
                                                                <div class="input-group-append">
                                                                    <button class="btn btn-outline-success" type="button"
                                                                        onclick="editSendOTP({{ $customer->id }})">Send
                                                                        OTP</button>
                                                                </div>
                                                                <br>
                                                            </div>
                                                            <label>OTP<span class="text-danger  ">*</span></label>
                                                            <div class="input-group mb-4">
                                                                <input type="text" class="form-control" type="number"
                                                                    id="otp" name="otp" placeholder="otp"
                                                                    aria-label="otp" aria-describedby="basic-addon2">

                                                                <div class="input-group-append">
                                                                    <button class="btn btn-outline-success" type="button"
                                                                        onclick="validateOTP()">Validate OTP</button>
                                                                </div>

                                                            </div>
                                                            <sub class="text-danger error-text otp" for="otp"></sub>
                                                            <button type="button" class="btn btn-outline-info btn-sm"
                                                                id="cancel">Cancel changing number</button>
                                                        </div>
                                                        <div class="col-sm-4 form-group mb-4">
                                                            <label>Email</label>
                                                            <input class="form-control" type="email" id="email"
                                                                placeholder="Email"
                                                                value="{{ isset($customer) ? $customer->email : old('email') }}"
                                                                name="email">
                                                            <sub class="text-danger email" for="email"></sub>
                                                            <br>
                                                            <label>Secondary Phone</label>
                                                            <input class="form-control" type="text" id="phone_2"
                                                                placeholder="Secondary phone"
                                                                value="{{ isset($customer) ? $customer->phone_2 : old('phone_2') }}"
                                                                name="phone_2">
                                                        </div>

                                                        <div class="col-sm-3 form-group mb-4">
                                                            <label>Address</label>
                                                            <textarea class="form-control" name="address" id="address" cols="5" rows="6">{{ isset($customer) ? $customer->address : old('address') }}</textarea>
                                                        </div>
                                                        <div class="col-sm-3 form-group mb-4">
                                                            <br>
                                                            <label>Place<span class="text-danger">*</span></label>
                                                            <input class="form-control" type="text" id="place"
                                                                placeholder="Place"
                                                                value="{{ isset($customer) ? $customer->place : old('place') }}"
                                                                name="place">
                                                            <sub class="text-danger email" for="place"></sub>
                                                            <br>
                                                            <label>Area<span class="text-danger  ">*</span></label>
                                                            <select name="area_id"
                                                                class="select2  area_select2 form-control" id="area">
                                                                <option value=""
                                                                    {{ isset($customer) ? 'selected' : '' }}></option>
                                                                @foreach ($areas as $area)
                                                                    <option value="{{ $area->id }}"
                                                                        {{ isset($customer) ? ($customer->area_id == $area->id ? 'selected' : '') : '' }}>
                                                                        {{ $area->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <sub class="text-danger error-text area" for="area"></sub>


                                                        </div>
                                                        <div class="col-sm-3 form-group mb-4">
                                                            <label>Pincode</label>
                                                            <input class="form-control" type="text" id="pincode"
                                                                placeholder=" Pincode"
                                                                value="{{ isset($customer) ? $customer->pincode : old('pincode') }}"
                                                                name="pincode">
                                                            <sub class="text-danger pincode" for="pincode"></sub>
                                                            <br>
                                                            <label>House name</label>
                                                            <input class="form-control" type="text" id="house_name"
                                                                placeholder=" House name"
                                                                value="{{ isset($customer) ? $customer->house_name : old('house_name') }}"
                                                                name="house_name">
                                                            <sub class="text-danger house_name" for="house_name"></sub>
                                                        </div>

                                                        <div class="col-sm-3 form-group mb-4">

                                                            <label>Building</label>
                                                            <input class="form-control" type="text" id="building"
                                                                placeholder="Building Name"
                                                                value="{{ isset($customer) ? $customer->building : old('building') }}"
                                                                name="building">
                                                            <sub class="text-danger building" for="building"></sub>
                                                            <br>
                                                            <label>Land mark</label>
                                                            <input class="form-control" type="text" id="land_mark"
                                                                placeholder="Land Mark"
                                                                value="{{ isset($customer) ? $customer->land_mark : old('land_mark') }}"
                                                                name="land_mark">
                                                            <sub class="text-danger land_mark" for="land_mark"></sub>
                                                        </div>
                                                        <div class="col-sm-3 form-group mb-4">

                                                            <label>City</label>
                                                            <input class="form-control" type="text" id="city"
                                                                placeholder="City"
                                                                value="{{ isset($customer) ? $customer->city : old('city') }}"
                                                                name="city">
                                                            <sub class="text-danger city" for="city"></sub>
                                                        </div>
                                                        <div class="col-sm-3 form-group mb-4">

                                                            {{-- <label>Passwrod</label>
                                                                <input class="form-control" type="text" id="password"
                                                                    placeholder="Password"
                                                                    value="{{ isset($customer) ? $customer->password : old('password') }}"
                                                                    name="password">
                                                                <sub class="text-danger password" for="password"></sub> --}}
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="form-group">
                                                    <button class="btn btn-primary mr-2" id="submitForm"
                                                        type="submit">Submit</button>
                                                    <button id="hide" class="btn btn-primary">Back</button>
                                                </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" id="detail-customer">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Customer ID </h5>
                                </div>
                                <div class="col-md-6">
                                    <h5>{{ $customer->customer_id }}</h5>
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-6">Name </div>
                                <div class="col-md-6">{{ $customer->name }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">Joined Date </div>
                                <div class="col-md-6">
                                    {{ date('d-M-Y h:i A', strtotime($customer->created_at)) }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">Email </div>
                                <div class="col-md-6">{{ $customer->email }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">Phone </div>
                                <div class="col-md-6">{{ $customer->phone }}</div>
                            </div>
                            <div class="mt-3 mb-3">
                                <h5>Address Details</h5>
                            </div>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-6">Address </div>
                                <div class="col-md-6">{{ $customer->address }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">Place </div>
                                <div class="col-md-6">{{ $customer->place }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">City </div>
                                <div class="col-md-6">{{ $customer->city }}</div>
                            </div>
                            @hasanyrole('super-admin|developer-admin||office-administrator
                                |branch-manager|collection-manager|marketing-manager')
                                <div class="row mb-3">
                                    <button id="show" class="btn btn-primary" style="float: right;">Edit</button>
                                </div>
                            @endhasanyrole
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab-items">
                    <div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Scheme ID</th>
                                    <th scope="col">Scheme Name</th>
                                    <th scope="col">Scheme Collection Day</th>
                                    <th scope="col">Total Amount to be Pay</th>
                                    <th scope="col">Total Paid</th>
                                    {{-- <th scope="col">Advance amount</th> --}}
                                    {{-- <th scope="col">Pending Collection amount</th> --}}
                                    <th scope="col">Amount need to complete</th>
                                    <th scope="col">customer Collection Day</th>
                                    <th scope="col">Joined Date</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $n = 1;
                                @endphp
                                @foreach ($customerSchemes as $customerScheme)
                                    <tr>
                                        <td>{{ $n++ }}</td>
                                        @php
                                            $scheme = \App\Models\Scheme::where('id', $customerScheme->scheme_id)->first();
                                        @endphp
                                        <td>{{ $scheme->scheme_a_id . '-' . $scheme->scheme_n_id }}</td>
                                        <td>{{ $scheme->name }}</td>
                                        <td>{{ $scheme->scheme_collection_day }}</td>
                                        <td>{{ $scheme->total_amount }}</td>
                                        <td>{{ $customerScheme->total_amount }}</td>
                                        {{-- <td>{{ $customerScheme->advance_amount }}</td> --}}
                                        {{-- <td>{{ $customerScheme->pending_amount }}</td> --}}
                                        @php
                                            $due = $scheme->total_amount - $customerScheme->total_amount;
                                            if ($customerScheme->status == 0) {
                                                $status = 'Pending';
                                            } elseif ($customerScheme->status == 1) {
                                                $status = 'Active';
                                            } elseif ($customerScheme->status == 2) {
                                                $status = 'Completed';
                                            } elseif ($customerScheme->status == 3) {
                                                $status = 'Lucky Winner';
                                            } elseif ($customerScheme->status == 4) {
                                                $status = 'Stop';
                                            }
                                        @endphp
                                        <td>{{ $due }}</td>
                                        <td>{{ $customerScheme->collection_day }}</td>
                                        <td>{{ $customerScheme->joining_date }}</td>

                                        <td>{{ $status }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal"
                                                onclick="getCustomerScheme({{ $customerScheme->scheme_id }},{{ $customerScheme->customer_id }})">View
                                            </button>
                                            @hasanyrole('super-admin|developer-admin')
                                                @if ($customerSchemes->count() > 1)
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="deleteCustomerScheme({{ $customerScheme->scheme_id }},{{ $customerScheme->customer_id }})">Delete
                                                    </button>
                                                @endif
                                            @endhasanyrole
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @hasanyrole('super-admin|developer-admin|marketing-executive|collection-executive')
                            <div class="form-group">
                                <a href="/admin/customer/scheme-register/{{ $customer->id }}"> <button class="btn btn-light"
                                        type="reset">Scheme Register</button></a>
                            </div>
                        @endhasanyrole
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Scheme Weekly Details</h5>
                </div>
                <div class="modal-body">
                    <table class="table mb-4 details" id="table_details">
                        <thead class="thead-light">
                            <th scope="col">Week No:</th>
                            <th scope="col">Paid Amount </th>
                            <th scope="col">Due Amount</th>
                            <th scope="col">Paid Week Date</th>
                            <th scope="col">Paid Date</th>
                        </thead>
                        <tbody>
                            <tr>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        onclick="cleardataTable()">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="{{ asset('backend/assets/vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    {{-- <script src="{{ asset('backend/assets/vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select an option",
            });
        });

        function cleardataTable() {
            $("#table_details td").remove();
        }

        function getCustomerScheme(scheme_id, customer_id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/admin/customer/get-scheme-report",
                method: 'POST',
                data: {
                    "customer_id": customer_id,
                    "scheme_id": scheme_id,
                },
                success: function(response) {
                    setView(response);
                },
                error: function(xhr) {
                    console.log(xhr)
                }
            });
        }

        function deleteCustomerScheme(scheme_id, customer_id) {
            let text = "Are you sure!";
            if (confirm(text) == true) {
                text = "You pressed OK!";
            } else {
                text = "You canceled!";
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/admin/customer/delete-customer-scheme",
                method: 'POST',
                data: {
                    "customer_id": customer_id,
                    "scheme_id": scheme_id,
                },
                success: function(response) {
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                        "timeOut": "1500",
                    }
                    toastr.success(response.success);
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                },
                error: function(xhr) {
                    console.log(xhr)
                }
            });
        }
        $(document).ready(function() {
            $("#edit-customer").hide();
            $("#edit-phone").hide();
            $("#show-phone").show();
            $("#request").click(function() {
                $("#edit-phone").show();
                $("#show-phone").hide();
            });
            $("#cancel").click(function() {
                $("#edit-phone").hide();
                $("#show-phone").show();
            });
            $("#hide").click(function() {
                $("#edit-customer").hide();
                $("#detail-customer").show();
                location.reload()
            });
            $("#show").click(function() {
                $("#edit-customer").show();
                $("#detail-customer").hide();
            });
            $('#customer-edit-form').submit(function(e) {
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
                    data: $('#customer-edit-form').serialize(),
                    beforeSend: function() {
                        $(document).find('sub.error-text').text('');
                    },
                    success: function(response) {
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true,
                            "timeOut": "1500",
                        }
                        toastr.success(response.success);
                        setTimeout(function() {
                            //  window.location = document.referrer;
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
        });

        function editSendOTP(id) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/admin/customer/get-edit-otp",
                method: 'POST',
                data: {
                    "phone": $('#phone').val(),
                    'id': id,
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

        function setView(schemeReport) {
            let data;
            schemeReport.forEach(function(element, i) {
                var start_date = element.scheme.start_date;
                if (element.paid_week == 1) {
                    var formattedDate = start_date;
                } else {
                    var d = new Date(start_date);
                    var days = 7 * ((element.paid_week - 1));
                    d.setDate(d.getDate() + parseInt(days));
                    var yyyy = d.getFullYear();
                    var mm = (d.getMonth() + 1).toString().length > 1 ? (d.getMonth() + 1) : "0" + (d.getMonth() +
                        1);
                    var dd = (d.getDate()).toString().length > 1 ? (d.getDate()) : "0" + (d.getDate());
                    var formattedDate = yyyy + '-' + mm + '-' + dd;
                }
                data += `
                     <tr>
                        <td>` + (i + 1) + `</td>
                      <td>` + element.paid_amount + `</td>
                      <td>` + element.due_amount + `</td>
                      <td>` + formattedDate + `</td>
                      <td>` + element.paid_date + `</td>
                     </tr>
                     `
            });
            $('table.details').append(data);
        }
    </script>
@endpush
