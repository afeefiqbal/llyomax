<?php
namespace App\Http\Controllers;
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
use Illuminate\Support\Str;
class WarehouseAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['role:super-admin|developer-admin|branch-manager|store-admin|delivery-boy']);
    }
    public function index(Request $request)
    {
        $branches = Branch::get();
        try {
            if ($request->ajax()) {
                if ($request->branch_id == 0 && $request->scheme_id == 0 ) {
                    $customers = CustomerScheme::where('status', 2)
                    ->get();
                } elseif ($request->branch_id != 0 && $request->scheme_id == 0) {
                    $customers = CustomerScheme::where('branch_id',$request->branch_id)->where('status', 2)
                    ->get();
                }elseif ($request->branch_id != 0 && $request->scheme_id != 0) {
                    $customers = CustomerScheme::where('branch_id',$request->branch_id)->where('scheme_id',$request->scheme_id)->where('status', 2)
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
                    ->addColumn('customer_place', function ($row) {
                        return $row->customer->place;
                    })
                    ->addColumn('customer_phone', function ($row) {
                        return $row->customer->phone;
                    })
                    ->addColumn('reason', function ($row) {
                        return "";
                    })
                    ->addColumn('executive', function ($row) {
                        $executive = CustomerExecutive::where('customer_id', $row->customer_id)->where('scheme_id', $row->scheme_id)->with('executive')->first();
                        return ($executive != '' ? $executive->executive->name : "");
                    })
                    ->make(true);
            }
            return view('backend.reports.collection-completed-customers', compact('branches'));
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }

}
