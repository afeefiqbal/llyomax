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
use App\Models\Scheme;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class SchemeReportController extends Controller
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
                if ($request->branch_id == 0) {
                    $schemes = CustomerScheme::whereBetween('joining_date', [$request->from_date,$request->to_date])->with('scheme')->get()->unique('scheme_id');
                } else {
                    $schemes = CustomerScheme::where('branch_id', $request->branch_id)->whereBetween('joining_date', [$request->from_date,$request->to_date])->with('scheme')->get()->unique('scheme_id');
                }
                return DataTables::of($schemes)
                    ->addIndexColumn()
                    ->addColumn('scheme_name', function ($row) {
                        return $row->scheme->scheme_id . "-" . $row->scheme->name;
                    })
                    ->addColumn('new_joining', function ($row) use ($request) {
                        $joinCount = CustomerScheme::where('scheme_id', $row->scheme->id)->whereBetween('joining_date', [$request->from_date,$request->to_date])->count();

                        return $joinCount;
                    })
                    ->addColumn('new_joining_payment', function ($row) use ($request) {
                        $joinAmount = CustomerScheme::where('scheme_id', $row->scheme->id)->whereBetween('joining_date', [$request->from_date,$request->to_date])->sum('total_amount');
                        return  $joinAmount;
                    })
                    ->addColumn('new_joining_with_paymt', function ($row) use ($request) {
                        $joinwithpayCount = CustomerScheme::where('scheme_id', $row->scheme->id)->whereBetween('joining_date', [$request->from_date,$request->to_date])->where('total_amount', '!=', 0)->count();
                        $joinwithpayAmount = CustomerScheme::where('scheme_id', $row->scheme->id)->whereBetween('joining_date', [$request->from_date,$request->to_date])->where('total_amount', '!=', 0)->sum('total_amount');
                        return (200 * $joinwithpayCount);
                    })
                    ->addColumn('new_joining_without_paymt', function ($row) use ($request) {
                       $joinwithoutpayAmont = CustomerScheme::where('scheme_id', $row->scheme->id)->whereBetween('joining_date', [$request->from_date,$request->to_date])->where('total_amount', 0)->sum('pending_amount');
                        return $joinwithoutpayAmont;
                    })
                    ->addColumn('new_joining_advance_paymt', function ($row) use ($request) {
                        $joinadvancepayCount = CustomerScheme::where('scheme_id', $row->scheme->id)->whereBetween('joining_date', [$request->from_date,$request->to_date])->where('total_amount', '>', 200)->count();
                        $joinadvancepayAmt = CustomerScheme::where('scheme_id', $row->scheme->id)->whereBetween('joining_date', [$request->from_date,$request->to_date])->where('total_amount', '>', 200)->sum('total_amount');
                        return (($joinadvancepayAmt)-($joinadvancepayCount * 200) );
                    })
                    ->addColumn('cash_collection', function ($row) use ($request) {
                        if ($request->branch_id == 0) {
                            $request->branch_id = $row->branch_id;
                        }
                        $Amount = CustomerScheme::where('scheme_id', $row->scheme->id)->whereBetween('joining_date', [$request->from_date,$request->to_date])->sum('total_amount');
                        // $amount = CustomerScheme::where('branch_id', $request->branch_id)->where('scheme_id', $row->scheme->id)->whereBetween('joining_date', [$request->from_date,$request->to_date])->sum('total_amount');
                        return $Amount;
                    })
                    ->rawColumns(['new_joining'])
                    ->make(true);
            }
            return view('backend.reports.scheme-report-by-branch', compact('branches'));
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
