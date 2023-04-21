<?php

namespace App\Http\Controllers\Admin\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warehouse\DeliveryBoy;
use App\Repositories\interfaces\Warehouse\DeliveryBoyInterface;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Log;

class DeliveryBoyController extends Controller
{
    protected $deliveryBoyInterface;

    public function __construct(DeliveryBoyInterface $deliveryBoyInterface)
    {
        $this->middleware(['role:super-admin|developer-admin|store-admin|delivery-boy']);
        $this->deliveryBoyInterface = $deliveryBoyInterface;
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
                $deliveryBoy = $this->deliveryBoyInterface->listdeliveryExecutives();
                return DataTables::of($deliveryBoy)
                    ->addIndexColumn()

                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="delivery-executives/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
                            <i class="la la-pencil"></i>
                        </a>
                        <a data-id="' . $row->id . '" class="delete btn btn-danger btn-floating btn-sm">
                            <i class="la la-trash"></i>
                        </a>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'branch'])
                    ->make(true);
            }
            return view('backend.warehouse.delivery-boy.list-delivery-boy');
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
        try {
            return view('backend.warehouse.delivery-boy.create-delivery-boy');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
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
            'delivery_boy_id' => 'required|unique:delivery_boys,delivery_boy_id,',
            'name' => 'required|unique:delivery_boys,name',
            'email' => 'required|email',
            'phone' => 'required|unique:delivery_boys',
            'password' => 'required',
            'place' => 'required',
        ]);
        try {
            $this->deliveryBoyInterface->createDeliveryBoy($request);
            return redirect()->route('delivery-executives.index')->with('success', 'Delivery Boy Created Successfully');
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $deliveryBoy = $this->deliveryBoyInterface->getDeliveryBoyById($id);
        return view('backend.warehouse.delivery-boy.create-delivery-boy', compact('deliveryBoy'));
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
        $request->validate([
            'delivery_boy_id' => 'required|unique:delivery_boys,delivery_boy_id,'.$id,
            'name' => 'required|unique:delivery_boys,name,'.$id,
            'email' => 'required|email',
            'phone' => 'required|unique:users,phone,'.$id,
            'phone' => 'required|unique:delivery_boys,phone,'.$id,
            'password' => 'required',
            'place' => 'required',
        ]);
        try {
            $this->deliveryBoyInterface->updateDeliveryBoy($request,$id);
            return redirect()->route('delivery-executives.index')->with('success', 'Delivery Boy Updated Successfully');
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
        $dvb = DeliveryBoy::find($id);
        $user = User::where('id', $dvb->user_id)->first()->delete();
        // $delete = DeliveryBoy::find($id)->delete();
        return $user;
    }
}
