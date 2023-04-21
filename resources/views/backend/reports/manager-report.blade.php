@extends('backend.layouts.backend')

@section('content')

    @push('styles')
    <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />


    @endpush

    <!-- BEGIN: Page heading-->
    <div class="page-heading">
        <div class="page-breadcrumb">
            <h1 class="page-title page-title-sep">Managers</h1>
            <ol class="breadcrumb">
                {{-- <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li> --}}
                {{-- <li class="breadcrumb-item">Executives</li>
                <li class="breadcrumb-item">Executives</li> --}}
            </ol>
        </div>
        <div>
        </div>
     <div>
         <label for="">Select Designation</label>
        <select name="manager" id="manager" class="form-control designation">
            <option value="Marketing manager">Marketing manager</option>
            <option value="cm">Collection manager</option>
            <option value="bm">Branch manager</option>
        </select>
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
                                <th>ID</th>
                                <th>Manager type</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Branch</th>
                                <th>Joined Date</th>
                                <th>Status</th>
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script type="text/javascript">
            $('.designation').on('change', function() {

            });
        $(function() {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                dom: 'Bfrltip',
                    buttons: [
                        'excel', 'pdf', 'print',

                    ],
                header: {
                    'X-CSRF-Token': '{{ csrf_token() }}'
                },
                ajax: "manager-reports",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        width: '10%'
                    },
                    {
                        data: 'manager_id',
                        name: 'manager_id',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'mobile',
                        name: 'mobile'
                    },
                    {
                        data: 'branch',
                        name: 'branch',
                    },

                    {
                        data : 'joined_date',
                        name : 'joined_date',
                    },
                    {
                        data : 'status',
                        name : 'status',
                    },

                ]
            });
            $('.designation').on('change', function(){
                table.search(this.value).draw();
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
                        url: "collection-executive/" + id,
                        method: 'DELETE',
                        success: function(result) {
                            swal(
                                'Deleted!',
                                'Executive has been deleted.',
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
