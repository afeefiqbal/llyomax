@extends('backend.layouts.backend')

@section('content')

@push('styles')
<link href="{{asset('backend/assets/vendors/DataTables/datatables.min.css')}}" rel="stylesheet" />
<link href="{{asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" />
@endpush

<!-- BEGIN: Page heading-->
<div class="page-heading">
    <div class="page-breadcrumb">
        <h1 class="page-title page-title-sep">Leaves Executives</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Executives</li>
            <li class="breadcrumb-item">Leaves Executives</li>
        </ol>
    </div>
    @hasanyrole('super-admin|developer-admin|collection-executive|marketing-executive')
    <div>
        <a href="leave-form/create">
            <button class="btn btn-primary">Add New <i class="la la-plus"></i></button>
        </a>
    </div>
    @endhasanyrole
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
                            <th>ID</th>
                            <th>Branch</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Manager</th>
                            <th>Date</th>
                            <th>Reason</th>
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

<script src="{{asset('backend/assets/vendors/DataTables/datatables.min.js') }}"></script>
{{-- <script src="{{asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js')}}"></script> --}}
<script src="http://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $(function () {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                header: {
                    'X-CSRF-Token': '{{ csrf_token() }}'
                },
                ajax: "leave-form",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        width: '10%'
                    },
                    {
                        data: 'executive',
                        name: 'id'
                    },
                    {
                        data: 'branch',
                        name: 'name'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'phone',
                        name: 'mobile'
                    },
                    {
                        data: 'manager',
                        name: 'manager',
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'reason',
                        name: 'reason',
                    },
                    {
                        data: 'status',
                        name: 'status',
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
        $(document).on('click', '.status', function () {
            swal.fire({
            title: 'Leave Approve/Reject',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            showDenyButton: true,
            denyButtonText: `Reject`,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: 'grey',
            confirmButtonText: 'Approve'
            }).then((result) => {
                if(!result.isDismissed){
                    if (result.isConfirmed) {
                    var approve = 'Accepted'
                }else if(result.isDenied){
                    var approve = 'Rejected'
                }

                    let id = $(this).data('id');

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-Token': '{{ csrf_token() }}'
                        }
                    });

                    $.ajax({
                        url: "leave-form-approve/"+id,
                        method: 'post',
                        data: {
                            leave: approve
                        },
                        success: function(result) {
                            swal.fire(
                            'Updated!',
                            'Leave request has been Changed',
                            'success'
                            );
                            $('.data-table').DataTable().ajax.reload();
                            // $('#DeleteArticleModal').hide();
                        }
                    });
                }
            });

        });
        $(document).on('click', '.delete', function () {
            swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
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
                        url: "leave-form/"+id,
                        method: 'DELETE',
                        success: function(result) {
                            swal.fire(
                            'Deleted!',
                            'Leave request has been deleted.',
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
