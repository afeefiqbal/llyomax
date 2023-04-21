@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
        <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
        <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            .select2-selection__arrow {
                top: 15px !important;
            }

            .select2-selection__rendered {
                line-height: 10px !important;
            }

        </style>
    @endpush
    <!-- BEGIN: Page heading-->
    <div class="page-heading">
        <div class="page-breadcrumb">
            <h1 class="page-title page-title-sep">
                @if (isset($scheme))
                    Edit scheme
                @else
                    Create scheme
                @endif
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Branch</li>
                <li class="breadcrumb-item">Scheme</li>
                <li class="breadcrumb-item">
                    @if (isset($scheme))
                        Edit scheme
                    @else
                        Create scheme
                    @endif
                </li>
            </ol>
        </div>
        <div>
            <a href="/admin/master/schemes">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i> Schemes List</button>
            </a>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="col-lg-12">
            <div class="card card-fullheight">
                <div class="card-body">
                    @if (isset($scheme))
                        <form action="/admin/master/schemes/{{ $scheme->id }}" method="PATCH" id="scheme-form">
                            @method('PATCH')
                        @else
                            <form action="/admin/master/schemes" method="POST" id="scheme-form">
                    @endif
                    @csrf
                    <div class="row">
                        {{-- <div class="col-sm-4 form-group mb-4">
                            <label>Scheme ID<span class="text-danger ">*</span></label>
                            <input class="form-control" readonly type="text" id="scheme_a_id" placeholder="Scheme ID"value="{{ isset($scheme) ? $scheme->scheme_id : $result }}" name="scheme_id">
                            <input class="form-control" readonly type="text" id="scheme_n_id" placeholder="Scheme ID"value="{{ isset($scheme) ? $scheme->scheme_id : $result }}" name="scheme_id">
                            <label id="scheme_id-error" class=" invalid-feedback active scheme_id" for="scheme_id"></label>
                        </div> --}}
                        <div class="col-sm-4 form-group mb-4">
                            <label for="">Scheme ID <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control"   id="scheme_a_id" placeholder="Scheme ID"value="{{ isset($scheme) ? $scheme->scheme_a_id : old('scheme_a_id') }}" name="scheme_a_id">
                                <input type="text" class="form-control" readonly type="text" id="scheme_n_id" placeholder="Scheme ID"value="{{ isset($scheme) ? $scheme->scheme_n_id : $result }}" name="scheme_n_id">
                                <label id="scheme_a_id-error" class=" invalid-feedback active scheme_a_id" for="scheme_a_id"></label>
                            </div>
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label>Cluster name<span class="text-danger">*</span></label>

                                <select class="select2_demo form-control " id="cluster_id" name="cluster_id">
                                    @isset($clusters)
                                        @foreach ($clusters as $cluster)
                                            <option value="{{ $cluster->id }}"
                                                @if (isset($scheme) && $scheme->cluster_id == $cluster->id) {{ 'selected' }} @endif>
                                                {{ $cluster->name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                                <label id="cluster_id-error" class=" invalid-feedback active cluster_id" for="cluster_id"></label>
                        </div>



                        <div class="col-sm-4 form-group mb-4">
                            <label>Scheme Name<span class="text-danger  ">*</span></label>
                            <input class="form-control" type="text" id="scheme_name" placeholder=" scheme Name"
                                value="{{ isset($scheme) ? $scheme->name : old('name') }}" name="name">
                            <label id="name-error" class=" invalid-feedback active name" for="name"></label>
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label>Details</label>
                            <textarea class="form-control" id="details" placeholder="Details"
                                name="details">{{ isset($scheme) ? $scheme->details : old('details') }}</textarea>
                            <label id="details-error" class=" invalid-feedback active details" for="details"></label>
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label>Scheme Start Date<span class="text-danger ">*</span></label>
                            <input class="form-control" type="date" id="start_date" onchange="handler(event);"
                                placeholder="Start Date"
                                value="{{ isset($scheme) ? $scheme->start_date : old('start_date') }}" name="start_date">
                            <label id="start_date-error" class=" invalid-feedback active start_date"
                                for="start_date"></label>
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label>Scheme End Date<span class="text-danger ">*</span></label>
                            <input class="form-control" type="date" id="end_date" placeholder="End Date"
                                value="{{ isset($scheme) ? $scheme->end_date : old('end_date') }}" name="end_date"
                                readonly>
                            <label id="end_date-error" class=" invalid-feedback active end_date" for="end_date"></label>
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label>Scheme Collection Day<span class="text-danger ">*</span></label>
                            <input class="form-control" type="text" id="collection_day"
                                value="{{ isset($scheme) ? $scheme->scheme_collection_day : old('collection_day') }}"
                                name="collection_day" readonly>
                            <label id="end_date-error" class=" invalid-feedback active end_date" for="end_date"></label>
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label>Joning Start Date<span class="text-danger ">*</span></label>
                            <input class="form-control" type="date" id="join_start_date" placeholder="Start Date"
                                value="{{ isset($scheme) ? $scheme->join_start_date : old('join_start_date') }}"
                                name="join_start_date">
                            <label id="join_start_date-error" class=" invalid-feedback active join_start_date"
                                for="join_start_date"></label>
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label>Joning End Date<span class="text-danger ">*</span></label>
                            <input class="form-control" type="date" id="join_end_date" placeholder="End Date"
                                value="{{ isset($scheme) ? $scheme->join_end_date : old('join_end_date') }}"
                                name="join_end_date" readonly>
                            <label id="join_end_date-error" class=" invalid-feedback active join_end_date"
                                for="join_end_date"></label>
                        </div>
                        <div class="col-sm-4 form-group mb-4">
                            <label>Scheme Image</label>
                            <input type="file" class="scheme_image" name="scheme_image"
                                value="{{ isset($scheme) ? $scheme->image : old('scheme_image') }}">
                            <label id="scheme_image-error" class=" invalid-feedback active scheme_image"
                                for="scheme_image"></label>
                        </div>
                        <div class="form-group col-md-6">
                            <br>
                            <label class="ui-switch switch-solid"> &nbsp;
                                <input type="checkbox"
                                    {{ isset($product) ? ($product->status == '1' ? 'checked' : '') : 'checked' }}
                                    name="status">
                                <span class="ml-0"></span>Active
                            </label>
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
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>
    <script src="{{ asset('backend/assets/vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <!-- CORE SCRIPTS-->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        var date_selected = `<?php if (isset($scheme)) {
    $timestamp = strtotime($scheme->start_date);
    $day = date('l', $timestamp);
    echo $day;
} ?>`;
        document.getElementById("collection_day").value = date_selected;

        function handler(e) {
            var date1 = e.target.value;
            var d = new Date(date1);
            console.log(d);
            d.setDate(d.getDate() + parseInt(7 * 30));
            var yyyy = d.getFullYear();
            var mm = (d.getMonth() + 1).toString().length > 1 ? (d.getMonth() + 1) : "0" + (d.getMonth() + 1);
            var dd = (d.getDate()).toString().length > 1 ? (d.getDate()) : "0" + (d.getDate());
            var formattedDate = yyyy + '-' + mm + '-' + dd;
            document.getElementById("end_date").value = formattedDate;
            document.getElementById("join_end_date").value = date1;
            const weekday = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            let day = weekday[d.getDay()];
            document.getElementById("collection_day").value = day;
        }
        $(document).ready(function() {
            $(".select2_demo").select2({
                placeholder: "Select an option",
            });
            FilePond.registerPlugin(
                FilePondPluginFileValidateSize,
                FilePondPluginFileValidateType,
                FilePondPluginImagePreview,
                FilePondPluginFileEncode
            );
            $('.scheme_image').filepond({
                allowFileTypeValidation: true,
                allowFileSizeValidation: true,
                maxFileSize: '5MB',
                acceptedFileTypes: ['image/*'],
                allowImagePreview: true,
                allowFileEncode: true,
                credits: false,
                @if (isset($scheme) && $scheme->hasMedia('scheme_images'))
                    files: [{                  
                    source: "{{ $scheme->getFirstMediaUrl('scheme_images') }}",
                    }],
                @endif
            });
            $(".select2_demo").select2({
                placeholder: "Select an option",
            });
            $('#scheme-form').submit(function(e) {
                e.preventDefault();
                let form = $(this);
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
                    data: $('#scheme-form').serialize(),
                    beforeSend: function() {
                        $(document).find('label.invalid-feedback').text('');
                        $(document).find('input.is-invalid').removeClass('is-invalid');
                    },
                    success: function(response) {
                        @if (!isset($scheme))
                            swal(
                            'Success!',
                            'Scheme has been added.',
                            'success'
                            ).then(()=>{
                            location.reload();
                            });
                            form.trigger('reset');
                        @else
                            swal(
                            'Success!',
                            'Scheme has been updated.',
                            'success'
                            )
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
    </script>
@endpush
