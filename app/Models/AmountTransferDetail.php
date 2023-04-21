<?php

namespace App\Models;

use App\Models\Executive\Executive;
use App\Models\Master\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AmountTransferDetail extends Model implements HasMedia
{

    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;
    protected $table = 'amount_transfer_details';
    public $fillable = [


        'branch_id',
        'executive_id',
        'date',
        'transfer_amount',
        'transfer_time',
        'transfer_type'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class,'branch_id');
    }
    public function executive()
    {
        return $this->belongsTo(Executive::class,'branch_id');
    }
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('receipt_images')
            ->acceptsMimeTypes(['image/jpeg', 'image/jpg','image/png']);
    }

}
