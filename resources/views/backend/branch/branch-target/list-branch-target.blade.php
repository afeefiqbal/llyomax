@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <link href="{{ asset('backend/assets/vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
    @endpush
    <!-- BEGIN: Page heading-->
    <div class="page-heading">
        <div class="page-breadcrumb">
            <h1 class="page-title page-title-sep">{{ request()->is('*/branch/scheme-targets*') ? 'Scheme Targets' : 'Branch Targets' }}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Branches</li>

                <li class="breadcrumb-item">{{ request()->is('*/branch/scheme-targets*') ? 'Scheme Targets' : 'Branch Targets' }}</li>
            </ol>
        </div>
        <div>
            <a href="{{ request()->is('*/branch/scheme-targets*') ? 'scheme-targets/create' : 'branch-targets/create' }} ">
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
                                <th {{ request()->is('*/branch/branch-targets*') ? '' : 'hidden' }}>Branch</th>
                                <th {{ request()->is('*/branch/scheme-targets*') ? '' : 'hidden' }} >Scheme</th>

                                <th>Target per month</th>
                                <th>Target per day</th>
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
                ajax: "{{ request()->is('*/branch/branch-targets*') ? 'branch-targets' : 'scheme-targets' }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        width: '10%'
                    },
                    @if (request()->is('*/branch/branch-targets*'))
                    {
                        data: 'branch_id',
                        name: 'branch_id'
                    },

                    @else
                    {
                        data: 'branch_id',
                        name: 'branch_id',
                        visible: false
                    },
                    @endif

                    @if (request()->is('*/branch/scheme-targets*'))
                    {
                        data: 'scheme_id',
                        name: 'scheme_id'
                    },

                    @else
                    {
                        data: 'scheme_id',
                        name: 'scheme_id',
                        visible: false
                    },
                    @endif
                    {
                        data: 'target_per_month',
                        name: 'target_per_month'
                    },
                    {
                        data: 'target_per_day',
                        name: 'target_per_day'
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
                        url: "branch-targets/" + id,
                        method: 'DELETE',
                        success: function(result) {
                            swal(
                                'Deleted!',
                                'Branch Target has been deleted.',
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
