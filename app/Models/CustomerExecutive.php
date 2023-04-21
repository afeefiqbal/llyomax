<?php

namespace App\Models;

use App\Models\Executive\Executive;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerExecutive extends Model
{

    use HasFactory;
    use SoftDeletes;
    protected $table = 'customer_executives';
    public $fillable = [

        'customer_id',
        'executive_id',
        'scheme_id',
        'branch_id'
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
    public function scheme()
    {
        return $this->belongsTo(Scheme::class,'scheme_id');
    }
    public function executive()
    {
        return $this->belongsTo(Executive::class,'executive_id');
    }
    

}
