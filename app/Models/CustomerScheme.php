<?php

namespace App\Models;

use App\Models\Executive\Executive;
use App\Models\Scheme;
use App\Models\Customer;
use App\Models\Master\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerScheme extends Model
{

    use HasFactory;
    use SoftDeletes;
    protected $table = 'customer_scheme';
    public $fillable = [
        'branch_id',
        'customer_id',
        'total_amount',
        'pending_amount',
        'advance_amount',
        'collection_day',
        'joining_date',
        'closing_date',
        'scheme_id',
        'executive_id',
        'next_collection_date',
        'last_paid_date',
        'completed_date',
        'status',
        'reason',
        'stop_date'
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
    public function scheme()
    {
        return $this->belongsTo(Scheme::class,'scheme_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class,'branch_id');
    }
    public function executive()
    {
        return $this->belongsTo(Executive::class,'executive_id');
    }

}
