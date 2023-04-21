<?php

namespace App\Repositories\interfaces\Warehouse;

use App\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

interface DeliveryBoyInterface extends RepositoryInterface
{
    public function listdeliveryExecutives();
    public function getDeliveryBoyById($id);
    public function createDeliveryBoy(Request $attributes);
    public function updateDeliveryBoy($request,$id);
    public function deleteDeliveryBoy($id);
}
