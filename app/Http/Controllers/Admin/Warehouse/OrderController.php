<?php

namespace App\Http\Controllers\Admin\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerProduct;
use App\Models\CustomerScheme;
use App\Models\Warehouse\Category;
use App\Models\Warehouse\Order;
use App\Models\Warehouse\Product;
use App\Repositories\interfaces\Warehouse\OrderInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    protected $orderInterface;

    public function __construct(OrderInterface $orderInterface)
    {
        $this->middleware(['role:super-admin|developer-admin|store-admin|delivery-boy|marketing-executive|collection-executive']);
        $this->orderInterface = $orderInterface;
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
                if($userRole == 'delivery-boy'){
                    $order = $this->orderInterface->listOrderByDeliveryBoy();
                }
                else{
                    $order = $this->orderInterface->listOrders();
                }
                return DataTables::of($order)
                    ->addIndexColumn()
                    ->addColumn('customer_details',function($row){
                        return $row->customer->name.'-'.$row->customer->phone;
                    })
                    ->addColumn('order_date',function($row){
                        return $row->order_date->format('F d Y');
                    })
                    ->addColumn('status',function($row){
                       if($row->status == 0){
                           return 'Pending';
                          }elseif($row->status == 1){
                            return 'Delivered';
                            }elseif($row->status == 2){
                                return 'cancelled';
                            }
                            elseif($row->status == 3){
                                return 'To be delivered';
                            }
                    })
                    ->addColumn('action', function ($row) {
                        $user = Auth::user();
                        $userRole = $user->roles->pluck('name')->first();
                        $btn = '';
                        if ($userRole == 'super-admin'  || $userRole == 'developer-admin' || $userRole == 'store-admin') {
                            $btn = '
                            <a href="orders/' . $row->id . '/" class="edit btn btn-info btn-floating btn-sm">
                                <i class="la la-eye"></i>
                            </a>
                            <a href="/admin/print/' . $row->id . '/" class="edit btn btn-info btn-floating btn-sm">
                            <i class="la la-print"></i>
                        </a>
                            <a data-id="' . $row->id . '" class="delete btn btn-danger btn-floating btn-sm">
                                <i class="la la-trash"></i>
                            </a>';
                        }
                        else{
                            $btn = '
                            <a href="orders/' . $row->id . '/" class="edit btn btn-info btn-floating btn-sm">
                                <i class="la la-eye"></i>
                            </a>';
                        }
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('backend.warehouse.orders.list-orders');
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
        $ordID = Order::max('id')+1;
        $ordID = 'ORD-'.$ordID;
        $categories = Category::all();
        $products = Product::where('status',1)->get();
        $customerScheme = CustomerScheme::with('customer')->where('status',2)->get();
        $customers = $customerScheme->map(function($customerScheme){
            return $customerScheme->customer;
        })->pluck('id')->toArray();
        $customers = Customer::whereIn(
            'id', $customers
        )->orWhere('customer_type','direct')->get();

        return view('backend.warehouse.orders.create-orders',compact('categories','products','customers','ordID'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'customer_id' => 'required',
            'phone' => 'required',
            'order_date' => 'required',
            'sub_amount' => 'required',
            'quantity' => 'required',
            'net_amount' => 'required',
        ]);
        return $order = $this->orderInterface->createOrder($request);

        try {
            return redirect()->route('orders.index')->with('success', 'Order created successfully');
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
         $order = $this->orderInterface->getOrderById($id);
         return view('backend.warehouse.orders.show-order',compact('order'));
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
        $delete = Order::destroy($id);
    }
    public function getProduct(){
        $product = Product::where('id',request()->product_id)->first();
        return response()->json($product);
    }
    public function getProductArray(){
        $product = Product::whereIn('id',request()->product_id)->get();
        return response()->json($product);
    }
    public function getCustomer(){
        $customerProduct = CustomerProduct::where('customer_id',request()->customer_id)->get();
        $customerProduct = $customerProduct->map(function($customerProduct){
            return $customerProduct->product;
        });

        $customers = Customer::where('id',request()->customer_id)->first();
        $total_amount = CustomerScheme::where('customer_id',$customers->id)->where('status',2)->sum('total_amount');
        $customer = [];
        $customer['customer'] = $customers;
        $customer['customerProduct'] = $customerProduct;
        $customer['total_amount'] = $total_amount;
        return response()->json($customer);
    }
    public function setSessionCart(){
        // return request()->cart;
        session()->forget('cart');
        $cart = session()->get('cart');
        if(!$cart){
            $cart = [];
        }
        session()->put('cart', request()->cart);
        $cart = session()->get('cart');
        // return $cart11;
        return response()->json($cart);
    }
    public function newCustomer(){
        $customer = new Customer();
        $custID = $customer->id;
        $custID = $custID + 1;
        $customer->customer_id = 'LLC-'.$custID;
        $customer->name = request()->name;
        $customer->username = request()->name;
        $customer->place = request()->place;
        $customer->phone = request()->phone;
        $customer->city = request()->city;
        $customer->customer_type = 'direct';
        $customer->address = request()->address;
        $customer->save();
        return response()->json($customer);
    }
}
