<?php

namespace App\Models\Office_admin;

use App\Models\Master\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'staff_id',
        'branch_id',
        'date',
        'name',
        'attendance',
        'late',
        'user_id'
    ];
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
