<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advance extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'advance_salaries';
    protected $fillable = [
        'date',
        'amount',
        'name_of_employee',
        'staff_id',
        'designation',
    ];
}
