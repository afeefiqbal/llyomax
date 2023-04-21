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
            <h1 class="page-title page-title-sep">Transfer Amount Details</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Transfer Amount Details</li>
                <li class="breadcrumb-item">Transfer Amount Details</li>
            </ol>
        </div>
        <div>
            <a href="/admin/branch/amount-transfer">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i> Transfer Amount Details List</button>
            </a>
        </div>
    </div>

    <!-- End Page Heading -->
    <div>
        <div class="card">
            <div class="card-body">

                <div class="row">

                    <div class="col-sm-3 form-group mb-4">
                        <label>Branch Name</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        
                        <label>{{ $amountTransferDetails->branch->branch_id }}-{{ strtoupper($amountTransferDetails->branch->branch_name) }}</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>Date</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>{{ $amountTransferDetails->date }}</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>Time</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>{{ date('h:i a', strtotime($amountTransferDetails->transfer_time)) }}</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>Transfer Amount</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>{{ $amountTransferDetails->transfer_amount }}</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>Transfer type</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>{{ $amountTransferDetails->transfer_type == 1 ? 'By Hand' : 'Bank' }}</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>Status</label>
                    </div>
                    <div class="col-sm-3 form-group mb-4">
                        <label>
                            @if ($amountTransferDetails->status == 1)
                                <span class="badge badge-success">Approved</span>
                            @else
                                <span id="statusChanger" data-id="{{$amountTransferDetails->id}}" data-status="1" class="badge badge-warning" style="color: white"> Pending</span>
                            @endif
                        </label>

                        </label>
                    </div>
                    <div class="form-group">
                        <img class="fit-picture" src="{{ $amountTransferDetails->getFirstMediaUrl('receipt_images') }}"
                            alt="">
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
        $('#statusChanger').click(function() {
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            var url = "/admin/status-changer";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    id: id,
                    status: status,
                },
                success: function(data) {
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true,
                            "timeOut": "1500",
                        }
                        toastr.success(data.success);
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    },
                    error: function(err) {
                        if (err.responseJSON['errors']) {
                            let error = err.responseJSON['errors'];
                            var msg = '';
                            $.each(error, (i, j) => {
                                $('.' + i).text(j);
                                msg += j + '<br/>';
                            });
                            let errKeys = Object.keys(err.responseJSON['errors']);
                            errKeys.map((item) => {
                                $('[name=' + item + ']').addClass('is-invalid');
                            });
                        }
                    }

            });
        });
    </script>
@endpush
