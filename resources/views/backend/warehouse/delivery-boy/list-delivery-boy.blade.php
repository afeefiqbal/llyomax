@extends('backend.layouts.backend')

@section('content')

@push('styles')
<link href="{{asset('backend/assets/vendors/DataTables/datatables.min.css')}}" rel="stylesheet" />
<link href="{{asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" />
@endpush

<!-- BEGIN: Page heading-->
<div class="page-heading">
    <div class="page-breadcrumb">
        <h1 class="page-title page-title-sep"> Delivery Boy</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Master</li>
            <li class="breadcrumb-item">Delivery Boy</li>
        </ol>
    </div>

    <div>
        <a href="delivery-executives/create">
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
                            <th>Delivery Boy ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Place</th>
                            <th>Address</th>
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
                ajax: "delivery-executives",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        width: '10%'
                    },
                    {
                        data: 'delivery_boy_id',
                        name: 'delivery_boy_id'
                    },

                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data : 'phone'
                    },
                    {
                        data: 'email',

                    },
                    {
                        data : 'place'
                    },
                    {

                        data : 'address'
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
                        url: "delivery-executives/"+id,
                        method: 'DELETE',
                        success: function(result) {
                            swal(
                            'Deleted!',
                            'Area has been deleted.',
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
