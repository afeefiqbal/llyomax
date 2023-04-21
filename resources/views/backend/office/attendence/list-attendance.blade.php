@extends('backend.layouts.backend')

@section('content')

@push('styles')
<link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{asset('backend/assets/vendors/DataTables/datatables.min.css')}}" rel="stylesheet" />
<link href="{{asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" />
<style>
    .select2-selection__arrow{
        top: 15px !important ;
    }
    .select2-selection__rendered{
        line-height: 10px !important;
        padding-right: 50px !important;
    }
    </style>
@endpush

<!-- BEGIN: Page heading-->
<div class="page-heading">
    <div class="page-breadcrumb">
        <h1 class="page-title page-title-sep">Attendances</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Office Admin</li>
            <li class="breadcrumb-item">Attendance</li>
        </ol>
    </div>

    @hasanyrole('super-admin|developer-admin|office-administrator')
    <div>
        <a href="attendances/create">
            <button class="btn btn-primary">Add New <i class="la la-plus"></i></button>
        </a>
    </div>
    @endhasanyrole
</div>
<!-- End Page Heading -->

<div>
    <div class="card">
        <div class="card-body">
            <div style="text-align: right;">
                {{-- Branch:
            <select class="select2_demo col-sm-2 form-group" id="branch" name="branch">
                <option value=""></option>
                @foreach ($branches as $branch )
                <option value="{{ $branch->id }}" @if(isset($staff)&&($staff->branch_id == $branch->id))
                    {{ 'selected' }}
                @endif>{{ $branch->branch_id }}-{{ $branch->branch_name }}</option>
                @endforeach
            </select> --}}
            </div>
            <br>
            <div class="table-responsive">
                <table class="table table-bordered w-100 data-table" id="dt-filter">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Branch Name</th>
                            <th>No. of Present Staffs</th>
                            <th>No. of Absent Staffs</th>
                            <th>No. of Late Staffs</th>
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
<script src="{{ asset('backend/assets/vendors/select2/dist/js/select2.full.min.js') }}"></script>
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
                ajax: "attendances",
                columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            width: '10%'
                        },

                        {
                            data: 'branch',       
                            name: 'branch'
                        },
                        {
                            data: 'present',
                            name: 'present'
                        },
                        {
                            data: 'absent',
                            name: 'absent'
                        },
                        {
                            data: 'late',
                            name: 'late',
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

    $(document).ready(function () {
            $(".select2_demo").select2({
                placeholder: "Select an option",
            });
            $('#branch').on('change', function() {
                let branch =   $("#branch :selected").val();

                  var  table = $('.data-table').DataTable({
                    bDestroy: true,
                    processing: true,
                    serverSide: true,

                    header: {
                        'X-CSRF-Token': '{{ csrf_token() }}'
                    },
                    ajax: {
                        url:"attendances",
                        data:{
                            branch: branch,
                        },
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            width: '10%'
                        },
                        {
                            data: 'date',
                            name: 'date'
                        },
                        {
                            data: 'branch',
                            name: 'branch'
                        },
                        {
                            data: 'present',
                            name: 'present'
                        },
                        {
                            data: 'absent',
                            name: 'absent'
                        },
                        {
                            data: 'late',
                            name: 'late',
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

                    let date = $(this).data('id');
                    let branch =   $("#branch :selected").val();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-Token': '{{ csrf_token() }}'
                        }
                    });

                    $.ajax({
                        url: "attendances/delete/"+date,
                        data: {
                            date: date,
                            branch: branch,
                        },
                        method: 'DELETE',
                        success: function(result) {
                            swal(
                            'Deleted!',
                            'Staff has been deleted.',
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
