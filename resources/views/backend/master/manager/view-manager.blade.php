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
        <h1 class="page-title page-title-sep">Manager</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Master</li>
            <li class="breadcrumb-item">Managers</li>
        </ol>
    </div>

    <div>
        <a href="/admin/master/managers">
            <button class="btn btn-primary">Back to Managers <i class="fas fa-arrow-left"></i></button>
        </a>
    </div>

</div>
<!-- End Page Heading -->

<div>
    <div class="card">
        <div class="card-body">
            <div>
                <ul class="nav nav-tabs nav-top-border nav-tabs-lg">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab7-1"><i class="ti-briefcase nav-tabs-icon"></i>Manager Details</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab7-2"><i class="ti-support nav-tabs-icon"></i>Branch Details</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab7-3"><i class="ti-user nav-tabs-icon"></i>Executives</a></li>
                </ul>
                <div class="tab-content mt-4">
                    <div class="tab-pane fade show active" id="tab7-1">
                        <div class="center">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th>Manager Name</th>
                                    <th><h3>{{ $manager->name }}</h3></th>
                                </tr>
                                <tr>
                                    <th scope="row">Manager ID</th>
                                    <td>{{ $manager->manager_id }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Username</th>
                                    <td>{{ $manager->username }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Mobile</th>
                                    <td>{{ $manager->mobile }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Email</th>
                                    <td>{{ $manager->email }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Password</th>
                                    <td>{{ $manager->password }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Status</th>
                                    <td>@if($manager->status == 1)
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
                    <div class="tab-pane fade" id="tab7-2">
                        <div class="center">
                            @if (isset($branch))
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
                        @endif
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab7-3">
                        <div class="row">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Executive Name</th>
                                        <th scope="row">ID</th>
                                        <th scope="row">Executive Type</th>
                                        <th scope="row">Username</th>
                                        <th scope="row">Mobile</th>
                                        <th scope="row">Email</th>
                                        <th scope="row">Place</th>
                                        <th scope="row">Collection Area</th>
                                        <th scope="row">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($executives as $executive )
                                <tr>
                                    <td>{{ $executive->name }}</td>
                                    <td>{{ $executive->executive_id }}</td>
                                    <td>@if($executive->executive_type == 1)
                                        Marketing Executive
                                        @else
                                        Collection Executive
                                        @endif
                                    </td>
                                    <td>{{ $executive->username }}</td>
                                    <td>{{ $executive->phone }}</td>
                                    <td>{{ $executive->email }}</td>
                                    <td>{{ $executive->place }}</td>
                                    <td>@php
                                        $area = App\Models\Master\Area::find($executive->collection_area_id)->name;
                                        echo $area;
                                    @endphp</td>
                                    <td>@if($executive->status == 1)
                                        <button class="btn btn-success">Active</button>
                                        @else
                                        <button class="btn btn-danger">Inactive</button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
