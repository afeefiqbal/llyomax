<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerExecutive;
use App\Models\CustomerScheme;
use App\Models\Executive\Executive;
use App\Models\Executive\ExecutiveReportSubmission;
use App\Models\Master\Area;
use App\Models\Master\Branch;
use App\Models\Scheme;
use App\Models\User;
use App\Models\Warehouse\Order;
use App\Models\Warehouse\Product;
use App\Repositories\interfaces\Customer\CustomerInterface;
use Carbon\Carbon;
use Exception;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Session\Session as SessionSession;
use Yajra\DataTables\Facades\DataTables;
use PDF;
use View;
class CustomerController extends Controller
{
    protected $customerInterface;
    public function __construct(CustomerInterface $customerInterface)
    {
        $this->middleware(['role:super-admin|developer-admin|branch-manager|collection-executive|marketing-executive|customer|office-administrator']);
        $this->customerInterface = $customerInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $user = Auth::user();
                $userRole = $user->roles->pluck('name')->first();
                if ($userRole == 'super-admin'  || $userRole == 'developer-admin') {
                    $customers = $this->customerInterface->listCustomers();
                } elseif ($userRole == 'collection-executive') {
                    $customers = $this->customerInterface->listexecutiveCustomers($user->id);
                }elseif ($userRole == 'marketing-executive') {
                    $customers = $this->customerInterface->listmarketingexecutiveCustomers($user->id);
                }
                elseif ($userRole == 'branch-manager') {

                    $customers = $this->customerInterface->listbranchmanagerCustomers($user->id);
                }
                elseif($userRole == 'office-administrator'){
                    $customers = $this->customerInterface->listofficeadministratorCustomers($user->id);
                }
                else {
                    $customers = $this->customerInterface->listCustomers();
                }
                return DataTables::of($customers)
                    ->addIndexColumn()
                    ->addColumn('customer_id', function ($row) {

                        return $row->customer->customer_id ?? '';
                    })
                    ->addColumn('name', function ($row) {
                        $customer = $row->customer;
                        if(isset($customer)){
                            return $customer->name;
                        }
                        else{
                            return '';
                        }
                        // return $row->customer->name ?? '';
                    })
                    ->addColumn('phone', function ($row) {

                        return $row->customer->phone;
                    })
                  ->addColumn('joining_date', function ($row) {
                        return $row->joining_date;
                    })
                  ->addColumn('branch_id', function ($row) {
                    $branch = $row->branch;
                    if(isset($branch)){
                        return $row->branch->branch_id."-". $row->branch->branch_name ?? '';
                    }
                    else{
                        return '';
                    }
                    return $branch->branch_name ?? '';
                    })
                  ->addColumn('area', function ($row) {
                      $customer = Customer::where('id',$row->customer_id)->with('area')->first();
                        return $customer->area->name ?? '';
                    })

                    ->addColumn('show_more', function ($row) {
                        $btn = '<a href="customers/' . $row->id . '/" class="edit btn btn-info btn-floating btn-sm">
                            <i class="la la-eye"></i>
                        </a>';
                        return $btn;
                    })

                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a data-id="' . $row->id . '" class="delete btn btn-danger btn-floating btn-sm">
                            <i class="la la-trash"></i>
                        </a>';
                        $user = Auth::user();
                        $userRole = $user->roles->pluck('name')->first();
                        if ($userRole == 'super-admin'  || $userRole == 'developer-admin') {
                        return $btn;
                        }
                    })

                    ->rawColumns(['action', 'show_more'])
                    ->make(true);
            }
            return view('backend.customers.customer.list-customer');
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
        $products = Product::where('status', 1)->get();
        $branches = Branch::get();
        return view('backend.customers.customer.create-customer')->with(compact('branches','products'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $customerID = Customer::latest()->first();
        // if ($customerID) {
        //     $customerCode = $customerID->customer_id;
        //     $customerScheme = CustomerScheme::where('customer_id',$customerID->id)->latest()->first();
        //     if($request->scheme_id !=$customerScheme->scheme_id){
        //         // $scheme = Scheme::orderBy('id','desc')->first();
        //         $scheme = Scheme::where('id',$request->scheme_id)->first();
        //         $schemeID =  $scheme->scheme_a_id;
        //         $customerCode = $schemeID.'001';
        //     }
        //     else{
                // $scheme = Scheme::orderBy('id','desc')->first();
                $scheme = Scheme::where('id',$request->scheme_id)->first();
                $customer = Customer::where('customer_id','LIKE','%'.$scheme->scheme_a_id.'%')->latest()->first();
                if($customer != null)
                {
                    $customerCode = $customer->customer_id;
                    $schemeID =  $scheme->scheme_a_id;
                    $customerCode = substr($customerCode, -3);

                    $customerCode = (int) $customerCode;
                    $customerCode++;
                    $customerCode =  sprintf('%03d', $customerCode);
                    $customerCode = $schemeID.$customerCode;
                }
                else{
                    $customerCode = $scheme->scheme_a_id.'001';
                }
        //     }

        // } else {
        //     // $scheme = Scheme::orderBy('id','desc')->first();
        //     $scheme = Scheme::where('id',$request->scheme_id)->first();
        //     $schemeID =  $scheme->scheme_a_id;
        //     $customerCode = $schemeID.'001';

        // }

        $request['customerCode'] = $customerCode;
        $otp = Session::get('otp');
        $amount = 6000;
        $request->validate(
            [
                'name' => 'required | string ',
                'phone' => 'required |unique:customers,phone',

                'otp' => [
                        'required' ,
                        Rule::in([$otp]),
                         ],
                'place' => 'required',
                'amount' => 'required',
                'custom_amount' => 'required_if:amount,==,custom|nullable|integer|gt:0|lte:' . $amount,
                'branch_id' => 'required',
                'scheme_id' => 'required',
                'customer_collection_day' => 'required',
            ],
            [
                '*.required' => 'This field is required',
            ]
        );
        try {
            $customer = $this->customerInterface->createCustomer($request);
            if ($customer) {
                return response()->json(['success' => 'Customer successfully created']);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
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
        $customer = Customer::where('id', $customerScheme->customer_id)->with('area')->first();
        $areas = Area::get();
        $customerSchemes = CustomerScheme::where('customer_id', $customerScheme->customer_id)->get();
        $schemesID = [];
        foreach ($customerSchemes as $customerScheme) {
            $schemesID[] = $customerScheme->scheme_id;
        }
        $schemes = Scheme::whereIn('id', $schemesID)->get();
        $customerExcutivesAssigned = CustomerExecutive::where('customer_id', $customerScheme->customer_id)->get();
        return view('backend.customers.customer.show-customer')->with(compact('customer', 'customerSchemes','areas','schemes','customerExcutivesAssigned'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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

        $amount =6000;
        $otp = Session::get('otp');
        $request->validate(
            [
                'customer_id' => 'required',
                'branch_id' => 'required',
                // 'amount' => 'required',
                'custom_amount' => 'required_if:amount,==,custom|nullable|integer|gt:0|lte:' . $amount,
                'otp' => [
                    'required' ,
                    Rule::in([$otp]),
                     ],
                'scheme_id' =>
                [
                    'required',
                    Rule::unique('customer_scheme')
                        ->where('branch_id', $request->branch_id)
                        ->where('customer_id', $request->customer_id)
                        ->where('scheme_id', $request->scheme_id)
                ],
            ],
            [
                '*.required' => 'This field is required',
                'scheme_id.unique' => 'Scheme already registered'
            ]
        );
        try {

            $customer = $this->customerInterface->customerSchemeRegister($request);
            if ($customer) {
                return response()->json(['success' => 'Scheme Registered Successfully']);
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
    public function getSchemeReport(Request $request)
    {
        $schemeReport = ExecutiveReportSubmission::where('customer_id', $request->customer_id)->where('scheme_id', $request->scheme_id)->with('scheme')->get();
        return $schemeReport;
    }
    public function DeleteCustomerScheme(Request $request)
    {

        try {
            $customerScheme = CustomerScheme::where('customer_id', $request->customer_id)->where('scheme_id', $request->scheme_id)->delete();
            if ($customerScheme) {
                $schemeReport = ExecutiveReportSubmission::where('customer_id', $request->customer_id)->where('scheme_id', $request->scheme_id)->delete();
                return response()->json(['success' => 'Customer Scheme Deleted Successfully']);
            }

          } catch (Exception $e) {
              Log::info($e->getMessage());
              $e->getCode();
              $e->getMessage();
              throw $e;
          }

    }
    public function getData(Request $request)
    {
        $data['schemes'] = Scheme::whereDate('join_end_date', '>=', Carbon::now())->get();
        // $data['schemes'] = Scheme::get();
        $data['areas'] = Area::get();

        return $data;
    }
    public function schemeRegister($id)
    {

        $customerSchemeDetail = CustomerScheme::where('customer_id', $id)->with('customer', 'branch')->first();
        return view('backend.customers.customer.create-customer')->with(compact('customerSchemeDetail'));
    }
    public function updateCustomer(Request $request, $id)
    {

        $amount =6000;
        $otp = Session::get('otp');
        $customer = Customer::where('id', $id)->first();
        $user = User::where('id', $customer->user_id)->first();
        if(isset($request->otp)){

            $request->validate(
                [
                    'phone' => 'required |unique:customers,phone,' . $customer->id,
                    'phone' => 'required |unique:users,mobile,' . $user->id,
                    'name' => 'required',
                    'place' => 'required',
                    // 'amount' => 'required',
                    'custom_amount' => 'required_if:amount,==,custom|nullable|integer|gt:0|lte:' . $amount,

                    'otp' => [
                        'required' ,
                        Rule::in([$otp]),
                         ],
                ],
                [
                    '*.required' => 'This field is required',
                ]
            );
        }
        else
        {
            $request->validate(
                [
                    // 'phone' => 'required |unique:customers,phone,' . $customer->id,
                    // 'phone' => 'required |unique:users,mobile,' . $user->id,
                    'name' => 'required',
                    'place' => 'required',
                    'area_id' => 'required',
                    // 'amount' => 'required',
                    'custom_amount' => 'required_if:amount,==,custom|nullable|integer|gt:0|lte:' . $amount,
                ],
                [
                    '*.required' => 'This field is required',
                ]
            );
        }
        try {

            $customer = $this->customerInterface->updateCustomer($request, $id);
            if ($customer) {
                return response()->json(['success' => 'Customer Details Updated Successfully']);
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
            $customer = $this->customerInterface->deleteCustomer($id);

            if ($customer) {
                return response()->json(['message' => 'customer successfully deleted']);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
    public function getOTP(){

        $digits = 4;
         request()->validate([
            'phone' => 'required|min:10',
        ]);
        $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);
        $phone =  request()->phone;
        $name = request()->name;
        Session::put('otp', $otp);
//
        return Helper::sendSMS($phone, $otp);
        return Session::get('otp');
    }
    public function getEditOTP(){

        $digits = 4;
         request()->validate([
            'phone' => 'required|min:10|unique:users,mobile,'.request()->id,
            'phone' => 'required|min:10|unique:customers,phone,'.request()->id,
        ]);
        $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);
        $phone =  request()->phone;
        $name = request()->name;
        Session::put('otp', $otp);

        return Helper::sendSMS($phone, $otp);
        return Session::get('otp');
    }

    public function validateOTP(){
        $otp = Session::get('otp');
        $input = request()->otp;
        if($otp == $input){
            return response()->json(['success' => 'otp verified succesfully']);
        }
        else{
            return response()->json(['error' => 'entered otp is incorrect']);
        }
    }
    public function getProductPrice(){

        $product = Product::whereIn('id', request()->product_id)->get();
        return $product;
    }
    public function printInvoice(){
        $data = request()->data;
       $order = Order::with('products.product','customer','products.order')->where('id', $data)->first();
      return  $view = View::make('invoice', compact('order'))->render();
    }
    public function print($id){
        $order = Order::with('products.product','customer','products.order')->where('id', $id)->first();
        // return  $view = View::make('invoice', compact('order'))->render();
        $pdf = PDF::loadView('invoice', compact('order'));

        return $pdf->download('invoice.pdf');
    }
}
