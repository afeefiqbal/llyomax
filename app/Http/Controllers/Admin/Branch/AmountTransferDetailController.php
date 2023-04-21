<?php

namespace App\Http\Controllers\Admin\Branch;

use App\Http\Controllers\Controller;
use App\Models\AmountTransferDetail;
use Illuminate\Http\Request;
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
class AmountTransferDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $amountTransfer;
    public function __construct(AmountTransferDetailInterface $amountTransfer)
    {
        $this->middleware(['role:super-admin|developer-admin|branch-manager|marketing-manager|collection-executive|marketing-executive|collection-manager']);
        $this->amountTransfer = $amountTransfer;
    }
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {

                $user = Auth::user();
                $userRole = $user->roles->pluck('name')->first();
                if ($userRole == 'branch-manager') {
                    $amountTransfer = $this->amountTransfer->listBranchAmountTransferDetails($request,$user->id);
                } else {
                    $amountTransfer = $this->amountTransfer->listAmountTransferDetails($request);
                }

                return DataTables::of($amountTransfer)
                    ->addIndexColumn()
                    ->addColumn('branch_id', function ($row) {
                        return $row->branch->branch_name;
                    })
                    ->addColumn('name', function ($row) {



                            $manager = Manager::where('branch_id',$row->branch->id)->where('type',1)->first();
                            if(isset($manager->name)){
                                $manager = $manager->name;
                            }
                            else{
                                $manager = '';
                            }
                            return $manager;


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
            return view('backend.branch.amount-transfer.list-amount-transfer-details');
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
        $manager = Manager::where('user_id',$user->id)->first();
        $banchID = $manager->branch_id;
        $executiveReportSubmission = ExecutiveReportSubmission::where('branch_id',$banchID )->get();
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
        $request->validate(
            [
                'date' => 'required ',
                'transfer_amount' => 'required ',
                'transfer_time' => 'required ',
                'branch_id' => 'required',
            ],
            [
                '*.required' => 'This field is required',
                'scheme_id.unique' => 'The scheme Target has already been taken'
            ]
        );
        try {
            $amountTransfer = $this->amountTransfer->createAmountTransfer($request);
            if ($amountTransfer) {
                return response()->json(['success' => 'Amount Transfer successfully created']);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $amountTransferDetails = AmountTransferDetail::where('id', $id)->with('branch')->first();
        return view('backend.branch.amount-transfer.show-amount-transfer-details', compact('amountTransferDetails'));
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
        try {
            $amountTransfer = $this->amountTransfer->deleteAmountTransferDetails($id);
            if ($amountTransfer) {
                return response()->json(['success' => 'Amount Transfer successfully deleted']);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
  public function  statusChanger(){
        $id = request()->id;
        $status = request()->status;
        $amountTransfer = AmountTransferDetail::where('id',$id)->update(['status'=>$status]);
        if($amountTransfer){
            return response()->json(['success'=>'Status changed successfully']);
        }
  }
}
