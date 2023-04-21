@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <link href="{{ asset('backend/assets/vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
    @endpush
    <!-- BEGIN: Page heading-->
    <div class="page-heading">
        <div class="page-breadcrumb">
            <h1 class="page-title page-title-sep">Executive Transfer Amount Details</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Transfer Amount Details</li>
                <li class="breadcrumb-item">Transfer Amount Details</li>
            </ol>
        </div>
        @hasanyrole('collection-executive|marketing-executive')
        <div>
            <a href="amount-transfer-executive/create">
                <button class="btn btn-primary">Add New <i class="la la-plus"></i></button>
            </a>
        </div>
        @endhasanyrole
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label>Date<span class="text-danger">*</span></label>
                        <input type="date" name="date" id="date" class="col-sm-12 form-group"
                            placeholder=" Date" />
                    </div>
                    <div class="col-md-3">
                        <br>
                        <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                        <button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered w-100 data-table" id="dt-filter">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Branch</th>
                                <th>Executive</th>
                                <th>Transfered Amount</th>
                                <th>Transfer Date</th>
                                <th>Transfer Time</th>
                                <th>Transfered Type</th>
                                <th>Status</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('backend/assets/vendors/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script type="text/javascript">
      $(document).ready(function() {

            load_data($('#date').val());

            function load_data(date = '') {
                $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print',
                        {}
                    ],
                    ajax: {
                        url: "/admin/executive/amount-transfer-executive",
                        data: {
                            date: $('#date').val(),
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            width: '10%'
                        },
                        {
                            data: 'branch_id',
                            name: 'branch_id'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'transfer_amount',
                            name: 'transfer_amount'
                        },
                        {
                            data: 'date',
                            name: 'date'
                        },
                        {
                            data: 'transfer_time',
                            name: 'transfer_time'
                        },
                        {
                            data: 'transfer_type',
                            name: 'transfer_type'
                        },
                        {
                            data : 'status',
                            name : 'status'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            width: '15%',
                            orderable: false,
                            searchable: false
                        },
                    ]
                });
            }
            $('#filter').click(function() {
                var date = $('#date').val();
                if (date != '') {
                    $('.data-table').DataTable().destroy();
                    load_data(date);
                } else {
                    alert('All field is required');
                }
            });
            $('#refresh').click(function() {
                document.getElementById("date").value = "";
                $('.data-table').DataTable().destroy();
                load_data();
            });
        });
         $(document).on('click', '.delete', function() {
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    let id = $(this).data('id');
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-Token': '{{ csrf_token() }}'
                        }
                    });
                    $.ajax({
                        url: "/admin/branch/amount-transfer/" + id,
                        method: 'DELETE',
                        success: function(result) {
                            swal(
                                'Deleted!',
                                'amount transfer has been deleted.',
                                'success'
                            );
                            $('.data-table').DataTable().ajax.reload();
                            // $('#DeleteArticleModal').hide();
                        }
                    });
                }
            });
        });

    </script>
@endpush
