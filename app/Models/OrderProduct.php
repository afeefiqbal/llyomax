<?php

namespace App\Models;

use App\Models\Warehouse\Order;
use App\Models\Warehouse\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProduct extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'order_product';
    protected $fillable = [
        'product_id',
        'category_id',
        'order_id',
        'qty',
        'price',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
