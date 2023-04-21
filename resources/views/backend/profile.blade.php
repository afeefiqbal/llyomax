@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {
                packages: ['corechart']
            });
        </script>
        <style>
            .select2-selection__arrow {
                top: 15px !important;
            }

            .select2-selection__rendered {
                line-height: 10px !important;
            }

        </style>
    @endpush
    <div class="page-content fade-in-up">
        <!-- BEGIN: Page heading-->
        <!-- BEGIN: Page content-->
        <div>
            <div class="bg-white pt-4 px-5" style="margin: -30px -30px 40px">
                <div class="flexbox mb-4 mt-3">
                    <div class="media"><span class="position-relative d-inline-block mr-4"><img class="rounded-circle" src="{{asset('backend/assets/img/users/admin-image.png')}}" alt="image" width="100" /><span class="badge-point badge-success avatar-badge" style="bottom: 5px;right: 14px;height: 14px;width: 14px;"></span></span>
                        <div class="media-body">
                            <div class="h4">{{ auth()->user()->name }}</div>
                            <div class="text-muted">{{ auth()->user()->roles->pluck('name')->first() }}</div>
                        </div>
                    </div>
                </div>
                <ul class="nav line-tabs m-0">

                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab-3"><i class="ti-settings nav-tabs-icon"></i>Settings</a></li>
                </ul>
            </div>
            <div class="tab-content mt-5">

                <div class="tab-pane fade  show active" id="tab-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="box-title">Settings</h4>
                            <form action="javascript:;">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="md-form"><input class="md-form-control" type="text" value="{{$user->username}}"><label>First Name</label></div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="md-form"><input class="md-form-control" type="text" value=""><label>Last Name</label></div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="md-form"><input class="md-form-control" type="text" value=""><label>User name</label></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="input-group-icon input-group-icon-right mb-4">
                                            <div class="md-form mb-4"><input class="md-form-control m-0" type="text" value="New York, USA 228 Park Ave Str."><label>Address</label><span class="input-icon input-icon-right"><i class="material-icons">place</i></span></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group-icon input-group-icon-right mb-4">
                                            <div class="md-form mb-4"><input class="md-form-control m-0" type="text" value="Web Designer"><label>Position</label><span class="input-icon input-icon-right"><i class="material-icons">work</i></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="input-group-icon input-group-icon-right mb-4">
                                            <div class="md-form mb-4"><input class="md-form-control m-0" type="text" value="+1-202-555-0134"><label>Phone</label><span class="input-icon input-icon-right"><i class="material-icons">phone</i></span></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group-icon input-group-icon-right mb-4">
                                            <div class="md-form mb-4"><input class="md-form-control m-0" type="text" value="johndue@gmail.com"><label>Email</label><span class="input-icon input-icon-right"><i class="material-icons">email</i></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-5"><label>Gender</label>
                                    <div><label class="radio radio-inline radio-primary"><input type="radio" name="a" checked=""><span>Male</span></label><label class="radio radio-inline radio-primary"><input type="radio" name="a"><span>Female</span></label></div>
                                </div>
                                <div class="form-group"><button class="btn btn-primary mr-2 waves-effect waves-light" type="submit">SUBMIT</button><button class="btn btn-light waves-effect" type="reset">CLEAR</button></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- END: Page content-->
    </div>
    @endsection
    @push('scripts')
    @endpush
