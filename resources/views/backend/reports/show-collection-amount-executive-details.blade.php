@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <link href="{{ asset('backend/assets/vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
    @endpush
    <!-- BEGIN: Page heading-->
    <div class="page-heading">
        <div class="page-breadcrumb">
            <h1 class="page-title page-title-sep">Transfer Amount Details</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Transfer Amount Details</li>
                <li class="breadcrumb-item">Transfer Amount Details</li>
            </ol>
        </div>
        <div>
            <a href="/admin/reports/collection-amount-executive">
                <button class="btn btn-primary"><i class="la la-arrow-left"></i> Collection Amount List</button>
            </a>
        </div>
    </div>
    <!-- End Page Heading -->
    <div>
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered w-100 data-table" id="dt-filter">
                    <thead class="thead-light">
                        <tr>
                            <th>Transfered Date</th>
                            <th>Transfered Time</th>
                            <th>Transfered Amount</th>
                            <th>Transfer type</th>
                            <th>Receipt</th>
                        </tr>
                    </thead>
                    @foreach ($amountTransferDetails as $amountTransferDetail)
                        <tr>
                            <td>{{ $amountTransferDetail->date }}</td>
                            <td>{{ date('h:i a', strtotime($amountTransferDetail->transfer_time)) }}</td>
                            <td>{{ $amountTransferDetail->transfer_amount }}</td>
                            <td>{{ $amountTransferDetail->transfer_type == 1 ? 'By Hand' : 'Bank' }}</td>
                            @php
                                $media = Spatie\MediaLibrary\MediaCollections\Models\Media::where('collection_name', 'receipt_images')
                                    ->where('model_id', $amountTransferDetail->id)
                                    ->first();
                            @endphp
                            @if ($media)
                                <td>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                        View
                                       </button>
                                       <button type="button" class="btn btn-link"><a
                                         href="{{ url('/storage/' . $media->order_column . '/' . $media->file_name) }}"
                                         download>Download File</a> </button>
                                       <!-- Modal -->
                                       <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                         <div class="modal-dialog" role="document">
                                           <div class="modal-content">
                                             <div class="modal-header">
                                               <h5 class="modal-title" id="exampleModalLabel">Receipt</h5>
                                               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                 <span aria-hidden="true">&times;</span>
                                               </button>
                                             </div>
                                             <div class="modal-body">
                                                 <img class="btn p-0 card-img3" src="{{ url('/storage/' . $media->order_column . '/' . $media->file_name) }}">
                                             </div>
                                            
                                           </div>
                                         </div>
                                       </div>
                                    {{-- <button type="button" class="btn btn-info"><a
                                            href="{{ url('/storage/' . $media->order_column . '/' . $media->file_name) }}">view</a></button>
                                    <button type="button" class="btn btn-link"><a
                                            href="{{ url('/storage/' . $media->order_column . '/' . $media->file_name) }}"
                                            download>Download File</a> </button> --}}
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('backend/assets/vendors/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script type="text/javascript">
    </script>
@endpush
