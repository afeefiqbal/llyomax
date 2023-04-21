<?php

namespace App\Models;

use App\Models\Executive\Executive;
use App\Models\Master\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchTarget extends Model
{

    use HasFactory;
    use SoftDeletes;
    protected $table = 'branch_targets';
    public $fillable = [


        'branch_id',
        'scheme_id',
        'target_per_month',
        'target_per_day'

    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class,'branch_id');
    }
    public function scheme()
    {
        return $this->belongsTo(Scheme::class,'scheme_id');
    }

}
