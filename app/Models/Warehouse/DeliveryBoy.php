<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryBoy extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'delivery_boys';
    public $fillable = [
        'delivery_boy_id',
        'name',
        'user_id',
        'address',
        'branch_id',
        'password',
        'username',
        'phone',
        'email',
        'username',
        'place',
    ];
}
