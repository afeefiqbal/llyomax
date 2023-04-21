<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExtraBonus extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'date',
        'type',
        'particulars',
        'amount',
        'staff_id',
    ];
}
