@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <style>
            .modal-dialog {
                max-width: 900px;
            }

        </style>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        {{-- <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" /> --}}
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        {{-- <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" /> --}}
        <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    <!-- BEGIN: Page heading-->
    <div class="page-heading">
        <div class="page-breadcrumb">
            <h1 class="page-title page-title-sep">
                @if (isset($scheme))
                    scheme ID: {{ $scheme->scheme_a_id.'-'.$scheme->scheme_n_id }}
                @endif
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
            </ol>
        </div>
        <div>
            <a href="/admin/master/schemes">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i>scheme List</button>
            </a>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="col-lg-12">
            <div class="card  card-fullheight">
                <div class="card-body">
                    <div>
                        <ul class="nav nav-tabs nav-top-border nav-tabs-lg">
                            <li class="nav-item"><a class="nav-link  active" data-toggle="tab" href="#tab-info"><i
                                        class="ti-receipt nav-tabs-icon "></i>scheme Details</a></li>
                            {{-- <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab-items"><i
                                        class="ti-shopping-cart nav-tabs-icon"></i>Add Branches To scheme</a></li> --}}
                        </ul>
                        <div class="tab-content mt-4">
                            <div class="tab-pane fade  show  active" id="tab-info">
                                <div class="col-md-12" id="detail-scheme">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5>scheme ID </h5>
                                        </div>
                                        <div class="col-md-6">
                                            <h5>{{ $scheme->scheme_a_id.'-'.$scheme->scheme_n_id }}</h5>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row mb-3">
                                        <div class="col-md-6">Name </div>
                                        <div class="col-md-6">{{ $scheme->name }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">Start Date </div>
                                        <div class="col-md-6">{{ $scheme->start_date }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">End Date </div>
                                        <div class="col-md-6">{{ $scheme->end_date }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">Join start Date </div>
                                        <div class="col-md-6">{{ $scheme->join_start_date }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">Join end Date </div>
                                        <div class="col-md-6">{{ $scheme->join_end_date }}</div>
                                    </div>
                                    <hr>
                                    <div class="row mb-3">
                                        <div class="col-md-6">Addvance </div>
                                        <div class="col-md-6">{{ $scheme->advance }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">Total Amount </div>
                                        <div class="col-md-6">{{ $scheme->total_amount }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">Scheme Collection Day </div>
                                        <div class="col-md-6">{{ $scheme->scheme_collection_day }}</div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="tab-pane fade show active" id="tab-items">
                                <div class="card-body">
                                    <form action="/admin/master/branch_assigning" method="POST" id="branch_assigning-form">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-6 form-group mb-4">
                                                <label>Scheme<span class="text-danger">*</span></label>
                                                <input type="hidden" name="scheme_id" value="{{$scheme->id}}">
                                                <input class="form-control" type="readonly" readonly value="{{$scheme->scheme_a_id.'-'.$scheme->scheme_n_id.'-'.$scheme->name}}" name="">
                                            <sub class="text-danger email" for="email"></sub>
                                            </div>
                                            <div class="col-sm-6 form-group mb-4">
                                                <label>Branches<span class="text-danger">*</span></label>
                                                @hasanyrole('super-admin|developer-admin')
                                                <select class="select2 col-sm-12 form-group multiple" multiple id="branch_id" name="branch_id[]" required>
                                                    <option value="">--select-an-option--</option>
                                                    @foreach ($branches as $branch)

                                                        <option value="{{ $branch->id }}" @if (isset($scheme) && $scheme->branch_id == $branch->id)
                                                            {{ 'selected' }}
                                                    @endif>{{ $branch->id }}-{{ $branch->branch_name }}</option>
                                                    @endforeach
                                                </select>
                                                @endhasanyrole

                                                <sub class="text-danger branch_id" for="branch_id"></sub>
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-primary mr-2" id="submitForm" type="submit">Submit</button>
                                            <button class="btn btn-light" type="reset">Clear</button>
                                        </div>
                                    </form>
                                </div>
                            </div> --}}
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

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('backend/assets/vendors/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('backend/assets/vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>

<script>
       $('.select2').select2({
                placeholder: "Select an option",
            });
            $('#branch_assigning-form').submit(function(e) {
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
                        data: $('#branch_assigning-form').serialize(),
                        success: function(data) {
                            toastr.options = {
                                "closeButton": true,
                                "progressBar": true,
                                "timeOut": "1500",
                            }
                            toastr.success(data.success);
                        },
                        error: function(err) {
                            if (err.responseJSON['errors']) {
                                let error = err.responseJSON['errors'];
                                var msg = '';
                                $.each(error, (i, j) => {
                                    $('.' + i).text(j);
                                    msg += j + '<br/>';
                                });
                                let errKeys = Object.keys(err.responseJSON['errors']);
                                errKeys.map((item) => {
                                    $('[name=' + item + ']').addClass('is-invalid');
                                });
                            }
                        }
                    });
            });
</script>
@endpush
