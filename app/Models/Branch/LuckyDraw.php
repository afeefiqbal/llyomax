<?php

namespace App\Models\Branch;

use App\Models\Customer;
use App\Models\Master\Branch;
use App\Models\Scheme;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LuckyDraw extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $fillable = [
        'scheme_id',
        'branch_id',
        'customer_id',
        'week',
        'draw_date'
    ];
    public function scheme()
    {
        return $this->belongsTo(Scheme::class, 'scheme_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
