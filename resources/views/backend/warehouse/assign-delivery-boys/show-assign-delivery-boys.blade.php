@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <link href="{{ asset('backend/assets/vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush
    <!-- BEGIN: Page heading-->
    <div class="page-heading">
        <div class="page-breadcrumb">
            <h1 class="page-title page-title-sep">Assigned Order Details</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>

            </ol>
        </div>
        <div>
            <a href="/admin/warehouse/orders">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i> Back to List</button>
            </a>
        </div>
    </div>

    <!-- End Page Heading -->
    <div>
        <span id="deliveryOrderID" hidden>{{ $assignDeliveryBoy->id }}</span>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="list-group">
                            <li class="list-group-item ">
                                <h6>Order No : <h2>{{ $assignDeliveryBoy->order->order_id }}</h2>
                                </h6>
                            </li>
                            <li class="list-group-item ">
                                <h6>Delivery Boy Details :</h6>
                                <table class="table table-border">
                                    <thead>
                                        <th>ID</th>
                                        <th> Name</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                {{ $assignDeliveryBoy->deliveryBoy->delivery_boy_id }}
                                            </td>
                                            <td>
                                                {{ $assignDeliveryBoy->deliveryBoy->name }}
                                            </td>
                                            <td>
                                                {{ $assignDeliveryBoy->deliveryBoy->phone }}
                                            </td>
                                            <td>
                                                {{ $assignDeliveryBoy->deliveryBoy->address }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </li>
                            <li class="list-group-item ">
                                <div class="row">
                                    <div class="col-sm-10">
                                        <h6>Assigned Date :</h6> {{ $assignDeliveryBoy->assign_date->format('d-M-Y') }}
                                    </div>
                                    <div class="col-sm-2">
                                        <h6>Delivered On :</h6>
                                        {{ isset($assignDeliveryBoy->delivery_date) ? $assignDeliveryBoy->delivery_date->format('d-M-Y') : '' }}
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item ">
                                <h6>Customer Details :</h6>
                                <table class="table table-border">
                                    <thead>
                                        <th>ID</th>
                                        <th> Name</th>
                                        <th>Phone</th>
                                        <th>Place</th>
                                        <th>Address</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                {{ $assignDeliveryBoy->customer->customer_id }}
                                            </td>
                                            <td>
                                                {{ $assignDeliveryBoy->customer->name }}
                                            </td>
                                            <td>
                                                {{ $assignDeliveryBoy->customer->phone }}
                                            </td>
                                            <td>
                                                {{ $assignDeliveryBoy->customer->place }}
                                            </td>
                                            <td>
                                                {{ $assignDeliveryBoy->customer->address }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </li>
                            <li class="list-group-item ">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h6>Order Status :&nbsp;&nbsp;
                                            @php
                                                $status = $assignDeliveryBoy->order->status;
                                                if($status == 0){
                                                    echo '<span class="badge badge-danger">Pending</span>';
                                                }elseif($status == 1){
                                                }elseif($status == 2){
                                                    echo 'Cancelled</span>';
                                                }elseif($status == 3){
                                                    echo '<span class="badge badge-info">To be delivered</span>';
                                                }

                                            @endphp
                                    </div>
                                    <div class="col-sm-6">
                                        <div>
                                            <h6>&nbsp;&nbsp;
                                                @php
                                                    $user = auth()->user();
                                                    $userRole = $user->roles->pluck('name')->first();
                                                @endphp
                                                @if ($userRole == 'delivery-boy')
                                                <button class="status btn  btn-sm">Secondary</button></h6>
                                                @else
                                               @php
                                                    $status = $assignDeliveryBoy->is_delivered;
                                               @endphp
                                                @if ($status == 0)
                                                    <button class=" btn status  btn-sm btn-info">Not Delivered</button></h6>
                                                @else
                                                    <button class=" btn  btn-sm btn-success">Delivered</button></h6>
                                                @endif
                                                @endif
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('backend/assets/vendors/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        getOrderStatus();

        function getOrderStatus() {
            var deliveryOrderID = $('#deliveryOrderID').text();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/admin/warehouse/get-order-status",
                type: "POST",
                data: {
                    deliveryOrderID: deliveryOrderID
                },
                success: function(data) {
                    if (data.is_delivered == 0) {
                        $(".status").addClass("btn-danger");
                        $(".status").text('Update Status');
                    } else {
                        $(".status").addClass("btn-success");
                        $(".status").text('delivered');
                        $(".status").attr("disabled","disabled");

                    }
                }
            });
        }
        $('.status').on('click', function() {
            Swal.fire({
                icon: 'question',
                iconHtml: '؟',
                title: 'Are you sure?',
                showDenyButton: true,
                confirmButtonText: 'Update',
                denyButtonText: `Cancel`,
            }).then((result) => {
                if (result.isConfirmed) {
                    var deliveryOrderID = $('#deliveryOrderID').text();
                    var status = $(this).text();
                    if (status == 'Pending') {
                        status = 0;
                    } else {
                        status = 1; 
                    }
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "/admin/warehouse/update-order-status",
                        type: "POST",
                        data: {
                            deliveryOrderID: deliveryOrderID,
                            status: status
                        },
                        success: function(data) {
                            if (data = "success") {
                                Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Status has been updated',
                        showConfirmButton: false,
                        timer: 1500
                    })
                                getOrderStatus();
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            } else {
                                Swal.fire('Status not updated', '', 'info')
                            }
                        }
                    });
                    //
                } else if (result.isDenied) {
                    Swal.fire('Status not updated', '', 'info')
                }
            })


        });
    </script>
@endpush
