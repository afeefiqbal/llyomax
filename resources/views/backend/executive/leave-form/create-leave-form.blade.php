@extends('backend.layouts.backend')

@section('content')

@push('styles')
    <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
    <style>
        .select2-selection__arrow{
            top: 15px !important ;
        }
        .select2-selection__rendered{
            line-height: 10px !important;
        }
        </style>
@endpush

<!-- BEGIN: Page heading-->
<div class="page-heading">
    <div class="page-breadcrumb">
        <h1 class="page-title page-title-sep">
            @if(isset($leave))
                Edit Executive Leave Form
            @else
                Create Executive Leave Form
            @endif
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Executives</li>
            <li class="breadcrumb-item">Leave Form</li>
            <li class="breadcrumb-item">
                @if(isset($leave))
                Edit Executive Leave Form
                @else
                Create Executive Leave Form
                @endif
            </li>
        </ol>
    </div>
    <div>
        <a href="/admin/executive/leave-form">
            <button class="btn btn-primary"><i class="la la-arrow-left"></i>Executive Leave List</button>
        </a>
    </div>
</div>
<!-- End Page Heading -->


<div>
    <div class="col-lg-12">

        <div class="card card-fullheight">
            <div class="card-body">
                @if(isset($leave))
                    <form action="/admin/executive/leave-form/{{ $leave->id }}" method="PATCH" id="leave-form">
                        @method('PATCH')
                    @else
                        <form action="/admin/executive/leave-form" method="POST" id="leave-form">
                @endif
                @csrf
                <div class="row">
                    <div class="col-sm-6 form-group mb-4">
                        <label>Date<span class="text-danger">*</span></label>
                        <input class="form-control" type="date" id="date" placeholder="Select Date"
                            value="{{ isset($leave)? $leave->date : old('date') }}"
                            name="date">
                    </div>
                    <div class="col-sm-6 form-group mb-4">
                        <label>Reason<span class="text-danger">*</span></label>
                        <textarea class="form-control" id="reason" placeholder="Reason for leave"
                            name="reason">{{ isset($leave)? $leave->reason : old('reason') }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary mr-2"
                       id="submitForm" type="submit">Submit</button>
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
    <script src="{{ asset('backend/assets/vendors/jquery-validation/dist/jquery.validate.min.js')}}"></script><!-- CORE SCRIPTS-->
    <script>
        $(document).ready(function () {
             $("#leave-form").validate({
            rules: {
                date: {
                    required: !0
                },
                reason: {
                    required: !0
                },
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

            $('#leave-form').submit(function (e) {
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
                    data:$('#leave-form').serialize(),
                    success: function (data) {
                        @if(!isset($leave))
                        swal(
                        'Success!',
                        'Executive leave has been added.',
                        'success'
                        ).then(()=>{
                                location.reload();
                            });
                        form.trigger('reset');
                        @else
                        swal(
                            'Success!',
                            'Executive leave has been updated.',
                            'success'
                            )
                        @endif

                    },
                    error: function (err) {


                        if (err.responseJSON['errors']) {

                            let error = err.responseJSON['errors'];
                            var msg = '';
                            $.each(error, (i, j) => {
                                $('.'+i).text(j);
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
            });

        });

    </script>
@endpush
