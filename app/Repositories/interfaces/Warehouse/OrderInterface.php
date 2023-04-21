<?php

namespace App\Repositories\interfaces\Warehouse;

use App\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

interface OrderInterface extends RepositoryInterface
{
    public function listOrders();
    public function listOrderByDeliveryBoy();
    public function getOrderById($id);
    public function createOrder(Request $attributes);
    public function updateOrder($request,$id);
    public function deleteOrder($id);
}
