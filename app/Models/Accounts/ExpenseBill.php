<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ExpenseBill extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;
    protected $table = 'bills';

    protected $fillable = [
        'date',
        'amount',
        'electricity_bill',
    ];
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('bills');
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('bill')->optimize();

    }
    public function parseImage()
    {
        return [
            "url" => $this->hasMedia('bills') ? $this->getFirstMediaUrl('bill') : "https://2.bp.blogspot.com/-eu7iHP0VeHY/XHMby8JSRRI/AAAAAAAAKUo/zcLJN8JN1yoZ3q0eNxgmjPXRN9UbPFEwgCLcBGAs/s1600/red-velvet-macarons.jpg"
        ];
    }

}
