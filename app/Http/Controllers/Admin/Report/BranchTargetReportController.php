<?php

namespace App\Http\Controllers\admin\Report;

use App\Http\Controllers\Controller;
use App\Models\BranchTarget;
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

class BranchTargetReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $branches = Branch::get();
        try {
            if ($request->ajax()) {
                $branchScheme = CustomerScheme::where('branch_id', $request->branch_id)->whereBetween('joining_date', [$request->from_date, $request->to_date])->with('scheme')->get()->unique('scheme_id');
                return DataTables::of($branchScheme)
                    ->addIndexColumn()
                    ->addColumn('scheme_name', function ($row) {
                        return $row->scheme->name;
                    })
                    ->addColumn('joining_date', function ($row) use ($request) {
                        return $request->from_date . " to " . $request->to_date;
                    })
                    ->addColumn('target', function ($row) use ($request) {
                        $branchtarget = BranchTarget::where('branch_id', $request->branch_id)->where('scheme_id', $row->scheme_id)->first();
                        return ($branchtarget == null ? "" : $branchtarget->target_per_month);
                    })
                    ->addColumn('achieved', function ($row) use ($request) {
                        $achievedTargetCount = CustomerScheme::where('branch_id', $request->branch_id)->where('scheme_id', $row->scheme_id)->whereBetween('joining_date', [$request->from_date, $request->to_date])->count();
                        return $achievedTargetCount;
                    })
                    ->addColumn('to_achieved', function ($row) use ($request) {
                        $branchtarget = BranchTarget::where('branch_id', $request->branch_id)->where('scheme_id', $row->scheme_id)->first();
                        $achievedTargetCount = CustomerScheme::where('branch_id', $request->branch_id)->where('scheme_id', $row->scheme_id)->whereBetween('joining_date', [$request->from_date, $request->to_date])->count();
                       if ($branchtarget != null) {
                        $to_achieved = ($branchtarget->target_per_month) - $achievedTargetCount;
                       } else {
                        $to_achieved = "";
                       }

                        return $to_achieved ;
                    })
                    ->addColumn('remark', function ($row) {
                        return "";
                    })
                    ->make(true);
            }
            return view('backend.reports.branch-target-report', compact('branches'));
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
