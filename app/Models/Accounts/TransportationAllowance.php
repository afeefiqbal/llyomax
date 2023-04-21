<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
class TransportationAllowance extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;

    protected $fillable = [
        'date',
        'amount',
        'type_of_vehicle',
        'running_km',
        'complaint',
    ];
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('transportation_allowances');
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('transportation_allowance')->optimize();

    }
    public function parseImage()
    {
        return [
            "url" => $this->hasMedia('transportation_allowances') ? $this->getFirstMediaUrl('transportation_allowance') : "https://2.bp.blogspot.com/-eu7iHP0VeHY/XHMby8JSRRI/AAAAAAAAKUo/zcLJN8JN1yoZ3q0eNxgmjPXRN9UbPFEwgCLcBGAs/s1600/red-velvet-macarons.jpg"
        ];
    }
}
