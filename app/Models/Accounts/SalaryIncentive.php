<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryIncentive extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'salary_incentives';
    protected $fillable = [
        'date',
        'total_amount',
    ];
}
