<?php

namespace App\Models;

use App\Models\Executive\Executive;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketingExecutiveTarget extends Model
{

    use HasFactory;
    use SoftDeletes;
    protected $table = 'marketing_executive_targets';
    public $fillable = [


        'executive_id',
        'target_per_day',
        'target_per_month'

    ];

    public function executive()
    {
        return $this->belongsTo(Executive::class,'executive_id');
    }

}
