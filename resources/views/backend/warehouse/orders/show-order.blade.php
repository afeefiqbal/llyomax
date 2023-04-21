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
            <h1 class="page-title page-title-sep">Order Details</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>

            </ol>
        </div>
        <div>
            <a href="/admin/warehouse/orders">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i> Order List</button>
            </a>
        </div>
    </div>

    <!-- End Page Heading -->
    <div>
        <div class="card">
            <div class="card-body">
                <h4>Order ID : {{$order->order_id}}</h4>
                <div class="row">
                    <div class="col-sm-6">
                        <ul class="list-group">
                            <li class="list-group-item "><h6>Customer Name :</h6> {{$order->customer->name }}</li>
                            <li class="list-group-item"><h6>Order Date :  </h6>{{$order->order_date}}</li>
                            <li class="list-group-item"><h6>Address : </h6>{{$order->shipping_address }}</li>
                            <li class="list-group-item"><h6>Order Amount :</h6>{{$order->net_amount }} </li>

                          </ul>
                    </div>
                    <div class="col-sm-6">
                        <li class="list-group-item">
                            Order Details:
                            <table class="table">
                                <thead>
                                    <th>Product Name </th>
                                    <th>Category Name </th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </thead>
                                <tbody>
                                    @php
                                        $orderProduct = DB::select("SELECT * FROM order_product WHERE order_id = '$order->id'");
                                    @endphp
                                  @foreach ($orderProduct  as $item)

                                    <tr>
                                        <td>
                                            @php
                                                $product = DB::table('products')->where('id',$item->product_id)->first();
                                            @endphp
                                            {{$product->name}}
                                        </td>
                                        <td>
                                            @php
                                                $category = DB::table('categories')->where('id',$product->category_id)->first();
                                            @endphp
                                            {{$category->name}}
                                        </td>
                                        <td>{{$item->qty}}</td>
                                        <td>
                                            @php
                                                $product = DB::table('products')->where('id',$item->product_id)->first();
                                            @endphp
                                            {{$product->mrp}}
                                        </td>
                                        <td>
                                            {{$item->price}}
                                        </td>
                                    </tr>
                                  @endforeach
                                </tbody>
                            </table>
                        </li>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('backend/assets/vendors/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script type="text/javascript">

    </script>
@endpush
