@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
        <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
        <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
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
                @if (isset($product))
                    Edit product
                @else
                    Create product
                @endif
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Warehouse</li>
                <li class="breadcrumb-item">Product</li>
                <li class="breadcrumb-item">
                    @if (isset($product))
                        Edit Product
                    @else
                        Create Product
                    @endif
                </li>
            </ol>
        </div>
        <div>
            <a href="/admin/warehouse/products">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i> products List</button>
            </a>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="col-lg-12">
            <div class="card card-fullheight">
                <div class="card-body">
                    @if (isset($product))
                    <form action="/admin/warehouse/products/{{ $product->id }}" method="POST" id="productForm">
                        @method('PATCH')
                        @else
                            <form action="/admin/warehouse/products" method="POST" id="productForm">
                    @endif
                    @csrf
                    <div class="row">

                        <div class="col-sm-3 form-group mb-4">
                            <label>product  ID<span class="text-danger">*</span></label>
                            <input class="form-control"  type="text" id="product_code" placeholder="product  ID"
                                value="{{ isset($product) ? $product->product_code : old('product_code') }}" name="product_code">
                        </div>
                        <div class="col-sm-3 form-group mb-4">
                            <label> Name<span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="name" placeholder=" Name"
                                value="{{ isset($product) ? $product->name : old('name') }}" name="name"  >
                        </div>
                            <div class="col-sm-3 form-group mb-4">
                                <label>Category  ID</label>
                                @isset($categories)
                                    <select class="form-control select2" id="category_id" name="category_id">
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ isset($product) && $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->cat_id.'-'.$category->name }}</option>
                                        @endforeach
                                    </select>
                                @endisset
                            </div>
                        <div class="col-sm-3 form-group mb-4">
                            <label> SKU</label>
                            <input class="form-control" type="text" id="sku" placeholder=" SKU"
                                value="{{ isset($product) ? $product->sku : old('sku') }}" name="sku"  >
                        </div>
                        <div class="col-sm-3 form-group mb-4">
                            <label> Type</label>
                            <input class="form-control" type="text" id="type" placeholder=" Type"
                                value="{{ isset($product) ? $product->type : old('type') }}" name="type"  >
                        </div>
                        <div class="col-sm-3 form-group mb-4">
                            <label> MRP <span class="text-danger">*</span></label>
                            <input class="form-control" type="number" id="mrp" placeholder=" MRP"
                                value="{{ isset($product) ? $product->mrp : old('mrp') }}" name="mrp"  >
                        </div>
                        <div class="col-sm-3 form-group mb-4">
                            <label> LRP </label>
                            <input class="form-control" type="number" id="lrp" placeholder=" LRP"
                                value="{{ isset($product) ? $product->lrp : old('lrp') }}" name="lrp"  >
                        </div>
                        <div class="col-sm-3 form-group mb-4">
                            <label> Quantity </label>
                            <input class="form-control" type="number" id="qty" placeholder=" Quantity"
                                value="{{ isset($product) ? $product->qty : old('qty') }}" name="qty"  >
                        </div>

                        <div class="col-sm-6 form-group mb-4">
                            <label>Product Image<span class="text-danger">*</span></label>
                            <input type="file" class="product_image" name="product_image"
                                value="{{ isset($product)? $product->product_image : old('product_image') }}">
                        </div>
                        <div class="col-sm-6 form-group mb-4">
                            <label>Product Description</label>
                            <textarea name="description" id="description" class="form-control" cols="30" rows="4">{{ isset($product) ? $product->description : old('description') }}</textarea>
                        </div>
                        <div class="col-sm-3 form-group mb-4">
                            <label>Status</label>
                            <br>
                            <label class="ui-switch switch-solid"><input type="checkbox" id="status" checked
                                name="status" {{ isset($product) && $product->status ? 'checked' : old('status') }}><span></span></label>
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
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
<script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>
<script src="{{ asset('backend/assets/vendors/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('backend/assets/vendors/jquery-validation/dist/jquery.validate.min.js')}}"></script>
    <!-- CORE SCRIPTS-->
    <script>
        $(document).ready(function() {
            FilePond.registerPlugin(
                FilePondPluginFileValidateSize,
                FilePondPluginFileValidateType,
                FilePondPluginImagePreview,
                FilePondPluginFileEncode
            );

            $('.product_image').filepond({
                allowFileTypeValidation: true,
                allowFileSizeValidation: true,
                maxFileSize: '5MB',
                acceptedFileTypes: ['image/*'],
                allowImagePreview: true,
                allowFileEncode: true,
                credits: false,
                @if(isset($branch) && $branch->hasMedia('branch_images'))
                files: [{
                source: "{{$branch->getFirstMediaUrl('branch_images')}}",
                }],
                @endif
            });
            $('.select2').select2({
                placeholder: "Select an option",
            });
            $("#productForm").validate({
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
            $('#productForm').submit(function(e) {
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
                    data: $('#productForm').serialize(),
                    success: function(data) {
                        @if (!isset($product))
                            swal(
                            'Success!',
                            'Product has been added.',
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
                            'Product has been updated.',
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
    </script>
@endpush
