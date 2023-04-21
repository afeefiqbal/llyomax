<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\WeeklyGift;
use App\Models\Branch\Branch;
use App\Models\Customer;
use App\Models\Executive\ExecutiveReportSubmission;
use App\Models\Master\Cluster;
use App\Models\Scheme;
use App\Repositories\interfaces\Accounts\WeeklyGiftInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class WeeklyGiftController extends Controller
{
    protected $weeklyGiftInterface;

    public function __construct(WeeklyGiftInterface $weeklyGiftInterface)
    {
        $this->weeklyGiftInterface = $weeklyGiftInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $user = Auth::user();
                $userRole = $user->roles->pluck('name')->first();
                $weeklyGift = $this->weeklyGiftInterface->listWeeklyGifts();
                // if ($userRole == 'branch-manager') {
                //     $schemes = $this->schemes->listBranchSchemes($user->id);
                // } else {
                // }

                return DataTables::of($weeklyGift)
                    ->addIndexColumn()
                    ->addColumn('scheme_id', function ($row) {
                        return $row->scheme->scheme_id.'-'.$row->scheme->name;
                    })
                    ->addColumn('customer_id', function ($row) {
                        return $row->customer->customer_id.'-'.$row->customer->name;
                    })
                    ->addColumn('branch_id', function ($row) {
                        $branch = $row->branch;
                        if(isset($branch)) {
                            return $branch->branch_id.'-'.$branch->name;
                        }
                        else{
                            return '';
                        }
                    })
                    ->addColumn('given_by', function ($row) {
                        if($row->given_by == '1'){
                            return 'Branch';
                        }else{
                            return 'Shop';
                        }
                    })
                    ->addColumn('date', function ($row) {
                        return $row->date;
                    })
                    ->addColumn('bill', function ($row) {
                        $pdf = '<iframe src="' .$row->getFirstMediaUrl('weeklyGifts','weeklyGift'). '" width="100%" height="100%" style="border: none;"></iframe>';
                        return $pdf;
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="weekly-gifts/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
                            <i class="la la-pencil"></i>
                        </a>
                        <a data-id="' . $row->id . '" class="delete btn btn-danger btn-floating btn-sm">
                        <i class="la la-trash"></i>
                    </a>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'bill'])
                    ->make(true);
            }
            return view('backend.accounts.weekly-gifts.list-weekly-gifts');
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
        $schemes = Scheme::get();
        $branches = Branch::get();
        $clusters = Cluster::get();
        $customers = Customer::get();
        return view('backend.accounts.weekly-gifts.create-weekly-gifts', compact('schemes', 'branches', 'customers','clusters'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'scheme_id' => 'required',
            'branch_id' => 'required',
            'customer_id' => 'required',
            'amount' => 'required',
            'date' => 'required',
            'given_by' => 'required',
            'gift_items' => 'required',
            'bill_doc' => 'required',
        ]);
        try {
            $weeklyGift = $this->weeklyGiftInterface->createWeeklyGift($request);
            if ($weeklyGift) {
                return response()->json(['success' => 'Weekly gift created successfully']);
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
        $weeklyGift = $this->weeklyGiftInterface->getWeeklyGift($id);

        $scheme = Scheme::where('id', $weeklyGift->scheme_id)->first();
        $start_date =$scheme->start_date;

        $branches = Branch::get();
        $clusters = Cluster::get();
        $customers = Customer::get();
        $schemes = Scheme::get();
        $start_date = $scheme->start_date;
        $weeklyGift = $this->weeklyGiftInterface->getWeeklyGift($id);
        return view('backend.accounts.weekly-gifts.create-weekly-gifts', compact('weeklyGift', 'schemes', 'branches', 'customers','clusters'));
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
        $request->validate([
            'scheme_id' => 'required',
            'branch_id' => 'required',
            'customer_id' => 'required',
            'amount' => 'required',
            'date' => 'required',
            'given_by' => 'required',
            'gift_items' => 'required',
            'bill_doc' => 'required',
        ]);
        try {
            $weeklyGift = $this->weeklyGiftInterface->updateWeeklyGift($request,$id);
            if ($weeklyGift) {
                return response()->json(['success' => 'Weekly gift updated successfully']);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $delete = WeeklyGift::find($id)->delete();
        return $delete;
    }
    public function getBranchs(Request $request)
    {

        $branches = Branch::where('scheme_id', [$request->scheme_id])->get();

        return response()->json(['branches' => $branches]);

    }
    public function getCustomers(Request $request)
    {
        $schemeCustomers = ExecutiveReportSubmission::where('branch_id', $request->branch_id)
            ->where('scheme_id', $request->scheme_id);
            $schemeCustomers = $schemeCustomers->where('paid_week', $request->week)->where('due_amount', 0)->pluck('customer_id')->toArray();
        $customers = Customer::join('customer_scheme', 'customers.id', '=', 'customer_scheme.customer_id')
            ->where('customer_scheme.branch_id', $request->branch_id)
            ->where('customer_scheme.scheme_id', $request->scheme_id)
            ->whereIn('customer_scheme.status', [1, 2])
            ->whereIn('customers.id', $schemeCustomers)
            ->with('area')
            ->get(['customers.*']);

        return response()->json(['customers' => $customers]);

    }
}
