<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

class DailyBranchReportController extends Controller
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
                if(isset($request->branch_id)){

                    $branches = CustomerScheme::whereBetween('joining_date', [$request->from_date, $request->to_date])->where('branch_id',$request->branch_id)->with('branch')->get()->unique('branch');
                }
                else{
                    $branches = CustomerScheme::whereBetween('joining_date', [$request->from_date, $request->to_date])->with('branch')->get()->unique('branch');
                }
                
              //  dd($branches);
                return DataTables::of($branches)
                    ->addIndexColumn()
                    ->addColumn('branch_name', function ($row) {
                        $branch = $row->branch;
                        if(isset($branch)) {
                            return $branch->branch_id.'-'.$branch->branch_name;
                        }
                        else{
                            return '';
                        }
                    })
                    ->addColumn('branch_manager', function ($row) {
                        $manager = Manager::where('branch_id', $row->branch_id)->where('type', 1)->first();
                        return ($manager == '' ? "" : $manager->manager_id . "-" . $manager->name);
                    })
                    ->addColumn('new_joining', function ($row) use ($request) {
                        $branche = CustomerScheme::where('branch_id', $row->branch_id)->whereBetween('joining_date', [$request->from_date, $request->to_date])->get();
                        $count1pm = 0;
                        $count6pm = 0;
                        foreach ($branche as $key => $branch) {
                            $fixtime = 13;
                            $htime = $branch->created_at->format('H');
                            if ($fixtime < $htime) {
                                $count1pm += 1;
                            } else {
                                $count6pm += 1;
                            }
                        }
                        $joinCount = CustomerScheme::where('branch_id', $row->branch_id)->whereBetween('joining_date', [$request->from_date, $request->to_date])->count();
                        return $count6pm . "-" . $count1pm;
                    })
                    ->addColumn('new_joining_total_count', function ($row) use ($request) {
                        $cash_collection_new_joining_count = CustomerScheme::where('branch_id', $row->branch_id)->whereBetween('joining_date', [$request->from_date, $request->to_date])->where('total_amount', '!=', 0)->count();
                        return  $cash_collection_new_joining_count ;
                    })
                    ->addColumn('cash_collection_new_joining', function ($row) use ($request) {
                       $cash_collection_new_joining_amount = CustomerScheme::where('branch_id', $row->branch_id)->whereBetween('joining_date', [$request->from_date, $request->to_date])->sum('total_amount');
                        return  $cash_collection_new_joining_amount;
                    })
                    ->addColumn('weekly_paymt', function ($row) use ($request) {
                        $weekly_paymtCount = CustomerScheme::where('branch_id', $row->branch_id)->whereBetween('joining_date', [$request->from_date, $request->to_date])->where('total_amount', '>=', 200)->count();
                        //$weekly_paymtAmount = CustomerScheme::where('branch_id', $row->branch_id)->whereBetween('joining_date', [$request->from_date, $request->to_date])->where('total_amount', '>=', 200)->sum('total_amount');
                        return (200 * $weekly_paymtCount);
                    })
                    ->addColumn('weekly_paymt_pending', function ($row) use ($request) {
                        $weekly_paymtAmount = CustomerScheme::where('branch_id', $row->branch_id)->whereBetween('joining_date', [$request->from_date, $request->to_date])->where('total_amount', '<', 200)->sum('pending_amount');
                        return  $weekly_paymtAmount;
                    })
                    ->addColumn('weekly_paymt_advance', function ($row) use ($request) {
                        $weekly_paymtAmount = CustomerScheme::where('branch_id', $row->branch_id)->whereBetween('joining_date', [$request->from_date, $request->to_date])->where('total_amount', '>', 200)->sum('total_amount');
                        return ($weekly_paymtAmount);
                    })
                    ->addColumn('remark', function ($row) use ($request) {
                        return "";
                    })
                    //->rawColumns(['new_joining'])
                    ->make(true);
            }
            return view('backend.reports.daily-branch-report', compact('branches'));
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
