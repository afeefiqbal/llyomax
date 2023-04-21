<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BranchTarget;
use App\Models\Customer;
use App\Models\CustomerScheme;
use App\Models\Executive\Executive;
use App\Models\Executive\ExecutiveReportSubmission;
use App\Models\MarketingExecutiveTarget;
use App\Models\Master\Area;
use App\Models\Master\Branch;
use App\Models\Master\Manager;
use App\Models\Scheme;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class MarketingExecutiveTargetReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['role:super-admin|developer-admin|branch-manager|marketing-manager |collection-manager']);
    }
    public function index(Request $request)
    {
        $branches = Branch::get();
        function dateDiff($date1, $date2)
        {
            $date1_ts = strtotime($date1);
            $date2_ts = strtotime($date2);
            $diff = $date2_ts - $date1_ts;
            return round($diff / 86400);
        }
        try {
            if ($request->ajax()) {
                if ($request->branch_id == 0) {
                    $customerScheme = CustomerScheme::join('executives', 'customer_scheme.executive_id', '=', 'executives.id')
                        ->whereBetween('joining_date', [$request->from_date, $request->to_date])
                        ->where('executives.executive_type', 1)
                        ->get(['customer_scheme.*'])
                        ->unique('executive_id');
                } else {
                    $customerScheme = CustomerScheme::join('executives', 'customer_scheme.executive_id', '=', 'executives.id')
                        ->whereBetween('joining_date', [$request->from_date, $request->to_date])
                        ->where('executives.executive_type', 1)
                        ->where('customer_scheme.branch_id', $request->branch_id)
                        ->get(['customer_scheme.*'])
                        ->unique('executive_id');
                }
                return DataTables::of($customerScheme)
                    ->addIndexColumn()
                    ->addColumn('executive_name', function ($row) {
                        $executive = Executive::where('id', $row->executive_id)->first();
                        return $executive->name;
                    })
                    ->addColumn('executive_branch', function ($row) {
                        $executive = Executive::where('id', $row->executive_id)->with('branch')->first();
                        return $executive->branch->branch_name;
                    })
                    ->addColumn('target', function ($row) use ($request) {
                        $target = MarketingExecutiveTarget::where('executive_id', $row->executive_id)->first();
                        if ($target != null) {
                            if ($request->from_date == $request->to_date) {
                                $targetexecutive = $target->target_per_day;
                            } else {
                                $dateDiff = dateDiff($request->from_date, $request->to_date);
                                $targetexecutive = $dateDiff * $target->target_per_day;
                            }
                        } else {
                            $targetexecutive = "";
                        }
                        return $targetexecutive;
                    })
                    ->addColumn('achieved', function ($row) use ($request) {
                        $exectivetarget = CustomerScheme::where('executive_id', $row->executive_id)->whereBetween('joining_date', [$request->from_date, $request->to_date])->count();
                        return ($exectivetarget != null ? $exectivetarget : "");
                    })
                    ->addColumn('to_achieved', function ($row) use ($request) {
                        $target = MarketingExecutiveTarget::where('executive_id', $row->executive_id)->first();
                        $exectivetarget = CustomerScheme::where('executive_id', $row->executive_id)->whereBetween('joining_date', [$request->from_date, $request->to_date])->count();
                        if ($target != null) {
                            if ($request->from_date == $request->to_date) {
                                $targetexecutive = $target->target_per_day;
                                $to_achieved = $targetexecutive - $exectivetarget;
                            } else {
                                $dateDiff = dateDiff($request->from_date, $request->to_date);
                                $targetexecutive = $dateDiff * $target->target_per_day;
                                $to_achieved = $targetexecutive - $exectivetarget;
                            }
                        } else {
                            $to_achieved = "";
                        }
                        return $to_achieved;
                    })
                    ->addColumn('remark', function ($row) {
                        return "";
                    })
                    ->make(true);
            }
            return view('backend.reports.marketing-executive-target-report', compact('branches'));
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
