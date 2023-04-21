<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerExecutive;
use App\Models\CustomerScheme;
use Illuminate\Http\Request;
use App\Models\Executive\Executive;
use App\Models\Executive\ExecutiveReportSubmission;
use App\Models\Master\Area;
use App\Models\Master\Branch;
use App\Models\Scheme;
use App\Models\User;
use App\Repositories\interfaces\Customer\CustomerCollectionInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

    class CustomerCollectionController extends Controller
{
    protected $customerCollectionInterface;
    public function __construct(CustomerCollectionInterface $customerCollectionInterface)
    {
        $this->middleware(['role:super-admin|developer-admin|branch-manager|collection-executive|marketing-executive']);
        $this->customerCollectionInterface = $customerCollectionInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $branches = Branch::orderBy('branch_name')->get();
        try {
            if ($request->ajax()) {
                $user = Auth::user();
                $userRole = $user->roles->pluck('name')->first();
                if ($userRole == 'super-admin'  || $userRole == 'developer-admin' ) {
                    $customers = $this->customerCollectionInterface->listCustomers();
                } elseif ($userRole == 'collection-executive') {
                    $customers = $this->customerCollectionInterface->listexecutiveCustomers($user->id);
                }elseif ($userRole == 'marketing-executive') {
                    $customers = $this->customerCollectionInterface->listmarketingexecutiveCustomers($user->id);
                }elseif ($userRole == 'branch-manager') {
                    $customers = $this->customerCollectionInterface->listbranchmanagerCustomers($user->id);
                }
                return DataTables::of($customers)
                    ->addIndexColumn()
                    ->addColumn('branch_id', function ($row) {
                        return $row->branch->branch_name ?? '';
                    })
                    ->addColumn('customer_id', function ($row) {
                        return $row->customer->customer_id;
                    })
                    ->addColumn('customer_name', function ($row) {
                        return $row->customer->name;
                    })
                    ->addColumn('customer_phone', function ($row) {
                        return $row->customer->phone;
                    })
                    ->addColumn('scheme_name', function ($row) {
                        return $row->scheme->name;
                    })
                    ->addColumn('executive', function ($row) {
                        $executive = CustomerExecutive::where('customer_id', $row->customer_id)->where('scheme_id', $row->scheme_id)->with('executive')->first();
                        return ($executive == null ? 'Not Assigned' : $executive->executive->name ?? 'Not Assigned');
                    })
                    ->addColumn('status', function ($row) {

                       if ( $row->status == 0) {
                        $status = "Pending";
                    } elseif ( $row->status == 1) {
                        $status = "Active";
                    } elseif ( $row->status == 2) {
                        $status = "Completed";
                    } elseif ( $row->status == 3) {
                        $status = "Lucky Winner";
                    } elseif ( $row->status == 4) {
                        $status = "Stop";
                    }
                    return $status;
                    })
                    ->addColumn('action', function ($row) {

                        $user = Auth::user();
                        $userRole = $user->roles->pluck('name')->first();
                        if ($userRole == 'super-admin'  || $userRole == 'developer-admin'|| $userRole == 'branch-manager') {
                            $btn = '
                            <a href="customer-collection/' . $row->id . '/edit" class="edit btn btn-danger btn-floating btn-sm">
                            <i class="fas fa-ban"></i>
                            </a>
                            <a href="customer-collection/' . $row->id . '/" class="edit btn btn-info btn-floating btn-sm">
                                <i class="fas fa-plus"></i>
                            </a>';
                        } elseif ($userRole == 'collection-executive' || $userRole == 'marketing-executive') {
                            $btn = '

                        <a href="customer-collection/' . $row->id . '/" class="edit btn btn-info btn-floating btn-sm">
                            <i class="fas fa-plus"></i>
                        </a>';
                        }
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('backend.customers.customer_collection.list-customer-collection')->with(compact('branches'));
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
    public function stopCustomerScheme(Request $request)
    {
        $request->validate(
            [
                'reason' => 'required',
            ],
            [
                '*.required' => 'This field is required',
            ]
        );
        try {
            $customerCollection = $this->customerCollectionInterface->stopCustomerScheme($request);
            if ($customerCollection) {
                return response()->json(['success' => 'Customer successfully Stop the Scheme']);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
    public function restartCustomerScheme(Request $request)
    {
        try {
            $customerCollection = $this->customerCollectionInterface->restartCustomerScheme($request);
            if ($customerCollection) {
                return response()->json(['success' => 'Customer successfully Restart the Scheme']);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
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
        $customerSchemeDetails = CustomerScheme::where('id', $id)->with('branch', 'scheme', 'customer')->first();
        $schemeDetails = ExecutiveReportSubmission::where('customer_id', $customerSchemeDetails->customer_id)->where('scheme_id', $customerSchemeDetails->scheme_id)->get();
        $executive = CustomerExecutive::where('customer_id', $customerSchemeDetails->customer_id)->where('scheme_id', $customerSchemeDetails->scheme_id)->with('executive')->first();
        return view('backend.customers.customer_collection.create-customer-collection')->with(compact('customerSchemeDetails', 'schemeDetails', 'executive'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customerSchemeDetails = CustomerScheme::where('id', $id)->with('branch', 'scheme', 'customer')->first();
        $schemeDetails = ExecutiveReportSubmission::where('customer_id', $customerSchemeDetails->customer_id)->where('scheme_id', $customerSchemeDetails->scheme_id)->get();
        $executive = CustomerExecutive::where('customer_id', $customerSchemeDetails->customer_id)->where('scheme_id', $customerSchemeDetails->scheme_id)->with('executive')->first();
        return view('backend.customers.customer_collection.stop-customer-collection')->with(compact('customerSchemeDetails', 'schemeDetails', 'executive'));
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

        $customerSchme = CustomerScheme::where('id', $id)->first();
        $scheme = Scheme::where('id', $customerSchme->scheme_id)->first();
        $balance = $scheme->total_amount - $customerSchme->total_amount;
        $request->validate(
            [
                'amount' => ['required', 'integer', 'lte:' . $balance],

            ],
            [
                '*.required' => 'This field is required',
            ]
        );
        try {
            $customerCollection = $this->customerCollectionInterface->updateCustomerCollection($request, $id);
            if ($customerCollection) {

                return response()->json(['success' => 'Customer successfully Paid']);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
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
