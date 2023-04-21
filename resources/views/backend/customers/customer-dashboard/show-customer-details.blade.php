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
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="col-lg-12">
            <div class="card  card-fullheight">
                <div class="card-body">
                    <div>
                        <ul class="nav nav-tabs nav-top-border nav-tabs-lg">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab-info"><i
                                        class="ti-receipt nav-tabs-icon"></i>Customer Details</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-items"><i
                                        class="ti-shopping-cart nav-tabs-icon"></i>Scheme Details</a></li>
                        </ul>
                        <div class="tab-content mt-4">
                            <div class="tab-pane fade  show active " id="tab-info">
                                <div class="row">
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
                                                    <td>{{ $scheme->scheme_id }}</td>
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
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
        < script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity = "sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin = "anonymous" >
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
            $('table.details').append(data);
        }
    </script>
@endpush
