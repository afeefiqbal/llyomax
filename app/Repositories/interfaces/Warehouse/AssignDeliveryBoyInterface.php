<?php

namespace App\Repositories\interfaces\Warehouse;

use App\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

interface AssignDeliveryBoyInterface
{
    public function getModel();
    public function listDeliveryOrders();
    public function getDeliveryOrderById($id);
    public function listDeliveryOrderByDB();
    public function createDeliveryOrder(Request $attributes);
    public function updateDeliveryOrder($request,$id);
    public function deleteDeliveryOrder($id);
}
