<?php

namespace App\Models\Executive;

use App\Models\Master\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Executive extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'manager_id',
        'executive_id',
        'branch_id',
        'username',
        'executive_type',
        'name',
        'email',
        'phone',
        'password',
        'status',
        'place',
        'staff_id',
        'collection_area_id',
        'number_of_executives',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
