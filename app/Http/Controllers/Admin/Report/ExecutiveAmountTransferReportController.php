<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AmountTransferDetail;
use App\Models\CustomerExecutive;
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
use Illuminate\Support\Facades\Auth;

class ExecutiveAmountTransferReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['role:super-admin|developer-admin|branch-manager']);
    }
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $user = Auth::user();
                $userRole = $user->roles->pluck('name')->first();
                if ($userRole == 'super-admin'  || $userRole == 'developer-admin') {
                    $transferAmount = AmountTransferDetail::with('branch')->where('executive_id', '!=', null)->with('executive')->get()->unique('executive_id');
                } elseif ($userRole == 'branch-manager') {
                    $manager = Manager::where('user_id', $user->id)->first();
                    $transferAmount = AmountTransferDetail::where('branch_id', $manager->branch_id)->with('branch')->where('executive_id', '!=', null)->with('executive')->get()->unique('executive_id');
                }
                return DataTables::of($transferAmount)
                    ->addIndexColumn()
                    ->addColumn('branch_id', function ($row) {
                        $branch = Branch::where('id', $row->branch_id)->first();
                        $branch = $row->branch;
                        if(isset($branch)) {
                            return $branch->branch_id.'-'.$branch->name;
                        }
                        else{
                            return '';
                        }
                    })
                    ->addColumn('executive', function ($row) {
                        $executive = Executive::where('id', $row->executive_id)->first();
                        return $executive->executive_id . "-" . $executive->name;
                    })
                    ->addColumn('total_amount', function ($row) {
                        $executive = Executive::where('id', $row->executive_id)->first();
                        if ($executive->executive_type == '2') {
                            $cutomerexecutive = CustomerExecutive::where('executive_id', $row->executive_id)->pluck('customer_id')->toArray();
                            $total = ExecutiveReportSubmission::whereIn('customer_id', $cutomerexecutive)->sum('paid_amount');
                        } elseif ($executive->executive_type == '1') {
                            $total = "";
                        }
                        return $total;
                    })
                    ->addColumn('transfered_amount', function ($row) {
                        $transfer_amount = AmountTransferDetail::where('executive_id', $row->executive_id)->sum('transfer_amount');
                        return  $transfer_amount;
                    })
                    ->addColumn('pending_transfered_amount', function ($row) {
                        $executive = Executive::where('id', $row->executive_id)->first();
                        if ($executive->executive_type == '2') {
                            $cutomerexecutive = CustomerExecutive::where('executive_id', $row->executive_id)->pluck('customer_id')->toArray();
                            $total = ExecutiveReportSubmission::whereIn('customer_id', $cutomerexecutive)->sum('paid_amount');
                            $transfer_amount = AmountTransferDetail::where('executive_id', $row->executive_id)->sum('transfer_amount');
                            return ($total - $transfer_amount);
                        } elseif ($executive->executive_type == '1') {
                            return "";
                        }
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="collection-amount-executive/' . $row->executive_id . '" class="view btn btn-primary btn-floating btn-sm">
                        <i class="la la-eye"></i>
                    </a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('backend.reports.collection-amount-executive-details');
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
        $amountTransferDetails =  AmountTransferDetail::where('executive_id', $id)->get();
        return view('backend.reports.show-collection-amount-executive-details', compact('amountTransferDetails'));
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
