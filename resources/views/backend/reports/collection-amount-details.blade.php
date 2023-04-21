@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <link href="{{ asset('backend/assets/vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
    @endpush
    <!-- BEGIN: Page heading-->
    <div class="page-heading">
        <div class="page-breadcrumb">
            <h1 class="page-title page-title-sep">Collection Amount Report By Branch</h1>
            <ol class="breadcrumb">
            </ol>
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
                                <th>Branch Name</th>
                                <th>Total Amount</th>
                                <th>Transfered Amount</th>
                                <th>Balance Amount</th>
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
                dom: 'Bfrtip',
                    buttons: [
                        'excel', 'pdf', 'print',
                       
                    ],
                header: {
                    'X-CSRF-Token': '{{ csrf_token() }}'
                },
                ajax: "collection-amount",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        width: '10%'
                    },
                    {
                        data: 'branch_id',
                        name: 'branch_id',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'transfered_amount',
                        name: 'transfered_amount',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'pending_transfered_amount',
                        name: 'pending_transfered_amount',
                        orderable: true,
                        searchable: true
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
   
    </script>
@endpush
