<?php

namespace App\Http\Controllers\admin\Report;

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

class CashCollectionController extends Controller
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
                if ($request->branch_id == 0 && $request->scheme_id == 0) {
                    $customers = CustomerScheme::with('customer')->get()->sortBy('customer.name');
                } elseif ($request->branch_id != 0 && $request->scheme_id != 0) {
                    $customers = CustomerScheme::where('branch_id', $request->branch_id)
                        ->where('scheme_id', $request->scheme_id)
                        ->with('customer')
                        ->get()
                        ->sortBy('customer.name');
                } elseif ($request->branch_id != 0 && $request->scheme_id == 0) {
                    $customers = CustomerScheme::where('branch_id', $request->branch_id)
                        ->with('customer')
                        ->get()
                        ->sortBy('customer.name');
                }
                return DataTables::of($customers)
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
                    ->addColumn('status', function ($row) {
                        if ($row->status == 0) {
                            $status = "Pending";
                        } elseif ($row->status == 1) {
                            $status = "Active";
                        } elseif ($row->status == 2) {
                            $status = "Completed";
                        } elseif ($row->status == 3) {
                            $status = "Lucky Winner";
                        } elseif ($row->status == 4) {
                            $status = "Stop";
                        }
                        return $status;
                    })
                    ->addColumn('amount', function ($row) {
                        return $row->total_amount;
                    })
                    ->addColumn('date', function ($row) {
                        return $row->joining_date;
                    })
                    ->make(true);
            }
            return view('backend.reports.cash-collection-by-marketing-executive', compact('branches'));
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
        $data['scheme']  = Scheme::where('branch_id', $request->branch_id)->get();
        return $data;
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
