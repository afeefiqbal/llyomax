<?php

namespace App\Models\Accounts;

use App\Models\Master\Manager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesCommision extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected  $fillable = [
        'monthly',
        'from_date',
        'to_date',
        'manager_id',
        'amount'

    ];
    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }
}
