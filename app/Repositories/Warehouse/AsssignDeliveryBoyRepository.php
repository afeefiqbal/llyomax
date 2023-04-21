<?php

namespace App\Repositories\Warehouse;

use App\Models\Warehouse\DeliveryBoy;
use App\Models\Warehouse\DeliveryOrder;
use App\Models\Warehouse\Order;
use App\Repositories\BaseRepository;
use App\Repositories\interfaces\Warehouse\AssignDeliveryBoyInterface;
use Illuminate\Http\Request;

class AsssignDeliveryBoyRepository implements AssignDeliveryBoyInterface
{
    public function getModel()
    {
        return DeliveryOrder::class;
    }
    public function listDeliveryOrders()
    {
        return DeliveryOrder::get();
    }
    public function listDeliveryOrderByDB(){
        $deliveryBoy = DeliveryBoy::where('user_id',auth()->user()->id)->first();
        return DeliveryOrder::where('delivery_boy_id',$deliveryBoy->id)->get();
    }
    public function getDeliveryOrderById($id)
    {
        return DeliveryOrder::find($id);
    }
    public function createDeliveryOrder(Request $attributes)
    {
        $deliveryOrder = new DeliveryOrder();
        $deliveryOrder->customer_id = $attributes->customer_id;
        $deliveryOrder->assign_date = $attributes->date;
        $deliveryOrder->order_id = $attributes->order_id;
        $deliveryOrder->delivery_boy_id = $attributes->delivery_boy_id;
        Order::where('id',$attributes->order_id)
        ->update([
            'delivery_boy_id' => $attributes->delivery_boy_id,
            'status' => 3,
        ]);
        $deliveryOrder->save();
    }
    public function updateDeliveryOrder($request,$id)
    {
        $deliveryOrder = $this->model->find($id);
        Order::where('id',$request->order_id)
        ->update([
            'delivery_boy_id' => $request->delivery_boy_id,
            'status' => 3,
        ]);
        $deliveryOrder->update($request->all());
        return $deliveryOrder;
    }
    public function deleteDeliveryOrder($id)
    {
        $deliveryOrder = $this->model->find($id);
        $deliveryOrder->delete();
        return $deliveryOrder;
    }

}
