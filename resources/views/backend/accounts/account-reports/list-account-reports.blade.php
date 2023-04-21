@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <link href="{{ asset('backend/assets/vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
    @endpush
    <div class="page-heading">
        <div class="page-breadcrumb">
            <h1 class="page-title page-title-sep">Account Reports</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/dashboard"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Reports</li>
                <li class="breadcrumb-item">Account Reports</li>
            </ol>
        </div>
    </div>
    <div>
        <div class="card">
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-4">Opening Balace from Collection : </div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-4"><span id="op-bal">0</span></div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-4">Total Cash Recieved : </div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-4"><span id="tot-cash-rec">0</span></div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-4">Total Expense : </div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-4"><span id="tot-exp">0</span></div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-4">Total Transfered to home Branch :</div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-4"><span id="tot-transf-bal">0</span></div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-4">Closing Balance :</div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-4"><span id="cl-bal">0</span></div>
                        </div>
                    </li>
                  </ul>
            </div>
        </div>
    </div>
    @endsection
@push('scripts')
<script>
    $(document).ready(function(){
        var op_bal = 0;
        var tot_cash_rec = 0;
        var tot_exp = 0;
        var tot_transf_bal = 0;
        var cl_bal = 0;
        var date = $('#date').val();
        $.ajax({
            url: '/admin/accounts/get-reports',
            type: 'GET',
            data: {
                date: date
            },
            success: function(data){
                console.log(data);
                op_bal = data.openingBalance;
                tot_cash_rec = data.totalCollection;
                tot_exp = data.totalExpenses;
                tot_transf_bal = data.totalTransfBal;
                cl_bal = data.closingBalance;
                $('#op-bal').text(op_bal);
                $('#tot-cash-rec').text(tot_cash_rec);
                $('#tot-exp').text(tot_exp);
                $('#tot-transf-bal').text(tot_transf_bal);
                $('#cl-bal').text(cl_bal);
            }
        });
    });
</script>
@endpush1

