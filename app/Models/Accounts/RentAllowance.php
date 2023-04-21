<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class RentAllowance extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;
    protected $table = 'rent_allowances';

    protected $fillable = [
        'date',
        'amount',
        'type',
    ];
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('rent_allowances');
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('rent_allowance')->optimize();

    }
    public function parseImage()
    {
        return [
            "url" => $this->hasMedia('rent_allowances') ? $this->getFirstMediaUrl('rent_allowance') : "https://2.bp.blogspot.com/-eu7iHP0VeHY/XHMby8JSRRI/AAAAAAAAKUo/zcLJN8JN1yoZ3q0eNxgmjPXRN9UbPFEwgCLcBGAs/s1600/red-velvet-macarons.jpg"
        ];
    }

}
