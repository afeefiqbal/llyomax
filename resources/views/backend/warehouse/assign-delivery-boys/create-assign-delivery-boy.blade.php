@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
        <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
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
                @if (isset($assignDeliveryBoy))
                    Assign Delivery Boy
                @else
                Assign Delivery Boy
                @endif
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item">
                    @if (isset($assignDeliveryBoy))
                    Assign Delivery Boy
                    @else
                    Assign Delivery Boy
                    @endif
                </li>
            </ol>
        </div>
        <div>
            <a href="/admin/warehouse/assigning-delivery-boys">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i> Back to List</button>
            </a>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="col-lg-12">
            <div class="card card-fullheight">
                <div class="card-body">
                    @if (isset($assignDeliveryBoy))
                    <form action="/admin/warehouse/assigning-delivery-boys/{{ $assignDeliveryBoy->id }}" method="POST" id="categoryForm">
                        @method('PATCH')
                        @else
                            <form action="/admin/warehouse/assigning-delivery-boys" method="POST" id="categoryForm">
                    @endif
                    @csrf
                    <div class="row">
                        <div class="col-sm-3" >
                            <label for="delivery_date">Date</label>
                            <input type="date" class="form-control" id="date" name="date"  max="<?= date('Y-m-d'); ?>"
                            value="{{ isset($assignDeliveryBoy) ? $assignDeliveryBoy->assign_date->format('Y-m-d') : date('Y-m-d') }}" placeholder="Assign Date">
                        </div>
                        <div class="col-sm-3 form-group mb-4">
                            <label>Order  ID<span class="text-danger ">*</span></label>
                            <select name="order_id" class="select2_demo form-control" id="order_id">
                                <option value=""></option>
                            @foreach ($orders as $order)
                                <option value="{{ $order->id }}"
                                    @isset($assignDeliveryBoy)
                                        @if ($assignDeliveryBoy->order_id == $order->id) selected @endif
                                    @endisset>
                                        {{ $order->order_id }}
                                </option>

                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3 form-group mb-4">
                            <label for="delivery_boy_id">Delivery Boy<span class="text-danger ">*</span>        </label>
                            <select name="delivery_boy_id" class="select2_demo form-control" id="delivery_boy_id">
                                @foreach ($deliveryBoys as $deliveryBoy)
                                <option value="{{ $deliveryBoy->id }}"
                                    @isset($assignDeliveryBoy)
                                        @if ($assignDeliveryBoy->delivery_boy_id == $deliveryBoy->id) selected @endif
                                    @endisset>
                                        {{ $deliveryBoy->delivery_boy_id.'-'.$deliveryBoy->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3 form-group mb-4">
                            <label for="customer_id">Customers<span class="text-danger ">*</span></label>
                            <select name="customer_id" class="select2_demo form-control" id="customer_id">
                                @isset($assignDeliveryBoy)
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                            @if ($assignDeliveryBoy->customer_id == $customer->id) selected @endif
                                        >
                                            {{ $customer->customer_id.'-'.$customer->name }}
                                        </option>
                                    @endforeach
                                    
                                @endisset
                            </select>
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
        $(document).ready(function() {
            $(".select2_demo").select2({
                placeholder: "Select an option",
            });
            $("#categoryForm").validate({
                rules: {
                    name: {
                        minlength: 2,
                        required: !0
                    },
                    cat_id: {
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
            $('#categoryForm').submit(function(e) {
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
                    data: $('#categoryForm').serialize(),
                    success: function(data) {
                        @if (!isset($assignDeliveryBoy))
                            swal(
                            'Success!',
                            'Record has been added.',
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
                            ' Record has been updated.',
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
                                $(e).addClass("is-invalid").removeClass('is-valid');
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
        $('#order_id').on('change', function() {
            var order_id = $(this).val();
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            $.ajax({
                url: "/admin/warehouse/get-customers",
                type: "POST",
                data: {
                    order_id: order_id
                },
                success: function(data) {
                    $('#customer_id').empty();
                    $('#customer_id').append('<option value=""></option>');
                    $('#customer_id').append('<option value="' + data.id + '">' + data.customer_id+'-'+data.name + '</option>');
                }
            });

        });
    </script>
@endpush
