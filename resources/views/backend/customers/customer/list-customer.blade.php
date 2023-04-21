@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <link href="{{ asset('backend/assets/vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
    @endpush
    <!-- BEGIN: Page heading-->
    <div class="page-heading">
        <div class="page-breadcrumb">
            <h1 class="page-title page-title-sep"> customers</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Branch</li>
                <li class="breadcrumb-item">customers</li>
            </ol>
        </div>
        <div>

            @hasanyrole('super-admin|developer-admin|branch-manager|marketing-executive|collection-executive')
                <a href="customers/create">
                    <button class="btn btn-primary">Add New <i class="la la-plus"></i></button>
                </a>
            @endhasanyrole
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
                                <th>Customer ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Joining Date</th>
                                <th>Branch</th>
                                @hasanyrole('office-administrator|collection-executive')
                                    <th>Show Details</th>
                                @endhasanyrole
                                @hasanyrole('super-admin|developer-admin|branch-manager|collection-executive|marketing-executive')

                                <th>Show Scheme Details</th>
                                <th class="no-sort">Action</th>
                                @endhasanyrole

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
                dom: 'Bflrtip',
                buttons: [
                    'copy', 'excel', 'pdf'

                ],
                header: {
                    'X-CSRF-Token': '{{ csrf_token() }}'
                },
                ajax: "customers",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        width: '10%'
                    },
                    {
                        data: 'customer_id',
                        name: 'customer_id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'joining_date',
                        name: 'joining_date',
                    },
                    {
                        data: 'branch_id',
                        name: 'branch_id',
                    },

                    {
                        data: 'show_more',
                        name: 'show_more',
                    },
                    @hasanyrole('super-admin|developer-admin|branch-manager|marketing-executive|collection-executive')
                        {
                            data: 'action',
                            name: 'action',
                            width: '15%',
                            orderable: false,
                            searchable: false
                        },
                    @endhasanyrole
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
                        url: "customers/" + id,
                        method: 'DELETE',
                        success: function(result) {
                            swal(
                                'Deleted!',
                                'customers has been deleted.',
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
