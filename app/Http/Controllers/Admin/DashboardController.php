<?php

namespace App\Http\Controllers\Admin;

use App\Charts\BranchTargetChart;
use App\Charts\Chart1;
use Carbon\Carbon;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BranchTarget;
use App\Models\CustomerScheme;
use App\Models\Executive\Executive;
use App\Models\Executive\ExecutiveReportSubmission;
use App\Models\Master\Branch;
use App\Models\Master\Manager;
use App\Models\Office_admin\Attendance;
use App\Models\Office_admin\Staff;
use App\Models\Scheme;
use App\Models\User;
use App\Models\Warehouse\Order;
use App\Models\Warehouse\Product;
use Helper;
use Illuminate\Support\Facades\Auth;
use DB;

class DashboardController extends Controller
{
    protected $chart;
    public function __construct(BranchTargetChart $chart, Chart1 $chart1){
        $this->chart = $chart;
        $this->chart1 = $chart1;
    }
    public function index()
    {
        $branches = Branch::get();
        $user = Auth::user();
        $userRole = $user->roles->pluck('name')->first();
        if ($userRole == 'branch-manager') {

            $user = auth()->user();
            $manager = Manager::where('user_id', $user->id)->first();
            $data['todayCustomer'] = Customer::where('branch_id', $manager->branch_id)->whereDate('created_at', Carbon::today())->count();
            $data['totalExecutives'] = Executive::where('branch_id', $manager->branch_id)->count();
            $data['todayCollection'] = ExecutiveReportSubmission::where('branch_id', $manager->branch_id)->where('paid_date', Carbon::today())->sum('paid_amount');
            $data['totalCollection'] = ExecutiveReportSubmission::where('branch_id', $manager->branch_id)->sum('paid_amount');
            $data['ce'] = Executive::where('executive_type',2)->where('branch_id',$manager->branch_id)->where('status',1)->count();
            $data['me'] = Executive::where('executive_type',1)->where('status',1)->count();
        } elseif ($userRole == 'super-admin' || $userRole == 'developer-admin' || $userRole == 'marketing-manager' || $userRole == 'collection-manager') {
            $data['todayCustomer'] = Customer::whereDate('created_at', Carbon::today())->count();
            $data['ce'] = Executive::where('executive_type',2)->where('status',1)->count();
            $data['me'] = Executive::where('executive_type',1)->where('status',1)->count();
            $data['totalExecutives'] = Executive::count();
            $data['todayCollection'] = ExecutiveReportSubmission::where('paid_date', Carbon::today())->sum('paid_amount');
            $data['totalCollection'] = ExecutiveReportSubmission::sum('paid_amount');
        } elseif ($userRole == 'collection-executive' || $userRole == 'marketing-executive') {
            $executive = Executive::where('user_id', $user->id)->first();
            $data['todayCustomer'] = Customer::where('executive_id',$executive->id)->whereDate('created_at', Carbon::today())->count();
            $data['totalExecutives'] = Executive::count();
            $data['ce'] = Executive::where('executive_type',2)->where('status',1)->count();
            $data['me'] = Executive::where('executive_type',1)->where('status',1)->count();
            $data['todayCollection'] = ExecutiveReportSubmission::where('executive_id',$executive->id)->where('paid_date', Carbon::today())->sum('paid_amount');
            $data['totalCollection'] = ExecutiveReportSubmission::where('executive_id',$executive->id)->sum('paid_amount');
        }
       else if($userRole == 'store-admin'){
        $data['totalCollection'] = ExecutiveReportSubmission::sum('paid_amount');
        $data['products'] = Product::where('status',1)->count();
        $data['customers'] = Customer::count();
        $data['totalOrders'] = Order::where('status',1)->count();
        $data['todayOrders'] = Order::where('status',1)->whereDate('created_at', Carbon::today())->count();

       }
        // }
        else {
            $columnChartData = "";
            return view('backend.dashboard',['chart1' => $this->chart1->build()],compact('columnChartData'));
        }
        $customerSchemeBranch = CustomerScheme::get()->unique('branch_id');
        $columnChartData = "";
        foreach ($customerSchemeBranch as $key => $custbranch) {
            $color =  '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
            $branch = Branch::where('id', $custbranch->branch_id)->first();
            $count = CustomerScheme::where('branch_id', $custbranch->branch_id)->get()->unique('customer_id', 'scheme_id',)->count();
            $branchID = $branch->branch_id ?? '';
            $columnChartData .= "['" .     $branchID . "'," . $count . ", '$color'],";
        }
        $columnChartData = rtrim($columnChartData, ",");

        return view('backend.dashboard',['chart1' => $this->chart1->build()]
        ,compact('data', 'branches', 'columnChartData'));
    }
    public function getWeekDate($scheme_start_date, $days)
    {
        $date = strtotime($scheme_start_date);
        $date = strtotime("+$days day", $date);
        $paid_date = date('Y-m-d', $date);
        return $paid_date;
    }
    public function getCollectionData(Request $request)
    {
        $day = Carbon::today()->format('l');
        $pendingcount = 0;
        $collectedCount = 0;
        $totalCount = 0;
        if ($request->branch_id == 0) {
            //0 -> pending , 1->active, 2 -> completed , 3->lucky , 4->closed
            $customeScheme = CustomerScheme::where('collection_day', $day)->whereIn('status', [0, 1])->get();
        } else {
            //0 -> pending , 1->active, 2 -> completed , 3->lucky , 4->closed
            $customeScheme = CustomerScheme::where('branch_id', $request->branch_id)->where('collection_day', $day)->whereIn('status', [0, 1])->get();
        }
        foreach ($customeScheme as $key => $custScheme) {
            $schemeReport = ExecutiveReportSubmission::where('customer_id', $custScheme->customer_id)->where('scheme_id', $custScheme->scheme_id)->orderBy('id', 'desc')->first();
            $scheme = Scheme::where('id', $schemeReport->scheme_id)->first();
            if ($schemeReport != '') {
                $report_lastweek = $schemeReport->paid_week;
                if ($report_lastweek  == 1) {
                    $report_lastweek_date = $scheme->start_date;
                } else {
                    $last_week_no = (7 * $schemeReport->paid_week) - 7;
                    $report_lastweek_date = $this->getWeekDate($scheme->start_date, $last_week_no);
                }
                $next_week_no = 7 * $schemeReport->paid_week;
                $next_week_date = $this->getWeekDate($scheme->start_date, $next_week_no);
                $currentDate = date('Y-m-d');
                $currentDate = date('Y-m-d', strtotime($currentDate));
                if (($currentDate >= $report_lastweek_date) && ($currentDate <= $next_week_date)) {
                    /**
                     * --------------------------------------------------------------------------
                     *  Current date is between two dates
                     * ---------------------------------------------------------------------------
                     */
                    $totalCount += 1;
                    if ($schemeReport->due_amount == 0) {
                        $collectedCount += 1;
                    } else {
                        $pendingcount += 1;
                    }
                }
            }
        }
        if ($totalCount != 0) {
            $arrValues =  [
                ['status', 'Collection'],
                ['total',     ($totalCount * 200)],
                ['pending',      ($pendingcount * 200)],
                ['collected',  ($collectedCount * 200)]
            ];
        } else {
            $arrValues =  [['status', 'Collection']];
        }
        return $arrValues;
    }
    public function getAttendenceData(Request $request)
    {
        if ($request->branch_id == 0) {
            $totalstaff = Staff::count();
            $presentStaff = Attendance::where('date', Carbon::today())->where('attendance', 1)->count();
            $absentstaff = $totalstaff - $presentStaff;
            if ($totalstaff != 0) {
                $arrValues =  [
                    ['Branch', 'attendence'],
                    ['Present', $presentStaff],
                    ['Absent',  $absentstaff],
                ];
            } else {
                $arrValues =  [['Branch', 'attendence']];
            }
        } else {
            $totalstaff = Staff::where('branch_id', $request->branch_id)->count();
            $presentStaff = Attendance::where('branch_id', $request->branch_id)->where('date', Carbon::today())->where('attendance', 1)->count();
            $absentstaff = $totalstaff - $presentStaff;
            if ($totalstaff != 0) {
                $arrValues =  [
                    ['Branch', 'attendence'],
                    ['Present', $presentStaff],
                    ['Absent',  $absentstaff],
                ];
            } else {
                $arrValues =  [['Branch', 'attendence']];
            }
        }
        return $arrValues;
    }
    public function getSchemeData(Request $request)
    {
        $customerSchemeBranch = CustomerScheme::where('branch_id', $request->branch_id)->get()->unique('scheme_id');
        $schemeChartData = collect([]);
        $basedata =  ['Scheme', 'No of Customers'];
        $schemeChartData->push($basedata);
        foreach ($customerSchemeBranch as $key => $custbranch) {
            $scheme = Scheme::where('id', $custbranch->scheme_id)->first();
            $count = CustomerScheme::where('branch_id', $request->branch_id)->where('scheme_id', $scheme->id)->get()->unique('customer_id')->count();
            $data = [$scheme->scheme_id, $count];
            $schemeChartData->push($data);
        }
        return $schemeChartData;
    }
    public function getBranchTarget(Request $request){

        return [
            'bar' => $this->chart->build($request)->toJson(),
        ];


    }
    public function getExecutivesList(){
        $user = Auth::user();
        $userRole = $user->roles->pluck('name')->first();
        $manager = Manager::where('user_id', $user->id)->first();
        $executives = Executive::where('manager_id',$manager->id)->with('branch')->get();

        return $executives;
    }
}
