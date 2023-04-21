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
use App\Models\Master\Manager;
use App\Models\Scheme;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class DailybranchSchemeReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['role:super-admin|developer-admin|branch-manager|collection-manager']);
    }
    public function index(Request $request)
    {
        $branches = Branch::get();
        try {
            if ($request->ajax()) {
                if ($request->branch_id == 0 && $request->scheme_id == 0) {
                    $ExecutiveJoin = CustomerScheme::whereBetween('joining_date', [$request->from_date, $request->to_date])->get()->unique('executive_id', 'scheme_id', 'branch_id');
                } elseif ($request->branch_id != 0 && $request->scheme_id == 0) {
                    ##-- user Role--##
                    $user = Auth::user();
                    $userRole = $user->roles->pluck('name')->first();
                    if ($userRole == 'collection-manager') {
                        $manager = Manager::where('user_id', $user->id)->first();
                        $ExecutiveJoin = CustomerScheme::join('executives', 'customer_scheme.executive_id', '=', 'executives.id')
                            ->where('executives.manager_id', $manager->id)
                            ->where('customer_scheme.branch_id', $request->branch_id)
                            ->whereBetween('customer_scheme.joining_date', [$request->from_date, $request->to_date])
                            ->get('customer_scheme.*')->unique('executive_id');
                    } else {
                        $ExecutiveJoin = CustomerScheme::where('branch_id', $request->branch_id)->whereBetween('joining_date', [$request->from_date, $request->to_date])->get()->unique('executive_id');
                    }
                } elseif ($request->branch_id != 0 && $request->scheme_id != 0) {
                    $user = Auth::user();
                    $userRole = $user->roles->pluck('name')->first();
                    if ($userRole == 'collection-manager') {
                        $manager = Manager::where('user_id', $user->id)->first();
                        $ExecutiveJoin = CustomerScheme::join('executives', 'customer_scheme.executive_id', '=', 'executives.id')
                            ->where('executives.manager_id', $manager->id)
                            ->where('customer_scheme.branch_id', $request->branch_id)
                            ->where('customer_scheme.scheme_id', $request->scheme_id)
                            ->whereBetween('customer_scheme.joining_date', [$request->from_date, $request->to_date])
                            ->get('customer_scheme.*')->unique('executive_id');
                    } else {
                        $ExecutiveJoin = CustomerScheme::where('branch_id', $request->branch_id)->where('scheme_id', $request->scheme_id)->whereBetween('joining_date', [$request->from_date, $request->to_date])->get()->unique('executive_id');
                    }
                }
                return DataTables::of($ExecutiveJoin)
                    ->addIndexColumn()
                    ->addColumn('executive_name', function ($row) use ($request) {
                        if ($row->executive_id == NULL) {
                            $executive_name = "";
                        } else {
                            $executive = Executive::where('id', $row->executive_id)->first();
                            $executive_name = $executive->executive_id . "-" . $executive->name;
                        }
                        return "$executive_name";
                    })
                    ->addColumn('new_joining', function ($row) use ($request) {
                        $joinCount = CustomerScheme::where('branch_id', $row->branch_id)->where('scheme_id', $row->scheme_id)->whereBetween('joining_date', [$request->from_date, $request->to_date])->where('executive_id', $row->executive_id)->count();
                        return $joinCount;
                    })
                    ->addColumn('cash_collection', function ($row) use ($request) {
                       $total = CustomerScheme::where('branch_id', $row->branch_id)->where('scheme_id', $row->scheme_id)->whereBetween('joining_date', [$request->from_date, $request->to_date])->where('executive_id', $row->executive_id)->sum('total_amount');
                        return $total;
                    })
                    ->addColumn('weekly_pytm', function ($row) use ($request) {
                        $joinCount = CustomerScheme::where('branch_id', $row->branch_id)->where('scheme_id', $row->scheme_id)->whereBetween('joining_date', [$request->from_date, $request->to_date])->where('executive_id', $row->executive_id)->where('total_amount', '>=', 200)->count();
                        return  ($joinCount * 200);
                    })
                    ->addColumn('pending_pytm', function ($row) use ($request) {
                        $joinpendingCount = CustomerScheme::where('branch_id', $row->branch_id)->where('scheme_id', $row->scheme_id)->whereBetween('joining_date', [$request->from_date, $request->to_date])->where('executive_id', $row->executive_id)->where('total_amount', '<', 200)->count();
                        $pending_total = CustomerScheme::where('branch_id', $row->branch_id)->where('scheme_id', $row->scheme_id)->whereBetween('joining_date', [$request->from_date, $request->to_date])->where('executive_id', $row->executive_id)->where('total_amount', '<', 200)->sum('pending_amount');
                        return  ($joinpendingCount == 0 ? "0" : $pending_total);
                    })
                    ->addColumn('advance_pytm', function ($row) use ($request) {
                        $joinadvance_pytmCount = CustomerScheme::where('branch_id', $row->branch_id)->where('scheme_id', $row->scheme_id)->whereBetween('joining_date', [$request->from_date, $request->to_date])->where('executive_id', $row->executive_id)->where('total_amount', '>', 200)->count();
                        $joinadvance_pytmTotal = CustomerScheme::where('branch_id', $row->branch_id)->where('scheme_id', $row->scheme_id)->whereBetween('joining_date', [$request->from_date, $request->to_date])->where('executive_id', $row->executive_id)->where('total_amount', '>', 200)->sum('total_amount');
                        return $joinadvance_pytmTotal - ($joinadvance_pytmCount * 200);
                    })
                    ->make(true);
            }
            return view('backend.reports.branch-daily-report-by-branch-manager', compact('branches'));
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
