@extends('backend.layouts.backend')

@section('content')

@push('styles')
<link href="{{asset('backend/assets/vendors/DataTables/datatables.min.css')}}" rel="stylesheet" />
<link href="{{asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" />
@endpush

<!-- BEGIN: Page heading-->
<div class="page-heading">
    <div class="page-breadcrumb">
        <h1 class="page-title page-title-sep">Roles</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Settings</li>
            <li class="breadcrumb-item">Role</li>
        </ol>
    </div>

    <div>
        <a href="roles/create">
            <button class="btn btn-primary">Add New <i class="la la-plus"></i></button>
        </a>
    </div>

</div>
<!-- End Page Heading -->

<div>
    <div class="card">
        <div class="card-body">
            <!-- <h5 class="box-title">Filter & custom search field</h5> -->
            <!-- <div class="flexbox mb-4">
                <div class="flexbox"><label class="mb-0 mr-2">Type:</label>
                    <select
                        class="selectpicker form-control show-tick" id="type-filter" title="Please select"
                        data-width="150px">
                        <option value="">All</option>
                        <option>Shipped</option>
                        <option>Completed</option>
                        <option>Pending</option>
                        <option>Canceled</option>
                    </select>
                </div>

                <div class="input-group-icon input-group-icon-right mr-3">
                    <span class="input-icon input-icon-right font-16">
                        <i class="ti-search"></i>
                    </span>
                    <input class="form-control form-control-rounded"
                     id="key-search" type="text" placeholder="Search ...">
                </div>

            </div> -->

            <div class="table-responsive">
                <table class="table table-bordered w-100 data-table" id="dt-filter">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Role</th>
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
<script src="{{asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>

    <script type="text/javascript">
        $(function () {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                header: {
                    'X-CSRF-Token': '{{ csrf_token() }}'
                },
                ajax: "roles",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        width: '10%'
                    },
                    {
                        data: 'name',
                        name: 'name'
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

        $(document).on('click', '.delete', function () {
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
                        url: "roles/"+id,
                        method: 'DELETE',
                        success: function(result) {
                            swal(
                                'Deleted!',
                                'Role has been deleted.',
                                'success'
                            );
                            $('.data-table').DataTable().ajax.reload();
                            // $('#DeleteArticleModal').hide();
                        },
                        error: function(err) {
                            swal(
                                'Error!',
                                'You can\'t delete roles in use!',
                                'error'
                            );
                        }
                    });


                }
            });

        });


    </script>
@endpush
