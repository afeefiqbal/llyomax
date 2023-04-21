@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
        <style>
            .select2-container--default .select2-selection--single .select2-selection__arrow b {
                border-color: #888 transparent transparent transparent;
                border-style: solid;
                border-width: 5px 4px 0 4px;
                height: 0;
                left: 50%;
                margin-left: -10px;
                margin-top: 10px;
                position: absolute;
                top: 50%;
                width: 0;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #444;
                line-height: 9px;
            }

        </style>
    @endpush
    <!-- BEGIN: Page heading-->
    <div class="page-heading">
        <div class="page-breadcrumb">
            <h1 class="page-title page-title-sep">
                @if (isset($executive))
                    Edit Executive
                @else
                    Create Executive
                @endif
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Executives</li>
                <li class="breadcrumb-item">
                    @if (isset($executive))
                        Edit Executive
                    @else
                        Create Executive
                    @endif
                </li>
            </ol>
        </div>
        <div>
            <a href="/admin/master/executives">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i>Executives List</button>
            </a>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="col-lg-12">
            <div class="card card-fullheight">
                <div class="card-body">
                    @if (isset($executive))
                        <form action="/admin/master/executives/{{ $executive->id }}" method="PATCH" id="manager-form">
                            @method('PATCH')
                        @else
                            <form action="/admin/master/executives" method="POST" id="manager-form">
                    @endif
                    @csrf
                    <div class="row">
                        <div class="col-sm-6 form-group mb-4">
                            <label>Executive Type<span class="text-danger">*</span></label>
                            <select class="select2_demo form-control" id="type_id" name="type_id">
                                <option></option>
                                <option value="1" @if (isset($executive) && $executive->executive_type == 1) {{ 'selected' }} @endif>Marketing
                                    Executive</option>
                                <option value="2" @if (isset($executive) && $executive->executive_type == 2) {{ 'selected' }} @endif>Collection
                                    Executive</option>
                            </select>
                            <label id="type-error" class=" invalid-feedback active type" for="type"></label>
                        </div>
                        <div class="col-sm-6 form-group mb-4">
                            <label>Branch<span class="text-danger">*</span></label>
                            @hasanyrole('super-admin|developer-admin')
                                <select class="select2_demo col-sm-12 form-group" id="branch_id" name="branch_id">
                                    <option value=""></option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            @if (isset($executive) && $executive->branch_id == $branch->id) {{ 'selected' }} @endif>
                                            {{ $branch->branch_id }}-{{ $branch->branch_name }}</option>
                                    @endforeach
                                </select>
                            @endhasanyrole
                            @role('branch-manager')
                                @php
                                    $user = auth()->user();
                                    $manager = \App\Models\Master\Manager::where('user_id', $user->id)->first();
                                @endphp
                                <select class="select2_demo col-sm-12 form-group" id="branch_id" name="branch_id">
                                    @isset($branches)
                                        @foreach ($branches as $branch)
                                            @isset($manager)
                                                @if ($manager->branch_id == $branch->id)
                                                    <option value="{{ $branch->id }}" selected>
                                                        {{ $branch->branch_name }}</option>
                                                @else
                                                    <option value="{{ $branch->id }}" disabled>
                                                        {{ $branch->branch_name }}</option>
                                                @endif
                                            @endisset
                                        @endforeach
                                    @endisset
                                </select>
                            @endrole
                            <label id="branch-error" class=" invalid-feedback active branch" for="branch"></label>
                        </div>
                        <div class="col-sm-6 form-group mb-4">
                            <label>Name<span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="name" placeholder="Executive Name" onkeyup="sync()" value="{{ isset($executive) ? $executive->name : old('name') }}" name="name">
                            <label id="name-error" class=" invalid-feedback active name" for="name"></label>
                        </div>
                        <div class="col-sm-6 form-group mb-4"hidden >
                            <label>Username<span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="username" placeholder=" Manager username" value="{{ isset($executive) ? $executive->username : old('username') }}" name="username">
                            <label id="username-error" class=" invalid-feedback active username" for="username"></label>
                        </div>
                        <div class="col-sm-6 form-group mb-4">
                            <label>Email<span class="text-danger ">*</span></label>
                            <input class="form-control" type="email" id="email" placeholder="Email" value="{{ isset($executive) ? $executive->email : old('email') }}" name="email">
                            <label id="email-error" class=" invalid-feedback active email" for="email"></label>
                        </div>
                        <div class="col-sm-6 form-group mb-4">
                            <label>Mobile<span class="text-danger ">*</span></label>
                            <input type="number" class="form-control" name="mobile" placeholder="Mobile" value="{{ isset($executive) ? $executive->phone : old('mobile') }}">
                            <label id="mobile-error" class=" invalid-feedback active mobile" for="mobile"></label>
                        </div>
                        <div class="col-sm-6 form-group mb-4">
                            <label>Place<span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="place" placeholder="Place" value="{{ isset($executive) ? $executive->place : old('place') }}" name="place">
                            <label id="place-error" class=" invalid-feedback active place" for="place"></label>
                        </div>

                        <div id="area-div" class="col-sm-6 form-group mb-4">
                            <label>Area<span class="text-danger">*</span></label>
                            <select class="select2_demo area_select2 form-control" id="area" name="area_id">
                                <option value=""></option>
                                @foreach ($areas as $area)
                            <option value="{{ $area->id }}" @if (isset($executive) && $executive->collection_area_id == $area->id)
                                {{ 'selected' }}
                            @endif>{{ $area->area_id }}-{{ $area->name }}</option>
                            @endforeach
                            </select>
                            <label id="area_id-error" class=" invalid-feedback active area_id" for="area_id"></label>
                        </div>
                        @if (!isset($executive))
                            <div class="col-sm-6 form-group mb-4">
                                <label>Password<span class="text-danger ">*</span></label>
                                <input class="form-control" id="password" type="password" name="password"
                                    placeholder="password" />
                                <label id="password-error" class=" invalid-feedback active password" for="password"></label>
                            </div>
                            <div class="col-sm-6 form-group mb-4">
                                <label>Confirm Password<span class="text-danger">*</span></label>
                                <input class="form-control" type="password" name="password_confirmation"
                                    placeholder="confirm password">
                                <label id="password_confirmation-error"
                                    class=" invalid-feedback active password_confirmation"
                                    for="password_confirmation"></label>
                            </div>
                        @endif
                        <div class="col-sm-6 form-group mb-4">
                            <label>Status<span class="text-danger">*</span></label>
                            <br>
                            <label class="ui-switch switch-solid"><input type="checkbox" id="status" checked name="status"
                                    {{ isset($executive) && $executive->status ? 'checked' : old('status') }}><span></span></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary mr-2" id="submitForm" type="submit">Submit</button>
                        <button class="btn btn-light" type="reset">Clear</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('backend/assets/vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script><!-- CORE SCRIPTS-->
    <script>
        function sync() {
            var n1 = document.getElementById('name');
            var n2 = document.getElementById('username');
            n2.value = n1.value;
        }
        var branch_id = $("#branch_id :selected").val();
        var type_id = $("#type_id :selected").val();
        var manager_selected_id = '<?php if (isset($executive)) {echo $executive->manager_id;} ?>';
        var area_selected_id = '<?php if (isset($executive)) {echo $executive->collection_area_id;} ?>';
        if (type_id != null && branch_id != 0) {
            getData(type_id, branch_id);
        }

        function getData(type_id, branch_id) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'GET',
                url: "/admin/executive/get-data",
                data: {
                    type_id: type_id,
                    branch_id: branch_id,
                },
                success: function(data) {
                    globalThis.managers = data.managers;
                    globalThis.areas = data.areas;
                    setManager(managers);
                    // setArea(areas);

                },
                error: function(err) {
                    if (err.responseJSON['errors']) {
                        let error = err.responseJSON['errors'];
                        var msg = '';
                        $.each(error, (i, j) => {
                            msg += j + '<br/>';
                        });
                        let errKeys = Object.keys(err.responseJSON['errors']);
                        errKeys.map((item) => {
                            $('[name=' + item + ']').addClass('is-invalid');
                        });
                    }
                    swal(
                        'Something went wrong!',
                        msg,
                        'error'
                    )
                }
            });
        }

        function countExecutive(manager_id) {
            var manager_id = $("#manager :selected").val();

            managers.forEach(element => {
                if (manager_id == element.id) {
                    document.getElementById("no_of_executives").value = element.count;
                }

            });
        }
        $(document).ready(function() {
            var type = $("#type_id :selected").val();
                if(type == 1){
                    $('#area-div').hide();
                }
                else{
                    $('#area-div').show();}
            $(".select2_demo").select2({
                placeholder: "Select an option",
            });

            $('#type_id').on('change', function(e) {
                var type = (this.value);
                if(type == 1){
                    $('#area-div').hide();
                }
                else{
                    $('#area-div').show();}
                // $('#area').find('option').not(':first').remove();
                // document.getElementById('no_of_executives').value = '';
                var type_id = $("#type_id :selected").val();
                var branch_id = $("#branch_id :selected").val();
                getData(type_id, branch_id)
            });

            $('#branch_id').on('change', function(e) {
                // $('#manager').find('option').not(':first').remove();
                // $('#area').find('option').not(':first').remove()/;
                // var type_id = $("#type_id :selected").val();
                // var branch_id = $("#branch_id :selected").val();
                // getData(type_id, branch_id)
            });



            $("#manager-form").validate({
                rules: {
                    area_id: {
                        required: !0
                    },
                    name: {
                        minlength: 2,
                        required: !0
                    },
                    manager: {
                        required: !0
                    },
                    username: {
                        required: !0
                    },
                    email: {
                        required: !0,
                        email: !0
                    },
                    mobile: {
                        required: !0,
                        number: !0,
                        minlength: 10
                    },
                    type: {
                        required: !0
                    },
                    branch_id: {
                        required: !0
                    },
                    password: {
                        required: !0,
                        minlength: 8
                    },
                    password_confirmation: {
                        required: !0,
                        minlength: 8,
                        equalTo: "#password"
                    }
                },
                errorClass: 'invalid-feedback',
                validClass: 'valid-feedback',
                highlight: function(e) {
                    $(e).addClass("is-invalid").removeClass('is-valid');
                },
                unhighlight: function(e) {
                    $(e).removeClass("is-invalid").addClass('is-valid');
                },
            });
            $('#manager-form').submit(function(e) {
                e.preventDefault();
                let form = $(this);
                if (!form.valid()) return false;
                var url = form.attr('action');
                var method = form.attr('method');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: method,
                    url: url,
                    data: $('#manager-form').serialize(),
                    success: function(data) {
                        @if (!isset($executive))
                            swal(
                            'Success!',
                            'Executive has been added.',
                            'success'
                            ).then(()=>{
                            location.reload();
                            });
                            form.trigger('reset');
                        @else
                            swal(
                            'Success!',
                            'Executive has been updated.',
                            'success'
                            ).then(()=>{
                          window.history.back();

                            });
                        @endif
                    },
                    error: function(err) {
                        if (err.responseJSON['errors']) {
                            let error = err.responseJSON['errors'];
                            var msg = '';
                            $.each(error, (i, j) => {
                                $('.' + i).css("display", "block");
                                $('.' + i).text(j);
                                msg += j;
                            });
                            let errKeys = Object.keys(err.responseJSON['errors']);
                            errKeys.map((item) => {
                                $('[name=' + item + ']').addClass('is-invalid');
                            });
                        }
                        // swal(
                        // 'Something went wrong!',
                        // msg,
                        // 'error'
                        // )
                    }
                });
            });
        });

        function setManager(managers) {
            $('.manager_select2').empty();
            managers.forEach(element => {
                if (manager_selected_id) {
                    if (element.id == manager_selected_id) {
                        var newOption = new Option(element.manager_id + ' ' + element.name, element.id, false,
                        true);
                        $('.manager_select2').append(`<option></option`);
                        $('.manager_select2').append(newOption);
                    } else {
                        var newOption = new Option(element.manager_id + ' ' + element.name, element.id, false,
                            false);
                        $('.manager_select2').append(`<option></option`);
                        $('.manager_select2').append(newOption);
                    }
                } else {
                    var newOption = new Option(element.manager_id + ' ' + element.name, element.id, false, false);
                    $('.manager_select2').append(`<option></option`);
                    $('.manager_select2').append(newOption);
                }
            });
        }

        // function setArea(areas) {
        //     $('.area_select2').empty();
        //     areas.forEach(element => {
        //         if (area_selected_id) {
        //             if (element.id == area_selected_id) {
        //                 var newOption = new Option(element.area_id + ' ' + element.name, element.id, false, true);
        //                 $('.area_select2').append(`<option></option`);
        //                 $('.area_select2').append(newOption);
        //             } else {
        //                 var newOption = new Option(element.area_id + ' ' + element.name, element.id, false, false);
        //                 $('.area_select2').append(`<option></option`);
        //                 $('.area_select2').append(newOption);
        //             }
        //         } else {
        //             var newOption = new Option(element.area_id + ' ' + element.name, element.id, false, false);
        //             $('.area_select2').append(`<option></option`);
        //             $('.area_select2').append(newOption);
        //         }
        //     });
        // }
    </script>
@endpush
