<?php

namespace App\Models\Executive;

use App\Models\Executive\Executive;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExecutiveLeave extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'manager_id',
        'executive_id',
        'branch_id',
        'name',
        'phone',
        'date',
        'reason',
        'status'
    ];
}
