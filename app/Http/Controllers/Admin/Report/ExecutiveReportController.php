<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Master\Area;
use App\Models\Master\Branch;
use App\Repositories\Report\ExecutiveReportInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class ExecutiveReportController extends Controller
{
    protected $executiveReportInterface;
    public function __construct(ExecutiveReportInterface $executiveReportInterface)
    {
        $this->middleware(['role:super-admin|developer-admin|branch-manager']);
        $this->executiveReportInterface = $executiveReportInterface;
    }
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $user = Auth::user();
                $userRole = $user->roles->pluck('name')->first();
                if ($userRole == 'super-admin'  || $userRole == 'developer-admin') {
                    $executiveReport = $this->executiveReportInterface->listExecutiveReport();
                } elseif ($userRole == 'branch-manager') {
                    $executiveReport = $this->executiveReportInterface->listBranchExecutiveReport($user->id);
                }
                return DataTables::of($executiveReport)
                    ->addIndexColumn()
                    ->addColumn('type', function ($row) {
                        if ($row->executive_type == 1) {
                            return 'Marketing Executive';
                        } elseif ($row->executive_type == 2) {
                            return 'Collection Executive';
                        }
                    })
                    ->addColumn('collection_area', function ($row) {
                        $area = Area::find($row->collection_area_id);
                        return $area->name;
                    })
                    ->addColumn('branch', function ($row) {
                        $branch = Branch::where('id', $row->branch_id)->first();
                        if (isset($branch)) {
                            return $branch->branch_name;
                        }
                    })
                    ->addColumn('joined_date', function ($row) {
                        return $row->created_at->format('d-M-y');
                    })
                    ->addColumn('status', function ($row) {
                        if ($row->status == 1) {
                            return 'active';
                        } else {
                            return 'not active';
                        }
                    })
                    ->make(true);
            }
            return view('backend.reports.executive-report');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
    public function staffReport(Request $request)
    {
        try {
            if ($request->ajax()) {
                return $staffReport = $this->executiveReportInterface->listStaffReport();
                return DataTables::of($staffReport)
                    ->addIndexColumn()
                    ->addColumn('type', function ($row) {
                        if ($row->executive_type == 1) {
                            return 'Marketing Executive';
                        } elseif ($row->executive_type == 2) {
                            return 'Collection Executive';
                        }
                    })
                    ->addColumn('collection_area', function ($row) {
                        $area = Area::find($row->collection_area_id);
                        return $area->name;
                    })
                    ->addColumn('branch', function ($row) {
                        $branch = Branch::where('id', $row->branch_id)->first();
                        if (isset($branch)) {
                            return $branch->branch_name;
                        }
                    })
                    ->addColumn('joined_date', function ($row) {
                        return $row->created_at->format('d-M-y');
                    })
                    ->addColumn('status', function ($row) {
                        if ($row->status == 1) {
                            return 'active';
                        } else {
                            return 'not active';
                        }
                    })
                    ->make(true);
            }
            return view('backend.reports.staff-report');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
}
