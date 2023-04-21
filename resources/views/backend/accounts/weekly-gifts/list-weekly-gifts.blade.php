@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <link href="{{ asset('backend/assets/vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
    @endpush
    <!-- BEGIN: Page heading-->
    <div class="page-heading">
        <div class="page-breadcrumb">
            <h1 class="page-title page-title-sep">Weekly Gifts</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Accounts</li>
                <li class="breadcrumb-item">Weekly Gifts</li>
            </ol>
        </div>
        <div>
            <a href="weekly-gifts/create">
                <button class="btn btn-primary">Add New <i class="la la-plus"></i></button>
            </a>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered w-100 data-table" id="dt-filter">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Scheme</th>
                                <th>Customer</th>
                                <th>Scheme Week</th>
                                <th>Given by</th>
                                <th>Branch  </th>
                                <th>Gift Items  </th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Bill</th>
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
        $(function() {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                header: {
                    'X-CSRF-Token': '{{ csrf_token() }}'
                },
                ajax: "weekly-gifts",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        width: '10%'
                    },
                    {
                        data: 'scheme_id',
                        name: 'scheme_id'
                    },

                    {
                        data: 'customer_id',
                        name: 'customer_id'
                    },
                    {
                        data : 'week',
                        name : 'week'
                    },
                    {
                        name : 'given_by',
                        data : 'given_by',
                    },
                    {
                        name : 'branch_id',
                        data : 'branch_id',
                    },
                    {
                        name : 'gift_items',
                        data : 'gift_items',
                    },
                    {
                        name : 'date',
                        data: 'date',
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },

                    {
                        data: 'bill',
                        name: 'bill'
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
                        url: "weekly-gifts/" + id,
                        method: 'DELETE',
                        success: function(result) {
                            swal(
                                'Deleted!',
                                'weekly-gifts has been deleted.',
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
