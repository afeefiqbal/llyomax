<?php

namespace App\Http\Controllers\Admin\Branch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AmountTransferDetail;
use App\Models\Executive\Executive;
use App\Models\Executive\ExecutiveReportSubmission;
use App\Models\MarketingExecutiveTarget;
use App\Models\Master\Area;
use App\Models\Master\Branch;
use App\Models\Master\Manager;
use App\Repositories\interfaces\Branch\AmountTransferDetailInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class AmountTransferExecutiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $amountTransfer;
    public function __construct(AmountTransferDetailInterface $amountTransfer)
    {
        $this->middleware(['role:super-admin|developer-admin|branch-manager|collection-executive|marketing-executive|collection-manager']);
        $this->amountTransfer = $amountTransfer;
    }
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $user = Auth::user();
                $userRole = $user->roles->pluck('name')->first();
                if ($userRole == 'collection-executive' || $userRole == 'marketing-executive') {
                    $amountTransfer = $this->amountTransfer->listExecutiveAmountTransferDetails($request, $user->id);
                } elseif ($userRole == 'branch-manager') {

                    $amountTransfer = $this->amountTransfer->listBranchExecutiveAmountTransferDetails($request, $user->id);
                }
                else {
                    $amountTransfer = $this->amountTransfer->listAllExecutiveAmountTransferDetails($request);
                }
                return DataTables::of($amountTransfer)
                    ->addIndexColumn()
                    ->addColumn('branch_id', function ($row) {
                        return $row->branch->branch_name ?? '';
                    })
                    ->addColumn('name', function ($row) {
                        $executive = Executive::where('id', $row->executive_id)->first();
                        return  $executive->executive_id . "-" . $executive->name ?? '';
                    })
                    ->addColumn('transfer_type', function ($row) {
                        return $row->transfer_type == 1 ? "By Hand" : "Bank";
                    })
                    ->addColumn('transfer_time', function ($row) {
                        return date('h:i a', strtotime($row->transfer_time));
                    })
                    ->addColumn('status', function ($row) {
                        return $row->status == 0 ? "<span style='color:red'>Not approved</span>" : "Approved";
                    })
                    ->addColumn('action', function ($row) use($userRole) {
                        if($userRole == 'collection-executive' ){
                                $btn = '
                        <a href="amount-transfer/' . $row->id . '" class="view btn btn-primary btn-floating btn-sm">
                                <i class="la la-eye"></i>
                            </a>
                        <a data-id="' . $row->id . '" class="delete btn btn-danger btn-floating btn-sm">
                        <i class="la la-trash"></i>
                    </a>
                        ';
                        }
                        else{
                            $btn = '
                            <a href="amount-transfer/' . $row->id . '" class="view btn btn-primary btn-floating btn-sm">
                                    <i class="la la-eye"></i>
                                </a>
                            <a data-id="' . $row->id . '" class="delete btn btn-danger btn-floating btn-sm">
                            <i class="la la-trash"></i>
                        </a>
                            ';
                        }

                        return $btn;
                    })
                    ->rawColumns(['action','status'])
                    ->make(true);
            }
            return view('backend.branch.amount-transfer.list-executive-amount-transfer-details');
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
        $branches = Branch::get();
       $user = Auth::user();
         $userRole = $user->roles->pluck('name')->first();
       $executive = Executive::where('user_id', $user->id)->first();
        $executiveReportSubmission = ExecutiveReportSubmission::where('executive_id',$executive->id )->get();
        $exMap =  $executiveReportSubmission->map(function($q){
            return $q->paid_amount;
        });
        $transferAmount = $exMap->sum();
        return view('backend.branch.amount-transfer.create-amount-transfer-details', compact('branches','transferAmount'));
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
        //
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
