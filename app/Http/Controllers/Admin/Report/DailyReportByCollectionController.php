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
use Illuminate\Support\Facades\Auth;

class DailyReportByCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['role:super-admin|developer-admin|branch-manager|collection-executive']);
    }
    public function index(Request $request)
    {
        $branches = Branch::get();
        try {
            if ($request->ajax()) {
                //  dd($request);
                if ($request->branch_id == 0 && $request->executive_id == 0 && $request->scheme_id == 0) {
                    $myDate = $request->date;
                    $day = Carbon::createFromFormat('Y-m-d', $myDate)->format('l');
                    ##-- user Role--##
                    $user = Auth::user();
                    $userRole = $user->roles->pluck('name')->first();
                    if ($userRole == 'collection-executive') {
                        $executive = Executive::where('user_id', $user->id)->first();
                        $customers = CustomerScheme::join('customer_executives', [['customer_scheme.customer_id', '=', 'customer_executives.customer_id'], ['customer_scheme.scheme_id', '=', 'customer_executives.scheme_id']])
                            ->join('executives', 'customer_executives.executive_id', '=', 'executives.id')
                            ->where('customer_executives.executive_id', $executive->id)
                            ->where('customer_scheme.collection_day', $day)
                            ->get(['customer_scheme.*']);
                    } else {
                        $customers = CustomerScheme::join('customer_executives', [['customer_scheme.customer_id', '=', 'customer_executives.customer_id'], ['customer_scheme.scheme_id', '=', 'customer_executives.scheme_id']])
                            ->join('executives', 'customer_executives.executive_id', '=', 'executives.id')
                            ->where('customer_scheme.collection_day', $day)
                            ->get(['customer_scheme.*']);
                    }
                    return DataTables::of($customers)
                        ->addIndexColumn()
                        ->addColumn('customer_id', function ($row) {
                            return $row->customer->customer_id;
                        })
                        ->addColumn('customer_name', function ($row) {
                            return $row->customer->name;
                        })
                        ->addColumn('weekly_paymt', function ($row) {
                            $customerReport = ExecutiveReportSubmission::where('customer_id', $row->customer_id)->where('scheme_id', $row->scheme_id)->orderBy('id', 'desc')->first();
                            return $customerReport->paid_amount;
                        })
                        ->addColumn('pending_paymt', function ($row) {
                            $customerReport = ExecutiveReportSubmission::where('customer_id', $row->customer_id)->where('scheme_id', $row->scheme_id)->orderBy('id', 'desc')->first();
                            return ($customerReport == '' ? "200" : $customerReport->due_amount);
                        })
                        ->addColumn('advance_paymt', function ($row) {
                            return "";
                        })
                        ->addColumn('balance_amount', function ($row) {
                            return "";
                        })
                        ->addColumn('collection_executive', function ($row) {
                            $collection_executive = CustomerExecutive::where('customer_id', $row->customer_id)->where('scheme_id', $row->scheme_id)->first();
                            return $collection_executive->executive->name;
                        })
                        ->addColumn('last_paid_date', function ($row) {
                            $customerReport = ExecutiveReportSubmission::where('customer_id', $row->customer_id)->where('scheme_id', $row->scheme_id)->orderBy('id', 'desc')->first();
                            return ($customerReport == '' ? "" : $customerReport->paid_date);
                        })
                        ->addColumn('last_paid_scheme_date', function ($row) {
                            $customerReport = ExecutiveReportSubmission::where('customer_id', $row->customer_id)->where('scheme_id', $row->scheme_id)->where('paid_amount', '!=', 0)->orderBy('id', 'desc')->first();
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
                            return ($week_date == '' ? "" :  $week_date . "(week-" . $customerReport->paid_week . ")");
                        })
                        ->make(true);
                } elseif ($request->branch_id != 0 && $request->executive_id == 0 && $request->scheme_id == 0) {
                    $myDate = $request->date;
                    $day = Carbon::createFromFormat('Y-m-d', $myDate)->format('l');
                    $customers = CustomerScheme::join('customer_executives', [['customer_scheme.customer_id', '=', 'customer_executives.customer_id'], ['customer_scheme.scheme_id', '=', 'customer_executives.scheme_id']])
                        ->join('executives', 'customer_executives.executive_id', '=', 'executives.id')
                        ->where('customer_scheme.branch_id', $request->branch_id)
                        ->where('customer_scheme.collection_day', $day)
                        ->get(['customer_scheme.*']);
                    return DataTables::of($customers)
                        ->addIndexColumn()
                        ->addColumn('customer_id', function ($row) {
                            return $row->customer->customer_id;
                        })
                        ->addColumn('customer_name', function ($row) {
                            return $row->customer->name;
                        })
                        ->addColumn('weekly_paymt', function ($row) {
                            $customerReport = ExecutiveReportSubmission::where('customer_id', $row->customer_id)->where('scheme_id', $row->scheme_id)->orderBy('id', 'desc')->first();
                            return $customerReport->paid_amount;
                        })
                        ->addColumn('pending_paymt', function ($row) {
                            $customerReport = ExecutiveReportSubmission::where('customer_id', $row->customer_id)->where('scheme_id', $row->scheme_id)->orderBy('id', 'desc')->first();
                            return ($customerReport == '' ? "200" : $customerReport->due_amount);
                        })
                        ->addColumn('advance_paymt', function ($row) {
                            return "";
                        })
                        ->addColumn('balance_amount', function ($row) {
                            return "";
                        })
                        ->addColumn('collection_executive', function ($row) {
                            $collection_executive = CustomerExecutive::where('customer_id', $row->customer_id)->where('scheme_id', $row->scheme_id)->first();
                            return $collection_executive->executive->name;
                        })
                        ->addColumn('last_paid_date', function ($row) {
                            $customerReport = ExecutiveReportSubmission::where('customer_id', $row->customer_id)->where('scheme_id', $row->scheme_id)->orderBy('id', 'desc')->first();
                            return ($customerReport == '' ? "" : $customerReport->paid_date);
                        })
                        ->addColumn('last_paid_scheme_date', function ($row) {
                            $customerReport = ExecutiveReportSubmission::where('customer_id', $row->customer_id)->where('scheme_id', $row->scheme_id)->where('paid_amount', '!=', 0)->orderBy('id', 'desc')->first();
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
                            return ($week_date == '' ? "" :  $week_date . "(week-" . $customerReport->paid_week . ")");
                        })
                        ->make(true);
                } else {
                    if ($request->branch_id != 0 && $request->executive_id == 0 && $request->scheme_id != 0) {
                        $myDate = $request->date;
                        $day = Carbon::createFromFormat('Y-m-d', $myDate)->format('l');
                        $customers = CustomerScheme::join('customer_executives', [['customer_scheme.customer_id', '=', 'customer_executives.customer_id'], ['customer_scheme.scheme_id', '=', 'customer_executives.scheme_id']])
                            ->where('customer_scheme.scheme_id', $request->scheme_id)
                            ->where('customer_scheme.branch_id', $request->branch_id)
                            ->where('customer_scheme.collection_day', $day)
                            ->with('customer')
                            ->get();
                    } elseif ($request->branch_id != 0 && $request->executive_id != 0 && $request->scheme_id != 0) {
                        $myDate = $request->date;
                        $day = Carbon::createFromFormat('Y-m-d', $myDate)->format('l');
                        $customers = CustomerScheme::join('customer_executives', [['customer_scheme.customer_id', '=', 'customer_executives.customer_id'], ['customer_scheme.scheme_id', '=', 'customer_executives.scheme_id']])
                            ->where('customer_executives.executive_id', $request->executive_id)
                            ->where('customer_scheme.scheme_id', $request->scheme_id)
                            ->where('customer_scheme.branch_id', $request->branch_id)
                            ->where('customer_scheme.collection_day', $day)
                            ->with('customer')
                            ->get();
                    }
                    return DataTables::of($customers)
                        ->addIndexColumn()
                        ->addColumn('customer_id', function ($row) {
                            return $row->customer->customer_id;
                        })
                        ->addColumn('customer_name', function ($row) {
                            return $row->customer->name;
                        })
                        ->addColumn('weekly_paymt', function ($row) use ($request) {
                            $customerReport = ExecutiveReportSubmission::where('customer_id', $row->customer_id)->where('scheme_id', $request->scheme_id)->orderBy('id', 'desc')->first();
                            return $customerReport->paid_amount;
                        })
                        ->addColumn('pending_paymt', function ($row) use ($request) {
                            $customerReport = ExecutiveReportSubmission::where('customer_id', $row->customer_id)->where('scheme_id', $request->scheme_id)->orderBy('id', 'desc')->first();
                            return ($customerReport == '' ? "200" : $customerReport->due_amount);
                        })
                        ->addColumn('advance_paymt', function ($row) use ($request) {
                            return "";
                        })
                        ->addColumn('balance_amount', function ($row) use ($request) {
                            return "";
                        })
                        ->addColumn('collection_executive', function ($row) {
                            $collection_executive = CustomerExecutive::where('customer_id', $row->customer_id)->where('scheme_id', $row->scheme_id)->first();
                            return $collection_executive->executive->name;
                        })
                        ->addColumn('last_paid_date', function ($row) use ($request) {
                            $customerReport = ExecutiveReportSubmission::where('customer_id', $row->customer_id)->where('scheme_id', $request->scheme_id)->orderBy('id', 'desc')->first();
                            return ($customerReport == '' ? "" : $customerReport->paid_date);
                        })
                        ->addColumn('last_paid_scheme_date', function ($row) use ($request) {
                            $customerReport = ExecutiveReportSubmission::where('customer_id', $row->customer_id)->where('scheme_id', $request->scheme_id)->where('paid_amount', '!=', 0)->orderBy('id', 'desc')->first();
                            if ($customerReport != null) {
                                $scheme = Scheme::where('id', $request->scheme_id)->first();
                                $date = strtotime($scheme->start_date);
                                $days = (7 * $customerReport->paid_week);
                                $date = strtotime("+$days day", $date);
                                $data[] = date('Y-m-d', $date);
                                $week_date = date('Y-m-d', $date);
                            } else {
                                $week_date = "";
                            }
                            return ($week_date == '' ? "" :  $week_date . "(week-" . $customerReport->paid_week . ")");
                        })
                        ->make(true);
                }
                // dd($customers);
            }
            return view('backend.reports.daily-report-by-collection-executive', compact('branches'));
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
            ->where('executive_type', 2)->where('status', 1)->get();
        return $executive;
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
