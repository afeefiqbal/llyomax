<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryIndividual extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'salary_of_individuals';
    protected  $fillable = [
        'date',
        'amount',
        'name_of_employee',
        'staff_id',
        'designation',

    ];

}
