<?php

namespace App\Repositories\Warehouse;

use App\Models\OrderProduct;
use App\Models\Warehouse\DeliveryBoy;
use App\Models\Warehouse\Order;
use App\Models\Warehouse\Product;
use App\Repositories\BaseRepository;
use App\Repositories\interfaces\Warehouse\OrderInterface;
use Illuminate\Support\Facades\DB;

class OrderRepository extends BaseRepository implements OrderInterface
{
    public function getModel()
    {
        return Order::class;
    }
    public function listOrders()
    {
        return $this->model->all();
    }
    public function listOrderByDeliveryBoy(){
        $deliveryBoy = DeliveryBoy::where('user_id',auth()->user()->id)->first();
        return $this->model->where('delivery_boy_id',$deliveryBoy->id)->get();
    }
    public function getOrderById($id)
    {
        return $this->model->find($id);
    }
    public function createOrder($attributes)
    {

        $order = new Order();
        $order->order_id = $attributes['order_id'];
        $order->customer_id = $attributes['customer_id'];
        $order->shipping_address = $attributes['shipping_address'];
        $order->order_date = $attributes['order_date'];
        $order->phone = $attributes['phone'];
        $order->discount = $attributes['discount'];
        $order->sub_amount = $attributes['sub_amount'];
        $order->scheme_amount = $attributes['scheme_amount'];
        $order->shipping_charge = $attributes['shipping_charge'];
        $order->net_amount = $attributes['net_amount'];
        $order->note = $attributes['note'];
        $order->quantity = $attributes['quantity'];
        $order->save();
        $cart = session()->get('cart');

        foreach($cart as $product)
        {
            $qty = $product['quantity'];
            $price = $product['sub_total'];
            $productID = $product['productId'];
            $product = Product::with('category')->where('id', $product['productId'])->first();
            $category_id = $product->category->id;
            $orderProducts = new OrderProduct();
            $orderProducts->order_id = $order->id;
            $orderProducts->category_id = $category_id;
            $orderProducts->product_id =  $productID;
            $orderProducts->qty = $qty;
            $orderProducts->price = $price;
            $orderProducts->save();
            Product::where('id', $productID)->decrement('qty', $qty);

        }
        return $order->id;
        session()->forget('cart');
    }
    public function updateOrder($request,$id)
    {
        $order = $this->model->find($id);
        $order->update($request->all());
        return $order;
    }
    public function deleteOrder($id)
    {
        $order = $this->model->find($id);
        $order->delete();
        return $order;
    }
}
