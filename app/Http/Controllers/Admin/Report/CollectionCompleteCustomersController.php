<?php
namespace App\Http\Controllers\admin\Report;
use App\Http\Controllers\Controller;
use App\Models\Customer;
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
class CollectionCompleteCustomersController extends Controller
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
                $user = Auth::user();
                $userRole = $user->roles->pluck('name')->first();
                if ($userRole == 'super-admin') {
                    if ($request->branch_id == 0 && $request->scheme_id == 0 ) {
                        $customers = CustomerScheme::where('status', 2);
                        if(isset($request->from_date) && isset($request->to_date)){
                            $customers = $customers->whereBetween('completed_date',[$request->from_date,$request->to_date]);
                        }
                        $customers = $customers->get();

                    } elseif ($request->branch_id != 0 && $request->scheme_id == 0) {
                        $customers = CustomerScheme::where('branch_id',$request->branch_id)->where('status', 2);
                        if(isset($request->from_date) && isset($request->to_date)){
                            $customers = $customers->whereBetween('completed_date',[$request->from_date,$request->to_date]);
                        }
                        $customers = $customers->get();
                    }elseif ($request->branch_id != 0 && $request->scheme_id != 0) {
                        $customers = CustomerScheme::where('branch_id',$request->branch_id)->where('scheme_id',$request->scheme_id)->where('status', 2);
                        if(isset($request->from_date) && isset($request->to_date)){
                            $customers = $customers->whereBetween('completed_date',[$request->from_date,$request->to_date]);
                        }
                        $customers = $customers->get();
                    }
                    else{
                        $customers = CustomerScheme::where('status', 2);
                        $customers = $customers->get();
                    }

                }
                if ($userRole == 'branch-manager') {

                    $manager = Manager::where('user_id', $user->id)->first();
                    if ($request->branch_id == 0 && $request->scheme_id == 0 ) {
                        $customers = CustomerScheme::where('branch_id',$manager->branch_id)->where('status', 2);
                        if(isset($request->from_date) && isset($request->to_date)){
                            $customers = $customers->whereBetween('completed_date',[$request->from_date,$request->to_date]);
                        }
                        $customers = $customers->get();

                    } elseif ($request->branch_id != 0 && $request->scheme_id == 0) {
                        $customers = CustomerScheme::where('branch_id',$manager->branch_id)->where('status', 2);
                        if(isset($request->from_date) && isset($request->to_date)){
                            $customers = $customers->whereBetween('completed_date',[$request->from_date,$request->to_date]);
                        }
                        $customers = $customers->get();
                    }elseif ($request->branch_id != 0 && $request->scheme_id != 0) {
                        $customers = CustomerScheme::where('branch_id',$manager->branch_id)->where('scheme_id',$request->scheme_id)->where('status', 2);
                        if(isset($request->from_date) && isset($request->to_date)){
                            $customers = $customers->whereBetween('completed_date',[$request->from_date,$request->to_date]);
                        }
                        $customers = $customers->get();
                    }
                    else{
                        $customers = CustomerScheme::where('branch_id',$manager->branch_id)->where('status', 2);
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
                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="collection-completed/' . $row->id . '" class="view btn btn-primary btn-floating btn-sm">
                        <i class="la la-eye"></i>
                    </a>';
                        return $btn;

                    })
                    ->rawColumns(['action', 'branch'])
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
        $customerScheme = CustomerScheme::where('id', $id)->first();
        $customer = Customer::with('orders ')->where('id', $customerScheme->customer_id)->with('area')->first();
        $areas = Area::get();
        $customerSchemes = CustomerScheme::where('customer_id', $customerScheme->customer_id)->get();
        // dd($customerSchemes);
        return view('backend.reports.show-collection-completed-customer')->with(compact('customer', 'customerSchemes','areas'));
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
