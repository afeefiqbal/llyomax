<?php

namespace App\Models\Warehouse;

use App\Models\OrderProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'customer_id',
        'customer_scheme_id',
        'order_date',
    ];
    protected $dates = ['order_date'];
    public function products()
    {
        return $this->hasMany(OrderProduct::class);
    }
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }
    public function deliveryOrder()
    {
        return $this->hasOne('App\Models\Warehouse\DeliveryOrder');
    }
}
