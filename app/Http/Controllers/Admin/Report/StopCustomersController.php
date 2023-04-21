<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\CustomerScheme;
use Illuminate\Http\Request;
use App\Models\Executive\Executive;
use App\Models\Executive\ExecutiveReportSubmission;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\Master\Branch;
use App\Models\Master\Manager;
use Yajra\DataTables\DataTables;
use App\Models\Master\Area;
use App\Models\Scheme;

class StopCustomersController extends Controller
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
        $branches = Branch::get();
        try {
            if ($request->ajax()) {
                if ($request->branch_id == 0 && $request->scheme_id == 0) {
                    $customers = CustomerScheme::where('status', 4)->with(['customer', 'scheme', 'branch'])->get();
                } elseif ($request->branch_id != 0 && $request->scheme_id == 0) {
                    $customers = CustomerScheme::where('branch_id', $request->branch_id)->where('status', 4)->with(['customer', 'scheme', 'branch'])->get();
                }elseif ($request->branch_id != 0 && $request->scheme_id != 0) {
                    $customers = CustomerScheme::where('branch_id', $request->branch_id)->where('scheme_id', $request->scheme_id)->where('status', 4)->with(['customer', 'scheme', 'branch'])->get();
                }
                return DataTables::of($customers)
                    ->addIndexColumn()
                    ->addColumn('customer_id', function ($row) {
                        return $row->customer->id;
                    })
                    ->addColumn('customer_name', function ($row) {
                        return $row->customer->name;
                    })
                    ->addColumn('customer_phone', function ($row) {
                        return $row->customer->phone;
                    })
                    ->addColumn('branch', function ($row) {
                        return $row->branch->branch_name;
                    })
                    ->addColumn('scheme', function ($row) {
                        return $row->scheme->name;
                    })
                    ->addColumn('total_amount', function ($row) {
                        $total = ExecutiveReportSubmission::where('branch_id', $row->branch_id)->where('scheme_id', $row->scheme_id)->where('customer_id', $row->customer_id)->sum('paid_amount');
                        return $total;
                    })
                    ->addColumn('balance_amount', function ($row) {
                        $total = ExecutiveReportSubmission::where('branch_id', $row->branch_id)->where('scheme_id', $row->scheme_id)->where('customer_id', $row->customer_id)->sum('paid_amount');
                        return (6000 - $total);
                    })
                    ->addColumn('week', function ($row) {
                        $customerReport = ExecutiveReportSubmission::where('branch_id', $row->branch_id)->where('scheme_id', $row->scheme_id)->where('customer_id', $row->customer_id)->orderBy('id', 'desc')->first();
                        if ($customerReport != null) {
                            $scheme = Scheme::where('id', $row->scheme_id)->first();
                            $date = strtotime($scheme->start_date);
                            $days = (7 * $customerReport->paid_week);
                            $date = strtotime("+$days day", $date);
                            $data[] = date('Y-m-d', $date);
                            $week_date = date('Y-m-d', $date);
                        } else {
                            $week_date = "";
                        }
                        return ($week_date == '' ? "" :  $week_date . "(" . $customerReport->paid_week . "th-week)");
                    })
                    ->make(true);
            }
            return view('backend.reports.stop-customers-list', compact('branches'));
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
