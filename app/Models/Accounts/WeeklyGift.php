<?php

namespace App\Models\Accounts;

use App\Models\Branch\Branch;
use App\Models\Customer;
use App\Models\Scheme;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
class WeeklyGift extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;
    protected $table = 'weekly_gifts';
    protected $fillable = [
        'date',
        'amount',
        'scheme_id',
        'given_by',
        'branch_id',
        'gift_items',
        'customer_id',
        'week',
    ];
    // protected $dates = ['date'];
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('weeklyGifts');
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('weeklyGift')->optimize();

    }
    public function parseImage()
    {
        return [
            "url" => $this->hasMedia('weeklyGifts') ? $this->getFirstMediaUrl('weeklyGift') : "https://2.bp.blogspot.com/-eu7iHP0VeHY/XHMby8JSRRI/AAAAAAAAKUo/zcLJN8JN1yoZ3q0eNxgmjPXRN9UbPFEwgCLcBGAs/s1600/red-velvet-macarons.jpg"
        ];
    }
    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
