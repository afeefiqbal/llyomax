@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <link href="{{ asset('backend/assets/vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush
    <div class="page-heading">
        <div class="page-breadcrumb">
            <h1 class="page-title page-title-sep"><i class="ft-settings mr-3 font-18 text-muted"></i>Settings</h1>
        </div>
        <div>
        </div>
    </div>
    <div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-9">
                        <form action="/admin/settings" method="POST" id="settingsForm">
                            @csrf
                            <div class="col-sm-8 form-group mb-12">
                                <label>Company Name : <span class="text-danger">*</span></label>
                                @php
                                    $title = \App\Models\Settings::where('key', 'title')->first()->value;
                                @endphp
                                <input class="form-control" type="text" id="title" placeholder="Company Name" value="{{$title}}" name="title">
                            </div>
                            <div class="col-sm-8 form-group mb-12">
                                @php
                                    $customer_care_number = \App\Models\Settings::where('key', 'customer_care_number')->first()->value;
                                @endphp
                                <label>Customer care number : <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="customer_care_number" placeholder="Customer care number" value="{{$customer_care_number}}" name="customer_care_number">
                            </div>
                            <div class="col-sm-8 form-group mb-12">
                                @php
                                $website_url = \App\Models\Settings::where('key', 'website_url')->first()->value;
                            @endphp
                                <label>Website Url : <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="website_url" placeholder="Website Url" value="{{$website_url}}" name="website_url">
                            </div>
                            {{-- <div class="col-sm-8 form-group mb-12">
                                <label>App Url : <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="app_url" placeholder="App Url" value="" name="app_url">
                            </div> --}}
                            <div class="col-sm-12 form-group mb-12">
                                <div class="form-group">
                                    <button class="btn btn-primary mr-2" id="submitForm" type="submit">Submit</button>
                                    <button class="btn btn-light" type="reset">Clear</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-2"></div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('backend/assets/vendors/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/jquery-validation/dist/jquery.validate.min.js')}}"></script><!-- CORE SCRIPTS-->
    @if (session('success'))
        <script>
            $(document).ready(function() {
                swal({
                    title: "Success",
                    text: "{{ session('success') }}",
                    type: "success",
                    confirmButtonText: "OK"
                });
            });
        </script>

    @else

    @endif
@endpush
