<?php

namespace App\Models;

use App\Models\Executive\Executive;
use App\Models\Master\Area;
use App\Models\Master\Branch;
use App\Models\Warehouse\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    use HasFactory;
    public $fillable = [
        'name',
        'parent_name',
        'phone',
        'phone_2',
        'email',
        'password',
        'username',
        'pincode',
        'place',
        'area_id',
        'building',
        'land_mark',
        'city',
        'address',
        'user_id',
        'status',
        'customer_id',
        'executive_id',
        'otp',
        'branch_id',
        'referenced_id',
    ];
    public function customerScheme()
    {
        return $this->hasMany(CustomerScheme::class);
    }
    public function executive()
    {
        return $this->belongsTo(Executive::class,'executive_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class,'branch_id');
    }
    public function area()
    {
        return $this->belongsTo(Area::class,'area_id');
    }
    public function orders(){
        return $this->hasMany(Order::class,'customer_id');
    }
}
