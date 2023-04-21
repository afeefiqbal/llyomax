<?php

namespace App\Http\Controllers\Admin\Branch;

use App\Http\Controllers\Controller;
use App\Models\Branch\LuckyDraw;
use App\Models\Customer;
use App\Models\Executive\ExecutiveReportSubmission;
use App\Models\Master\Branch;
use App\Models\Master\Cluster;
use App\Models\Scheme;
use App\Repositories\Branch\LuckyDrawInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class LuckyDrawController extends Controller
{
    protected $luckyDrawInterface;
    public function __construct(LuckyDrawInterface $luckyDrawInterface)
    {
        $this->luckyDrawInterface = $luckyDrawInterface;
    }
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $user = Auth::user();
                $userRole = $user->roles->pluck('name')->first();
                if ($userRole == 'branch-manager' || $userRole == 'collection-manager') {
                    $luckyDraw = $this->luckyDrawInterface->listBranchLuckyDraws($user->id);
                } elseif ($userRole == 'marketing-executive' || $userRole == 'collection-executive') {
                    $luckyDraw = $this->luckyDrawInterface->listExecutiveBranchLuckyDraws($user->id);
                } else {
                    $luckyDraw = $this->luckyDrawInterface->listLuckyDraws();
                }

                return DataTables::of($luckyDraw)
                    ->addIndexColumn()
                    ->addColumn('scheme_name', function ($row) {
                        return $row->scheme->scheme_id . '-' . $row->scheme->name;
                    })
                    ->addColumn('branch_name', function ($row) {
                        $branch = $row->branch;
                        if (isset($branch)) {
                            return $branch->branch_id . '-' . $branch->name;
                        } else {
                            return '';
                        }
                    })
                    ->addColumn('customer_name', function ($row) {
                        return $row->customer->customer_id . '-' . $row->customer->name;
                    })
                    ->addColumn('joined_date', function ($row) {
                        return $row->customer->created_at->format('d-m-Y');
                    })
                    ->addColumn('action', function ($row) {
                        $user = Auth::user();
                        $userRole = $user->roles->pluck('name')->first();
                        if ($userRole == 'branch-manager' || $userRole == 'developer-admin' || $userRole == 'super-admin') {
                            $btn = '
                            <a data-id="' . $row->id . '" class="delete btn btn-danger btn-floating btn-sm">
                            <i class="la la-trash"></i>
                        </a>
                            ';
                        } else {
                            $btn = ' ';
                        }
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('backend.branch.lucky_draws.list-lucky_draws');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
    public function create()
    {
        $branches = Branch::get();
        $clusters = Cluster::get();
        return view('backend.branch.lucky_draws.create-lucky_draw')->with(compact('branches', 'clusters'));
    }
    public function getBranch(Request $request)
    {
        $branches = Branch::where('cluster_id', $request->id)->get();
        return response()->json($branches);
    }
    public function getSchemes(Request $request)
    {
        $schemes = Scheme::where('start_date', '<', Carbon::now())->get();
        return $schemes;
    }
    public function getWeek(Request $request)
    {
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
    public function  getCustomers(Request $request)
    {
        $customers = $this->getSelectCustomers($request);
        return DataTables::of($customers)
            ->addIndexColumn()
            ->addColumn('customerId', function ($row) {
                return $row->customer_id;
            })
            ->addColumn('customername', function ($row) {
                return $row->name ?? '';
            })
            ->addColumn('phone', function ($row) {
                return $row->phone;
            })
            ->addColumn('branch', function ($row) {
                return $row->branch->branch_id."-". $row->branch->branch_name ?? '';
            })
            ->addColumn('area', function ($row) {
                return $row->area->name ?? '';
            })
            ->make(true);
    }
    public function getSelectCustomers(Request $request)
    {
        if ($request->branch_id == 'all') {
            $cluster = Cluster::where('id', $request->cluster_id)->first();
            $branchesID = [];
            foreach ($cluster->branches as $branch) {
                $branchesID[] = $branch->id;
            }
            $schemeCustomers = ExecutiveReportSubmission::where('scheme_id', $request->scheme_id)->whereIn('branch_id', $branchesID);
            if (isset($request->from_date) && isset($request->to_date)) {
                $schemeCustomers = $schemeCustomers->whereBetween('paid_date', [$request->from_date, $request->to_date]);
            }

            $schemeCustomers = $schemeCustomers->where('paid_week', $request->week)->where('due_amount', 0)->pluck('customer_id')->toArray();
            $customers = Customer::join('customer_scheme', 'customers.id', '=', 'customer_scheme.customer_id')
                ->where('customer_scheme.scheme_id', $request->scheme_id)
                ->whereIn('customer_scheme.status', [1, 2])
                ->whereIn('customers.id', $schemeCustomers)
                ->with('area')
                ->get(['customers.*']);
            return $customers;
        } else {
            $schemeCustomers = ExecutiveReportSubmission::where('branch_id', $request->branch_id)
                ->where('scheme_id', $request->scheme_id);
                if (isset($request->from_date) && isset($request->to_date)) {
                    $schemeCustomers = $schemeCustomers->whereBetween('paid_date', [$request->from_date, $request->to_date]);
                }

                $schemeCustomers = $schemeCustomers->where('paid_week', $request->week)->where('due_amount', 0)->pluck('customer_id')->toArray();
                $customers = Customer::join('customer_scheme', 'customers.id', '=', 'customer_scheme.customer_id')
                    ->where('customer_scheme.branch_id', $request->branch_id)
                    ->where('customer_scheme.scheme_id', $request->scheme_id)
                    ->whereIn('customer_scheme.status', [1, 2])
                    ->whereIn('customers.id', $schemeCustomers)
                    ->with('area')
                    ->get(['customers.*']);
                return $customers;
        }

    }
    public function store(Request $request)
    {
        $request->validate(
            [
                'draw_date' => 'required',
                'branch_id' => 'required',
                'scheme_id' => 'required',
                'customer_id' => 'required',
                'week' =>
                [
                    'required',
                    Rule::unique('lucky_draws')
                        ->where('branch_id', $request->branch_id)
                        ->where('scheme_id', $request->scheme_id)
                        ->where('week', $request->week)
                ],
            ],
            [
                '*.required' => 'This field is required',
            ]
        );
        try {
            $luckyDraw = $this->luckyDrawInterface->createLuckyDraw($request);
            if ($luckyDraw) {
                return response()->json(['success' => 'luckyDraw successfully created']);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
    public function destroy($id)
    {
        try {
            $luckyDraw = $this->luckyDrawInterface->deleteLuckyDraw($id);
            if ($luckyDraw) {
                return response()->json(['success' => 'LuckyDraw successfully deleted']);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
    public function eligibleCustomers()
    {
        $branches = Branch::get();
        $clusters = Cluster::get();
        return view('backend.branch.lucky_draws.eligible-customers')->with(compact('branches', 'clusters'));
    }
}
