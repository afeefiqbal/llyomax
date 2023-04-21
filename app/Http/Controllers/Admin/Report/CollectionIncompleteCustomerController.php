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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class CollectionIncompleteCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['role:super-admin|developer-admin|branch-manager|store-admin']);
    }
    public function index(Request $request)
    {

        $branches = Branch::get();
        try {
            if (
                $request->ajax()) { $user = Auth::user();
                $userRole = $user->roles->pluck('name')->first();
                if ($userRole == 'super-admin') {
                    if ($request->branch_id == 0 && $request->scheme_id == 0 && $request->week == null) {
                        $customers = CustomerScheme::where('total_amount', '<', 6000)->whereIn('status', [0, 1])
                            ->get();

                    } elseif ($request->branch_id != 0 && $request->scheme_id == 0 && $request->week == null) {
                        $customers = CustomerScheme::where('branch_id', $request->branch_id)->where('total_amount', '<', 6000)->whereIn('status', [0, 1])
                            ->get();
                    } elseif ($request->branch_id != 0 && $request->scheme_id != 0 && $request->week == null) {
                        $customers = CustomerScheme::where('branch_id', $request->branch_id)->where('scheme_id', $request->scheme_id)->where('total_amount', '<', 6000)->whereIn('status', [0, 1])
                            ->get();
                    } elseif ($request->branch_id != 0 && $request->scheme_id != 0 && $request->week != null) {
                        $customerReport = ExecutiveReportSubmission::where('branch_id', $request->branch_id)->where('scheme_id', $request->scheme_id)->where('paid_week', ($request->week + 1))->where('paid_amount', '!=', 0)->pluck('customer_id')->toArray();
                        $customers = CustomerScheme::where('branch_id', $request->branch_id)
                            ->where('scheme_id', $request->scheme_id)
                            ->whereIn('status', [0, 1])
                            ->whereNotIn('customer_id', $customerReport)
                            ->get();
                    }
                    else{
                        $customers = CustomerScheme::where('status', 2);
                        $customers = $customers->get();
                    }
                }
                elseif($userRole == 'branch-manager'){
                    $manager = Manager::where('user_id', $user->id)->first();
                    if ($request->branch_id == 0 && $request->scheme_id == 0 && $request->week == null) {
                        $customers = CustomerScheme::where('total_amount', '<', 6000)->whereIn('status', [0, 1])
                            ->get();

                    } elseif ($request->branch_id != 0 && $request->scheme_id == 0 && $request->week == null) {
                        $customers = CustomerScheme::where('branch_id', $manager->branch_id)->where('total_amount', '<', 6000)->whereIn('status', [0, 1])
                            ->get();
                    } elseif ($request->branch_id != 0 && $request->scheme_id != 0 && $request->week == null) {
                        $customers = CustomerScheme::where('branch_id', $manager->branch_id)->where('scheme_id', $request->scheme_id)->where('total_amount', '<', 6000)->whereIn('status', [0, 1])
                            ->get();
                    } elseif ($request->branch_id != 0 && $request->scheme_id != 0 && $request->week != null) {
                        $customerReport = ExecutiveReportSubmission::where('branch_id', $manager->branch_id)->where('scheme_id', $request->scheme_id)->where('paid_week', ($request->week + 1))->where('paid_amount', '!=', 0)->pluck('customer_id')->toArray();
                        $customers = CustomerScheme::where('branch_id', $manager->branch_id)
                            ->where('scheme_id', $request->scheme_id)
                            ->whereIn('status', [0, 1])
                            ->whereNotIn('customer_id', $customerReport)
                            ->get();
                    }
                    else{
                        $customers = CustomerScheme::where('status', 2);
                        $customers = $customers->get();
                    }
                }
                return DataTables::of($customers)
                    ->addIndexColumn()
                    ->addColumn('customer_id', function ($row) {
                        return $row->customer->customer_id;
                    })
                    ->addColumn('customer_name', function ($row) {
                        return $row->customer->name;
                    })
                    ->addColumn('customer_place', function ($row) {
                        return $row->customer->place;
                    })
                    ->addColumn('customer_phone', function ($row) {
                        return $row->customer->phone;
                    })
                    ->addColumn('reason', function ($row) {
                        return "";
                    })
                    ->addColumn('scheme_id', function ($row) {
                        $schemes = Scheme::where('id',$row->scheme_id)->first();
                        return $schemes->scheme_a_id.'-'.$schemes->scheme_n_id.'-'.$schemes->name;
                    })
                    ->addColumn('branch_id', function ($row) {
                        $branches = Branch::where('id',$row->branch_id)->first();
                        return $branches->branch_id.'-'.$branches->branch_name;

                    })
                    ->addColumn('executive', function ($row) {
                        $executive = CustomerExecutive::where('customer_id', $row->customer_id)->where('scheme_id', $row->scheme_id)->with('executive')->first();
                        return ($executive != '' ? $executive->executive->name : "");
                    })
                    ->make(true);
            }
            return view('backend.reports.collection-incomplete-customers-weekly', compact('branches'));
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
        $data['scheme']  = Scheme::where('status', 1)->get();
        return $data;
    }
    public function getSchemeDate(Request $request)
    {
        // dd($request);
        $data = [];
        $scheme = Scheme::where('id', $request->scheme_id)->first();
        $start_date = $scheme->start_date;
        $data[] =  $start_date . "(1-week)";
        for ($i = 1; $i < 30; $i++) {
            $date = strtotime($start_date);
            $date = strtotime("+7 day", $date);
            $data[] = date('Y-m-d', $date) . "(" . ($i + 1) . "-week)";
            $start_date = date('Y-m-d', $date);
        }
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
