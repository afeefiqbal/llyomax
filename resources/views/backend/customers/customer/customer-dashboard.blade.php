<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer dashboard</title>
    <link rel="stylesheet" href="{{ asset('backend/assets/css/main.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="cntnt">
    <div class="nav">
        <div class="container">
            <div class="profile-menu">
                <ul>
                    <li class="welmc-membr">Hi,{{ $customer->name }}</li>
                    <li class="logout-membr">
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <span onclick="$('#logout-form').submit()" class="btn-icon">
                        <i class="fa-solid fa-arrow-right-from-bracket" ></i> Logout</li></span>
                        </form>

                </ul>
            </div>
        </div>

    </div>
    <div class="container-fluid">
        <div class="container p-0">
            <div class="tab-main">
                <div class="tabs">
                    <input type="radio" name="tab" id="tab1" checked="checked">
                    <label for="tab1"><i class="fa-regular fa-user"></i> Customers Detail</label>
                    <input type="radio" name="tab" id="tab2">
                    <label for="tab2"><i class="fa-solid fa-circle-info"></i> Scheme Details</label>
                    <input type="radio" name="tab" id="tab3">
                    <div class="tab-content-wrapper">
                        <div id="tab-content-1" class="tab-content">
                            <div class="cust-det-bx">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="customer-det-hd">
                                            <div class="row">
                                                <div class="col-12 col-md-2">
                                                    <div class="profie-img">
                                                        <img src="{{ asset('backend/assets/img/profile-img.png') }}"
                                                            alt="">
                                                    </div>

                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <h5>Customer ID: # {{ $customer->customer_id }}</h5>
                                                </div>
                                                <!-- <div class="col-6 col-md-5">
                                                    <span></span>
                                                </div> -->
                                            </div>
                                        </div>
                                        <div class="customer-det">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <h5>Name</h5>
                                                </div>
                                                <div class="col-md-6">
                                                    <span>{{ $customer->name }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="customer-det">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <h5>Joined Date</h5>
                                                </div>
                                                <div class="col-md-6">
                                                    <span>{{ date('d-M-Y h:i A', strtotime($customer->created_at)) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="customer-det">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <h5>Email</h5>
                                                </div>
                                                <div class="col-md-6">
                                                    <span>{{ $customer->email }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="customer-det">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <h5>Phone</h5>
                                                </div>
                                                <div class="col-md-4">
                                                    <span>{{ $customer->phone }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="customer-add-hd">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5>Address Details</h5>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="customer-det-add">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <h5>Address</h5>
                                                </div>
                                                <div class="col-md-6">
                                                    <span>
                                                        {{ $customer->address }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="customer-det-add">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <h5>Place</h5>
                                                </div>
                                                <div class="col-md-6">
                                                    <span>
                                                        {{ $customer->place }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="customer-det-add">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <h5>City</h5>
                                                </div>
                                                <div class="col-md-6">
                                                    <span>
                                                        {{ $customer->city }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="tab-content-2" class="tab-content">

                            <table>

                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Scheme ID</th>
                                        <th scope="col">Scheme Name</th>
                                        <th scope="col">Scheme Day</th>
                                        <th scope="col">Total Amount to Pay</th>
                                        <th scope="col">Total Paid</th>
                                        <th scope="col">Advance amount</th>
                                        <th scope="col">Pending  amount</th>
                                        <th scope="col">Amount to complete</th>
                                        <th scope="col">Collection Day</th>
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

                                  @php
                                          $scheme = \App\Models\Scheme::where('id', $customerScheme->scheme_id)->first();
                                          @endphp
                                    <tr>
                                        <td>{{ $n++ }}</td>
                                        <td data-label="Scheme ID">{{ $scheme->scheme_id }}</td>
                                        <td data-label="Scheme Name">{{ $scheme->name }}</td>
                                        <td data-label="Scheme Collection Day">{{ $scheme->scheme_collection_day }}</td>
                                        <td data-label="Total Amount to Pay">{{ $scheme->total_amount }}</td>
                                        <td data-label="Total Paid">{{ $customerScheme->total_amount }}</td>
                                        <td data-label="Advance amount">{{ $customerScheme->advance_amount }}</td>
                                        <td data-label="Pending Collection amount">{{ $customerScheme->pending_amount }}</td>
                                        @php
                                        $due = $scheme->total_amount - $customerScheme->total_amount;
                                        if ($customerScheme->status == 0) {
                                            $status = "Pending";
                                        } elseif ($customerScheme->status == 1) {
                                            $status = "Active";
                                        } elseif ($customerScheme->status == 2) {
                                            $status = "Completed";
                                        } elseif ($customerScheme->status == 3) {
                                            $status = "Lucky Winner";
                                        } elseif ($customerScheme->status == 4) {
                                            $status = "Stop";
                                        }
                                    @endphp
                                        <td data-label="Amount need to complete">{{ $due }}</td>
                                        <td data-label="Customer Collection Day">{{ $customerScheme->collection_day }}</td>
                                        <td data-label="Joined Date">{{ $customerScheme->joining_date }}</td>
                                        <td data-label="Status">{{ $status }}</td>
                                        <td data-label="Action">
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
                                    @hasanyrole('super-admin|developer-admin|marketing-executive|collection-executive')
                                    <div class="form-group">
                                        <a href="/admin/customer/scheme-register/{{ $customer->id }}"> <button
                                                class="btn btn-light" type="reset">Scheme Register</button></a>
                                    </div>
                                    @endhasanyrole


                                </tbody>
                            </table>
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




    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        < script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity = "sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin = "anonymous" >
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    </script>
    </script>
    <script>
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
            $('table.details').empty();
            $('table.details').append(data);
        }
    </script>



</html>
