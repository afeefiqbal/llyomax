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
            <h1 class="page-title page-title-sep">Product Details</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Product Details</li>
                <li class="breadcrumb-item">Product Details</li>
            </ol>
        </div>
        <div>
            <a href="/admin/warehouse/products">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i> Product List</button>
            </a>
        </div>
    </div>

    <!-- End Page Heading -->
    <div>
        <div class="card">
            <div class="card-body">

                <div class="row">
                    <div class="col-sm-6">
                        <ul class="list-group">
                            <li class="list-group-item active"><h6>Product Code : </h6>{{$product->product_code }}</li>
                            <li class="list-group-item "><h6>Product Name :</h6> {{$product->name }}</li>
                            <li class="list-group-item"><h6>Category :  </h6>{{$product->category->name }}</li>
                            <li class="list-group-item"><h6>MRP : </h6>{{$product->mrp }}</li>
                            <li class="list-group-item"><h6>LRP :</h6>{{$product->lrp }} </li>
                            <li class="list-group-item"><h6>Description : </h6>{{$product->description }}</li>
                            <li class="list-group-item"><h6>Type : </h6>{{$product->type }}</li>
                            <li class="list-group-item"><h6>SKU : </h6>{{$product->sku }}</li>
                            <li class="list-group-item"><h6>Status : </h6> &nbsp;&nbsp;&nbsp;
                                <label>
                                    @if ($product->status == 1)
                                        <span class="badge badge-success">On Stock </span>
                                    @else
                                        <span class="badge badge-warning" style="color: white"> Out of Stock</span>
                                    @endif
                                </label>
                            </li>
                          </ul>
                    </div>
                    <div class="col-sm-6">
                        <ul class="list-group">
                            <li class="list-group-item ">Product Image : <br> <br>
                                <img class="fit-picture" src="{{ $product->getFirstMediaUrl('product_images') }}" alt="">
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
    <script type="text/javascript">
   
    </script>
@endpush
