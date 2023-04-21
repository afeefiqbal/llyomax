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

            .select2-container--default .select2-selection--single {

                height: 38px;
                padding: 6px 5px;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow b {
                top: 69%;
                width: 0;
            }
        </style>
    @endpush
    <!-- BEGIN: Page heading-->
    <div class="page-heading">
        <div class="page-breadcrumb">
            <h1 class="page-title page-title-sep">
                New Order
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">
                    New Order

                </li>
            </ol>
        </div>
        <div>
            <a href="/admin/warehouse/orders">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i>Orders</button>
            </a>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="col-lg-12">
            <div class="card card-fullheight">
                <div class="card-body">
                    <form action="/admin/warehouse/orders" method="POST" id="orderForm">
                        @csrf
                        <div class="row">

                            <div class="col-sm-4 form-group mb-4">
                                <label>Order ID<span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="cat_id" placeholder="Category  ID"
                                    value="{{ isset($ordID) ? $ordID : old('order_id') }}" name="order_id" readonly>
                            </div>
                            <div class="col-sm-4 form-group mb-4">
                                <label for="">Date</label>
                                <input type="date" value="{{ date('Y-m-d') }}" readonly id="order_date"
                                    name="order_date" class="form-control">
                            </div>
                            <div class="col-sm-4 form-group mb-4">
                                <label>Customer Name<span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <select name="customer_id" id="customer_id" class="form-control select2">
                                        <option value=""></option>
                                        @isset($customers)
                                            @foreach ($customers as $cust)
                                                <option value="{{ $cust->id }}">{{ $cust->name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    <div class="input-group-append">
                                        <span data-toggle="modal" data-target="#customerModel" class="input-group-text"
                                            id="basic-addon2"><i class="la la-plus"></i>
                                            new</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 form-group mb-4">
                                <label for="">Customer Phone</label>
                                <input type="number" id="phone" name="phone" class="form-control"
                                    placeholder="Customer Phone">
                            </div>
                            <div class="col-sm-5 form-group mb-4">
                                <label for="">Shipping Addres</label>
                                <textarea id="shipping_address" name="shipping_address" class="form-control" rows="3" cols="3"></textarea>
                            </div>
                            <div class="col-sm-6 form-group mb-4">
                                <label>Product Name<span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <select name="product_id" id="product_id" class="form-control select2">
                                        <option value=""></option>
                                        @isset($products)
                                            @foreach ($products as $prod)
                                                <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4 form-group mb-4">
                            </div>
                            <div class="col-sm-2 form-group mb-4">
                                <br> &nbsp; <br>
                                <button type="button" id="add_new" class="btn btn-info" hidden>Add +</button>
                            </div class="col-sm-12 form-group mb-4">
                            <table id="maintable" class="table table-bordered">
                                <thead class="thead-dark">
                                    <th align="center">Product ID</th>
                                    <th align="center">Product Name</th>
                                    <th align="center">Qauntity</th>
                                    <th align="center">MRP</th>
                                    <th align="center">Sub Total</th>
                                    <th align="center">Action</th>
                                </thead>
                                <tbody id="rows"> </tbody>
                            </table>
                            <div>

                            </div>

                            <div class="col-sm-3 form-group mb-4">
                                <label for="">Quantity<span class="text-danger">*</span></label>
                                <input type="number" id="quantity" readonly name="quantity" class="form-control"
                                    placeholder="Quantity">
                            </div>
                            <div class="col-sm-3 form-group mb-4">
                                <label for="">Sub Amount</label>
                                <input type="number" id="sub_amount" name="sub_amount" readonly class="form-control"
                                    placeholder="">
                            </div>
                            <div class="col-sm-3 form-group mb-4">
                                <label for="">Discount Amount</label>
                                <input type="number" id="discount" name="discount" class="form-control"
                                    placeholder="Discount">
                            </div>
                            <div class="col-sm-3 form-group mb-4">
                                <label for="">Shipping charge</label>
                                <input type="number" id="shipping_charge" name="shipping_charge" class="form-control"
                                    placeholder="Shipping charge">
                            </div>
                            <div class="col-sm-3 form-group mb-4">
                                <label for="">Total amount<span class="text-danger">*</span></label>
                                <input type="number" id="total_amount" name="total_amount" readonly
                                    class="form-control" placeholder="Total amount">
                            </div>
                            <div class="col-sm-3 form-group mb-4">
                                <label for="">Scheme amount<span class="text-danger">*</span></label>
                                <input type="number" id="scheme_amount" readonly name="scheme_amount"
                                    class="form-control" placeholder="Scheme amount">
                            </div>
                            <div class="col-sm-3 form-group mb-4">
                                <label for="">Total amount<span class="text-danger">*</span></label>
                                <input type="number" id="net_amount" name="net_amount" class="form-control"
                                    placeholder="Total amount">
                            </div>
                            <div class="col-sm-12 form-group mb-4">
                                <label for="">Note</label>
                                <textarea name="note" id="note" class="form-control" cols="4" rows="5"></textarea>
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


    <!-- Modal -->
    <div class="modal fade" id="customerModel" tabindex="-1" role="dialog" aria-labelledby="customerModelTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/admin/order/customer" method="POST"id="newCustomerForm">
                        <div class="row">
                            <div class="col-sm-6 form-group mb-4">
                                <label>customer Name<span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="name" placeholder="Name" required
                                    value="{{ old('name') }}" name="name">
                                <sub class="text-danger name" for="name"></sub>
                                <br>
                            </div>
                            <div class="col-sm-6 form-group mb-4" id="edit-phone">
                                <label>Phone<span class="text-danger">*</span></label>
                                <input class="form-control" type="number" id="phone" required placeholder="Phone"
                                    value="{{ old('phone') }}" name="phone">
                            </div>
                            <div class="col-sm-6 form-group mb-4">
                                <label>Place</label>
                                <input class="form-control" type="text" id="place" placeholder="Place"
                                    value="{{ old('place') }}" name="place">
                                <sub class="text-danger email" for="place"></sub>
                            </div>
                            <div class="col-sm-6 form-group mb-4">
                                <label>City</label>
                                <input class="form-control" type="text" id="city" placeholder="City"
                                    value="{{ old('city') }}" name="city">
                                <sub class="text-danger city" for="city"></sub>
                            </div>
                            <div class="col-sm-12 form-group mb-4">
                                <label>Address</label>
                                <textarea class="form-control" name="address" id="address" required cols="5" rows="6">{{ old('address') }}</textarea>
                            </div>

                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                </form>
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
        function cnew() {
            $('#customerModel').modal('show');
        }
        $(window).on('load', function() {
            cart = [];
            if (localStorage.getItem("cart") !== null) {
                cart = JSON.parse(localStorage.getItem("cart"));
                localStorage.removeItem("cart", JSON.stringify(cart));
            }
            if (cart.length > 0) {
                localStorage.removeItem("cart", JSON.stringify(cart));
            }
        });

        $(document).ready(function() {
            $(".select2").select2({
                placeholder: "Select an option",
                language: {
                    noResults: function() {
                        return $(
                            `<button type ="button"  class="input-group-text" id="basic-addon2" onclick="cnew()" id="new"> + new</button>`
                            );
                    }
                }
            });
            $('#customer_id').on('change', function() {
                
                var customer_id = $(this).val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/admin/getCustomer/",
                    type: "POST",
                    data: {
                        customer_id: customer_id
                    },
                    success: function(data) {

                        setProductTableCstomer(data.customerProduct);
                        $('#phone').val(data.customer.phone);
                        $('#shipping_address').append(data.customer.address);
                        $('#scheme_amount').val(data.total_amount);
                    }
                });
            });
            $("#orderForm").validate({
                rules: {
                    name: {
                        minlength: 2,
                        required: !0
                    },
                    net_amount: {
                        min: 1
                    },
                    messages: {
                        net_amount: {
                            min: "Value must be greater than 0"
                        },
                    }
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
            $('#newCustomerForm').submit(function(e) {
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
                    data: $('#newCustomerForm').serialize(),
                    success: function(data) {
                        @if (!isset($category))
                            swal(
                                'Success!',
                                'New Customer Added successfully',
                                'success'
                            ).then(() => {
                                $('#customerModel').modal('hide');
                                console.log(data);
                                $('#customer_id').append(
                                    `<option value="${data.id}">${data.name}</option>`
                                    );
                            });
                            form.trigger('reset');
                        @else
                            swal(
                                'Success!',
                                ' Category has been updated.',
                                'success'
                            ).then(() => {
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
            $('#orderForm').submit(function(e) {
                e.preventDefault();
                var cart = [];
                if (localStorage.getItem("cart") !== null) {
                    cart = JSON.parse(localStorage.getItem("cart"));
                    // localStorage.removeItem("cart", JSON.stringify(cart));
                }
                var cartArray = []

                cart.map((item) => {
                    cartArray.push({
                        product_id: item.id,
                        quantity: item.quantity,
                        price: item.price,
                        total: item.total
                    });
                });
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
                    data: $('#orderForm').serialize() + '&cart=' + cartArray,
                    success: function(data) {
                        @if (!isset($category))
                            swal(
                                'Success!',
                                'Order has been successfully Completed.',
                                'success'
                            ).then(() => {
                                setTimeout(function() {
                                    console.log(data);
                                    localStorage.removeItem('cart');
                                    printInvoice(data)
                                }, 1000);
                            });
                            form.trigger('reset');
                        @else
                            swal(
                                'Success!',
                                ' Category has been updated.',
                                'success'
                            ).then(() => {
                                setTimeout(function() {
                                    printInvoice(data)
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
        $('#product_id').on('change', function() {
            let product_id = $(this).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/admin/get-product",
                type: "POST",
                data: {
                    product_id: product_id
                },
                success: function(data) {
                    productCart(data);

                }
            });
        });
        $("#add_new").click(function() {

            // $("#maintable").each(function () {
            //     var tds = '<tr>';
            //     jQuery.each($('tr:last td', this), function () {
            //         tds += '<td>' + $(this).html() + '</td>';
            //     });
            //     tds += '</tr>';
            //     if ($('tbody', this).length > 0) {
            //         $('tbody', this).append(tds);
            //     } else {
            //         $(this).append(tds);
            //     }
            // });
            $("#maintable").on('click', '.btnDelete', function() {
                var length = ($('#maintable tr').length);
                if (length > 2) {
                    $(this).closest('tr').remove();
                } else {}
            });
        });

        function setProductTable(cart) {
            $('#rows').append(`
            <tr id="">
                    <td>
                        <span id="productID">` + cart.product_code + `</span>
                    </td>
                    <td>
                        <span id="productName">` + cart.name + `</span>
                    </td>
                    <td>
                        <input class="form-control qty" type="number" name="qty" id="qty"  value="1" data-id= "` + cart
                .id + `">
                    </td>
                    <td>
                        <span id="price">` + cart.mrp + `</span>
                    </td>
                    <td>
                        <span class="sub_total" id="sub_total">` + cart.mrp + `</span>
                    </td>

                    <td>
                        <div id="removeDiv">
                            <button type="button" class="btn btn-primary btn-sm btnDelete" data-id="` + cart.id + `" >Delete</button>
                        </div>
                    </td>
                </tr>
                `);

        }

        function setProductTableCstomer(customerProduct) {
            productID = [];
            customerProduct.forEach(element => {
                productID.push(element.id);
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/admin/get-product-array",
                type: "POST",
                data: {
                    product_id: productID
                },
                success: function(data) {
                    productCartArray(data);

                }
            });
        }

        function productCartArray(product) {
            product.forEach(data => {
                product_id = data.id;
                qty = 1;
                productName = data.name;
                price = data.mrp;
                var cart = [];
                if (localStorage.getItem("cart") !== null) {
                    cart = JSON.parse(localStorage.getItem("cart"));
                    localStorage.removeItem("cart", JSON.stringify(cart));
                }
                if (cart.find(data => data.productId == product_id) !== undefined) {
                    cart = cart.map(data => {

                        if (data.productId === product_id) {
                            toastr.options = {
                                "closeButton": true,
                                "progressBar": true,
                                "timeOut": "1500",
                            }
                            toastr.warning('product already added');
                            // data.quantity = data.quantity + 1;
                        }
                        return data;
                    });
                    localStorage.setItem("cart", JSON.stringify(cart));
                } else {
                    cart.push({
                        "productId": product_id,
                        "quantity": qty,
                        "price": price,
                        "product_name": productName,
                        "sub_total": price
                    });
                    localStorage.setItem("cart", JSON.stringify(cart));
                    $("#add_new").click();
                    var total = 0;
                    var quantity = 0;
                    cart.map(data => {
                        total += data.quantity * data.price;
                    });
                    cart.map(data => {
                        quantity += parseFloat(data.quantity);
                    });
                    $('#total_amount').val(total);
                    $('#quantity').val(quantity);
                    $('#total_amount').val(total);
                    $('#sub_amount').val(total);
                    var scheme_amount = parseFloat($('#scheme_amount').val());
                    var total = parseFloat($('#total_amount').val());
                    var total = total - scheme_amount;
                    $('#net_amount').val(total);
                    setProductTable(data);
                }
                var cart = JSON.parse(localStorage.getItem("cart"));
                setSessionCart(cart)
            });
        }

        function productCart(data) {
            product_id = data.id;
            qty = 1;
            productName = data.name;
            price = data.mrp;
            var cart = [];
            if (localStorage.getItem("cart") !== null) {
                cart = JSON.parse(localStorage.getItem("cart"));
                localStorage.removeItem("cart", JSON.stringify(cart));
            }
            if (cart.find(data => data.productId == product_id) !== undefined) {
                cart = cart.map(data => {

                    if (data.productId === product_id) {
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true,
                            "timeOut": "1500",
                        }
                        toastr.warning('product already added');
                        // data.quantity = data.quantity + 1;
                    }
                    return data;
                });
                localStorage.setItem("cart", JSON.stringify(cart));
            } else {
                cart.push({
                    "productId": product_id,
                    "quantity": qty,
                    "price": price,
                    "product_name": productName,
                    "sub_total": price
                });
                localStorage.setItem("cart", JSON.stringify(cart));
                $("#add_new").click();
                var total = 0;
                var quantity = 0;
                cart.map(data => {
                    total += data.quantity * data.price;
                });
                cart.map(data => {
                    quantity += parseFloat(data.quantity);
                });
                $('#total_amount').val(total);
                $('#quantity').val(quantity);
                $('#total_amount').val(total);
                $('#sub_amount').val(total);
                var scheme_amount = parseFloat($('#scheme_amount').val());
                var total = parseFloat($('#total_amount').val());
                var total = total - scheme_amount;
                $('#net_amount').val(total);
                setProductTable(data);
            }
            var cart = JSON.parse(localStorage.getItem("cart"));
            setSessionCart(cart)
        }

        $('#maintable').on('change keyup', '#qty', function() {

            var id = $(this).data('id');
            var qty = $(this).val();
            if (qty > 0) {
                var cart = JSON.parse(localStorage.getItem("cart"));
                cart = cart.map(data => {
                    if (data.productId === id) {
                        data.quantity = qty;
                        data.sub_total = data.price * qty;

                        ($(this).closest('tr').find('.sub_total').text(data.sub_total));
                    }
                    return data;
                });
                localStorage.setItem("cart", JSON.stringify(cart));
                var total = 0;
                var quantity = 0;
                cart.map(data => {
                    total += data.quantity * data.price;
                });
                cart.map(data => {
                    quantity += parseFloat(data.quantity);
                });
                $('#total_amount').val(total);
                $('#sub_amount').val(total);
                $('#quantity').val(quantity);
                var scheme_amount = parseFloat($('#scheme_amount').val());
                var total = parseFloat($('#total_amount').val());
                var total = total - scheme_amount;
                $('#net_amount').val(total);
            } else {
                var cart = JSON.parse(localStorage.getItem("cart"));
                cart = cart.map(data => {
                    if (data.productId === id) {
                        data.quantity = 1;
                        data.sub_total = data.price * 1;
                        ($(this).closest('tr').find('.sub_total').text(data.sub_total));
                    }
                    return data;
                });
                localStorage.setItem("cart", JSON.stringify(cart));
                var total = 0;
                cart.map(data => {
                    total += data.quantity * data.price;
                });
            }
            var cart = JSON.parse(localStorage.getItem("cart"));
            setSessionCart(cart)
        });
        $('#maintable').on('click', '.btnDelete', function() {
            var id = $(this).data('id');
            var cart = JSON.parse(localStorage.getItem("cart"));
            cart = cart.filter(data => data.productId !== id);
            localStorage.setItem("cart", JSON.stringify(cart));
            var cart = JSON.parse(localStorage.getItem("cart"));
            setSessionCart(cart)
            $(this).closest('tr').remove();
        });
        $('#discount').on('change keyup', function() {
            var discount_amount = $(this).val();
            var total = $('#sub_amount').val();
            // var discount_amount = (total * discount)/100;
            var total_amount = total - discount_amount;
            $('#total_amount').val(total_amount);
            var scheme_amount = parseFloat($('#scheme_amount').val());
            var total = parseFloat($('#total_amount').val());
            var total = total - scheme_amount;
            $('#net_amount').val(total);
        });

        $('#shipping_charge').on('change keyup', function() {
            var shipping_charge = parseFloat($('#shipping_charge').val());
            var total = parseFloat($('#sub_amount').val());
            var total_amount = total + shipping_charge;
            $('#total_amount').val(total_amount);
            var scheme_amount = parseFloat($('#scheme_amount').val());
            var total = parseFloat($('#total_amount').val());
            var total = total - scheme_amount;
            $('#net_amount').val(total);
        });

        function setSessionCart(cart) {
            $.ajax({
                url: "/admin/set-session-cart",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "cart": cart
                },
                success: function(data) {

                }
            });
        }
        $('#scheme_amount').on('change', function() {
            var scheme_amount = parseFloat($('#scheme_amount').val());
            var total = parseFloat($('#sub_amount').val());
            var total_amount = total - scheme_amount;
            $('#net_amount').val(total_amount);
        });

        function printInvoice(data) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/admin/print-invoice",
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "data": data
                },
                success: function(html) {
                    var mywindow = window.open('', 'PRINT', 'height=400,width=600');
                    mywindow.document.write('<html><head><title>' + document.title + '</title>');
                    mywindow.document.write('</head><body >');
                    mywindow.document.write(html);
                    mywindow.document.write('</body></html>');
                    mywindow.document.close();
                    mywindow.focus();
                    mywindow.print();
                    // mywindow.close();
                    return true;
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }
    </script>
@endpush
