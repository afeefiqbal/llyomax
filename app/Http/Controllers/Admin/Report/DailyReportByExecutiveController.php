<?php

namespace App\Http\Controllers\admin\Report;

use App\Http\Controllers\Controller;
use App\Models\CustomerExecutive;
use App\Models\CustomerScheme;
use App\Models\Executive\Executive;
use App\Models\Executive\ExecutiveReportSubmission;
use App\Models\Master\Area;
use App\Models\Master\Branch;
use App\Models\Scheme;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class DailyReportByExecutiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['role:super-admin|developer-admin|branch-manager|marketing-executive']);
    }
    public function index(Request $request)
    {
        $branches = Branch::get();
        try {
            if ($request->ajax()) {
                if ($request->branch_id == 0 && $request->executive_id == 0 && $request->scheme_id == 0) {
                    ##-- user Role--##
                    $user = Auth::user();
                    $userRole = $user->roles->pluck('name')->first();
                    if ($userRole == 'marketing-executive') {
                        $executive = Executive::where('user_id', $user->id)->first();
                        $customerSchemeReport = CustomerScheme::join('executives', 'customer_scheme.executive_id', '=', 'executives.id')
                            ->whereDate('customer_scheme.joining_date', $request->date)
                            ->where('customer_scheme.executive_id', $executive->id)
                            ->with('customer')
                            ->get()->sortBy('customer.name');
                    } else {
                        $customerSchemeReport = CustomerScheme::join('executives', 'customer_scheme.executive_id', '=', 'executives.id')
                            ->whereDate('customer_scheme.joining_date', $request->date)
                            ->where('executives.executive_type', 1)
                            ->with('customer')
                            ->get()->sortBy('customer.name');
                    }
                } else {
                    if ($request->branch_id != 0 && $request->executive_id == 0 && $request->scheme_id == 0) {
                        $customerSchemeReport = CustomerScheme::join('executives', 'customer_scheme.executive_id', '=', 'executives.id')
                            ->whereDate('customer_scheme.joining_date', $request->date)
                            ->where('customer_scheme.branch_id', $request->branch_id)
                            ->where('executives.executive_type', 1)
                            ->with('customer')
                            ->get()->sortBy('customer.name');
                    } elseif ($request->branch_id != 0 && $request->executive_id == 0 && $request->scheme_id != 0) {
                        $customerSchemeReport = CustomerScheme::join('executives', 'customer_scheme.executive_id', '=', 'executives.id')
                            ->whereDate('customer_scheme.joining_date', $request->date)
                            ->where('customer_scheme.branch_id', $request->branch_id)
                            ->where('customer_scheme.scheme_id', $request->scheme_id)
                            ->where('executives.executive_type', 1)
                            ->with('customer')
                            ->get()->sortBy('customer.name');
                    } elseif ($request->branch_id != 0 && $request->executive_id != 0 && $request->scheme_id != 0) {
                        $customerSchemeReport = CustomerScheme::join('executives', 'customer_scheme.executive_id', '=', 'executives.id')
                            ->whereDate('customer_scheme.joining_date', $request->date)
                            ->where('customer_scheme.branch_id', $request->branch_id)
                            ->where('customer_scheme.scheme_id', $request->scheme_id)
                            ->where('customer_scheme.executive_id', $request->executive_id)
                            ->where('executives.executive_type', 1)
                            ->with('customer')
                            ->get()->sortBy('customer.name');
                    }
                }
                return DataTables::of($customerSchemeReport)
                    ->addIndexColumn()
                    ->addColumn('customer_name', function ($row) {
                        return $row->customer->name;
                    })
                    ->addColumn('customer_place', function ($row) {
                        return $row->customer->place;
                    })
                    ->addColumn('customer_phone', function ($row) {
                        return $row->customer->phone;
                    })
                    ->addColumn('collection_amount', function ($row) use ($request) {
                        $customerScheme = CustomerScheme::where('customer_id', $row->customer_id)->where('scheme_id', $row->scheme_id)->first();
                        return $customerScheme->total_amount;
                    })
                    ->addColumn('date_for_1st_payement', function ($row) {
                        $customerReport = ExecutiveReportSubmission::where('customer_id', $row->customer_id)->where('scheme_id', $row->scheme_id)->where('paid_amount', '!=', 0)->orderBy('id', 'asc')->first();
                        if ($customerReport != null) {
                            // foreach ($customerReport as $key => $value) {
                            $date = $customerReport->paid_date;
                            // }
                        } else {
                            $date = '';
                        }
                        return  $date;
                    })
                    ->addColumn('status', function ($row) {
                        $customerScheme = CustomerScheme::where('customer_id', $row->customer_id)->where('scheme_id', $row->scheme_id)->first();
                        if ($customerScheme->status == 0) {
                            $status = "Pending";
                        } elseif ($customerScheme->status == 1) {
                            $status = "Active";
                        } elseif ($customerScheme->status == 2) {
                            $status = "Completed";
                        } elseif ($customerScheme->status == 3) {
                            $status = "Lucky Winner";
                        } elseif ($customerScheme->status == 4) {
                            $status = "Stop";
                        }
                        return $status;
                    })
                    ->addColumn('marketing_executive', function ($row) {
                        $marketing_executive = Executive::where('id', $row->executive_id)->first();
                        return $row->name;
                    })
                    ->make(true);
            }
            return view('backend.reports.daily-report-by-marketing-executive', compact('branches'));
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
    public function getData(Request $request)
    {
        $data['areas']  = Area::where('branch_id', $request->branch_id)->get();
        $data['scheme']  = Scheme::where('branch_id', $request->branch_id)->get();
        return $data;
    }
    public function getExecutives(Request $request)
    {
        $executive = Executive::where('branch_id', request()->branch_id)
            ->where('collection_area_id', request()->area_id)
            ->where('executive_type', 1)->where('status', 1)->get();
        return $executive;
    }
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
