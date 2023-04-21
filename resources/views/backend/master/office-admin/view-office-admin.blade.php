@extends('backend.layouts.backend')

@section('content')

@push('styles')
<link href="{{asset('backend/assets/vendors/DataTables/datatables.min.css')}}" rel="stylesheet" />
<link href="{{asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" />
<style>
    .center{
        text-align: center;
    }
    .executive{
    border: 1px solid #dee2e6;
    padding: 1.5rem;
    margin-bottom: 5px;
    }
    </style>
@endpush

<!-- BEGIN: Page heading-->
<div class="page-heading">
    <div class="page-breadcrumb">
        <h1 class="page-title page-title-sep">Office Admin</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Master</li>
            <li class="breadcrumb-item">Office Admin</li>
        </ol>
    </div>

    <div>
        <a href="/admin/master/office-admins">
            <button class="btn btn-primary">Back to Office Admin <i class="fas fa-arrow-left"></i></button>
        </a>
    </div>

</div>
<!-- End Page Heading -->

<div>
    <div class="card">
        <div class="card-body">
            <div>
                <ul class="nav nav-tabs nav-top-border nav-tabs-lg">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab7-1"><i class="ti-briefcase nav-tabs-icon"></i>Office Admin Details</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab7-2"><i class="ti-support nav-tabs-icon"></i>Branch Details</a></li>
                </ul>
                <div class="tab-content mt-4">
                    <div class="tab-pane fade show active" id="tab7-1">
                        <table class="table table-borderless center">
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <td><h3>{{ $office->name }}</h3></td>
                                </tr>
                                <tr>
                                    <th scope="row">ID</th>
                                    <td>{{ $office->admin_id }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Name</th>
                                    <td>{{ $office->name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Username</th>
                                    <td>{{ $office->username }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Mobile</th>
                                    <td>{{ $office->phone }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Email</th>
                                    <td>{{ $office->email }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Address</th>
                                    <td>{{ $office->address }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Status</th>
                                    <td>@if($office->status == 1)
                                        <button class="btn btn-success">Active</button>
                                        @else
                                        <button class="btn btn-danger">Inactive</button>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="tab7-2">
                        <div class="center">
                        @if( $branch->getFirstMediaUrl('branch_images'))
                        <img style="border-radius: 50%;" src="{{ $branch->getFirstMediaUrl('branch_images') }}" height="150px" width="400px">
                        @else
                        <img style="border-radius: 50%;" src="{{ asset('backend/assets/img/branch.jpg') }}" height="150px" width="400px">
                        @endif
                        <div class="col">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th scope="row">Branch ID</th>
                                        <td>{{ $branch->branch_id }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Branch Name</th>
                                        <td>{{ $branch->branch_name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Mobile</th>
                                        <td>{{ $branch->mobile }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">District</th>
                                        <td>{{ $branch->district }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Address</th>
                                        <td>{{ $branch->address }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Place</th>
                                        <td>{{ $branch->place }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Status</th>
                                        <td>@if($branch->status == 1)
                                            <button class="btn btn-success">Active</button>
                                            @else
                                            <button class="btn btn-danger">Inactive</button>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

@endpush
