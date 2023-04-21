<?php

namespace App\Models\Office_admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $table  = 'staffs';
    protected $fillable = [
        'staff_id',
        'status',
        'name',
        'user_id',
        'phone',
        'designation',
        'branch_id',
    ];
    public function attendance()
    {
        return $this->hasOne(Attendance::class, 'staff_id');
    }
}
