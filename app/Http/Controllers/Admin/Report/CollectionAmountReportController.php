<?php

namespace App\Http\Controllers\admin\Report;

use App\Http\Controllers\Controller;
use App\Models\AmountTransferDetail;
use Illuminate\Http\Request;
use App\Models\CustomerScheme;
use App\Models\Executive\Executive;
use App\Models\Executive\ExecutiveReportSubmission;
use App\Models\Master\Area;
use App\Models\Master\Branch;
use App\Models\Master\Manager;
use App\Models\Scheme;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class CollectionAmountReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        try {
            if ($request->ajax()) {

                $transferAmount = AmountTransferDetail::with('branch')->where('executive_id',null)->get()->unique('branch_id');
                return DataTables::of($transferAmount)
                    ->addIndexColumn()
                    ->addColumn('branch_id', function ($row) {
                        $branch = $row->branch;
                        if(isset($branch)) {
                            return $branch->branch_id.'-'.$branch->name;
                        }
                        else{
                            return '';
                        }
                    })
                    ->addColumn('total_amount', function ($row) {
                        $total = CustomerScheme::where('branch_id',$row->branch_id)->sum('total_amount');
                        return $total;
                    })
                    ->addColumn('transfered_amount', function ($row) {

                        $transfer_amount = AmountTransferDetail::where('branch_id',$row->branch_id)->where('executive_id',null)->sum('transfer_amount');
                        return  $transfer_amount;
                    })
                    ->addColumn('pending_transfered_amount', function ($row) {
                        $total = CustomerScheme::where('branch_id',$row->branch_id)->sum('total_amount');
                        $transfer_amount = AmountTransferDetail::where('branch_id',$row->branch_id)->where('executive_id',null)->sum('transfer_amount');
                        return ($total-$transfer_amount);
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="collection-amount/' . $row->branch_id . '" class="view btn btn-primary btn-floating btn-sm">
                        <i class="la la-eye"></i>
                    </a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('backend.reports.collection-amount-details');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $amountTransferDetails = AmountTransferDetail::where('branch_id', $id)->where('executive_id',null)->get();

        return view('backend.reports.show-amount-transfer-details', compact('amountTransferDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
