<?php

namespace App\Models\Executive;

use App\Models\Customer;
use App\Models\Master\Branch;
use App\Models\Scheme;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExecutiveReportSubmission extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'scheme_reports';
    public $fillable = [
        'branch_id',
        'scheme_id',
        'customer_id',
        'advance_amount',
        'due_amount',
        'executive_id',
        'paid_date',
        'paid_week',
        'paid_amount',
        'pending_reason',
        'status'
    ];
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function scheme()
    {
        return $this->belongsTo(Scheme::class, 'scheme_id');
    }
}
