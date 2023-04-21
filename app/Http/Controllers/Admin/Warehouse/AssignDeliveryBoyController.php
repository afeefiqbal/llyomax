<?php

namespace App\Http\Controllers\Admin\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Warehouse\DeliveryBoy;
use App\Models\Warehouse\DeliveryOrder;
use App\Models\Warehouse\Order;
use App\Repositories\interfaces\Warehouse\AssignDeliveryBoyInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class AssignDeliveryBoyController extends Controller
{
    protected $assignDeliveryBoyInterface;

    public function __construct(AssignDeliveryBoyInterface $assignDeliveryBoyInterface)
    {
        $this->middleware(['role:super-admin|developer-admin|store-admin|delivery-boy']);
        $this->assignDeliveryBoyInterface = $assignDeliveryBoyInterface;
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
                    $assignDeliveryBoy = $this->assignDeliveryBoyInterface->listDeliveryOrderByDB();
                }
                else{

                    $assignDeliveryBoy = $this->assignDeliveryBoyInterface->listDeliveryOrders();
                }
                return DataTables::of($assignDeliveryBoy)
                    ->addIndexColumn()
                    ->addColumn('order_id', function ($row) {
                        return $row->order->order_id;
                    })
                    ->addColumn('customer_id', function ($row) {
                        return $row->customer->customer_id . '-' . $row->customer->name;
                    })
                    ->addColumn('delivery_boy_id', function ($row) {
                        return $row->deliveryBoy->delivery_boy_id . '-' . $row->deliveryBoy->name;
                    })
                    ->addColumn('assign_date', function ($row) {
                        $date = $row->assign_date;
                        if (isset($date)) {
                            $date = $row->assign_date->format('d-M-Y');
                        }
                        return $date;
                    })
                    ->addColumn('delivery_date', function ($row) {
                        $date = $row->delivery_date;
                        if (isset($date)) {
                            $date =  $row->delivery_date->format('d-M-Y');
                        }
                        return $date;
                    })
                    ->addColumn('is_delivered', function ($row) {
                        $status = $row->is_delivered;
                        if ($status == '0') {
                            $status = 'not delivered';
                        } elseif ($status == '1') {
                            $status = 'delivered';
                        }
                        return $status;
                    })
                    ->addColumn('action', function ($row) use($userRole) {
                        $btn = '';
                        if ($userRole == 'super-admin'  || $userRole == 'developer-admin' || $userRole == 'store-admin') {
                            $btn = '
                            <a href="assigning-delivery-boys/' . $row->id . '/" class="edit btn btn-info btn-floating btn-sm">
                                <i class="la la-eye"></i>
                            </a>
                            <a href="assigning-delivery-boys/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
                                <i class="la la-pencil"></i>
                            </a>
                            <a data-id="' . $row->id . '" class="delete btn btn-danger btn-floating btn-sm">
                                <i class="la la-trash"></i>
                            </a>';
                        }
                        else{
                            $btn = '
                            <a href="assigning-delivery-boys/' . $row->id . '/" class="edit btn btn-info btn-floating btn-sm">
                                <i class="la la-eye"></i>
                            </a>';
                        }
                        return $btn;
                    })
                    ->rawColumns(['action', 'branch'])
                    ->make(true);
            }
            return view('backend.warehouse.assign-delivery-boys.list-assign-delivery-boys');
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
        $orders  = Order::where('status', '=', '1')->get();
        $deliveryBoys  = DeliveryBoy::get();
        $customers  = Customer::get();
        return view('backend.warehouse.assign-delivery-boys.create-assign-delivery-boy', compact('orders', 'deliveryBoys', 'customers'));
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
            'order_id' => 'required|unique:delivery_order,order_id,',
            'delivery_boy_id' => 'required|unique:delivery_order,delivery_boy_id,',
            'customer_id' => 'required|unique:delivery_order,customer_id,',
            'date' => 'required',
        ]);
        try {
            $assignDeliveryBoy = $this->assignDeliveryBoyInterface->createDeliveryOrder($request);
            return redirect()->route('assigning-delivery-boys.index')->with('success', 'Assigened Delivery Order successfully');
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
        $assignDeliveryBoy = $this->assignDeliveryBoyInterface->getDeliveryOrderById($id);
        return view('backend.warehouse.assign-delivery-boys.show-assign-delivery-boys', compact('assignDeliveryBoy'));
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
        $assignDeliveryBoy = $this->assignDeliveryBoyInterface->getDeliveryOrderById($id);
        $orders  = Order::where('status', '=', '1')->get();
        $deliveryBoys  = DeliveryBoy::get();
        $customers  = Customer::get();
        return view('backend.warehouse.assign-delivery-boys.create-assign-delivery-boy', compact('orders', 'deliveryBoys', 'customers', 'assignDeliveryBoy'));
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
        $request->validate([
            'order_id' => 'required',
            'delivery_boy_id' => 'required',
            'customer_id' => 'required,',
            'date' => 'required',
        ]);
        try {
            $assignDeliveryBoy = $this->assignDeliveryBoyInterface->updateDeliveryOrder($request, $id);
            return redirect()->route('assigning-delivery-boys.index')->with('success', 'Assigened Delivery Order successfully');
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
        try {
            $assignDeliveryBoy = $this->assignDeliveryBoyInterface->deleteDeliveryOrder($id);
            return redirect()->route('assigning-delivery-boys.index')->with('success', 'Assigened Delivery Order Deleted');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
    public function getCustomers()
    {
        $order = Order::where('id', request()->order_id)->first();
        $customer = Customer::where('id', $order->customer_id)->first();
        return $customer;
    }
    public function getOrderStatus(){
        $deliveryOrder = DeliveryOrder::where('id',request()->deliveryOrderID)->first();
        return $deliveryOrder;
    }
    public function updateOrderStatus(){
        $deliveryOrder = DeliveryOrder::where('id',request()->deliveryOrderID)
        ->update([
            'is_delivered' => 1,
            'delivery_date' => date('Y-m-d'),
        ]);
        $deliveryOrder = DeliveryOrder::where('id',request()->deliveryOrderID)->first();

        $order = Order::where('id', $deliveryOrder->order_id)->update([
            'status' => 1,
        ]);
        return $sucess = 'success';
    }

}
