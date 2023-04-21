<?php

namespace App\Models\Warehouse;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryOrder extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'delivery_order';

    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }
    protected $dates = ['assign_date','delivery_date'];

    public function deliveryBoy(){
        return $this->belongsTo(DeliveryBoy::class,'delivery_boy_id');
    }
    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
