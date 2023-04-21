<?php

namespace App\Models;

use App\Models\Master\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Scheme extends Model implements HasMedia
{
    use HasFactory, SoftDeletes;
    use InteractsWithMedia;

    public $fillable = [
        'name',
        'scheme_n_id',
        'scheme_a_id',
        'branch_id',
        'details',
        'end_date',
        'start_date',
        'scheme_collection_day',
        'join_start_date',
        'join_end_date',
        'advance',
        'cluster_id',
        'total_amount',
        'status',
    ];
    public function branches(){
        return $this->hasMany(Branch::class, 'scheme_id');
    }
    public function cluster(){
        return $this->belongsTo(Cluster::class, 'cluster_id');
    }
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('scheme_images')
            ->acceptsMimeTypes(['image/jpeg', 'image/jpg','image/png']);
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('scheme_img')
        ->optimize();
        $this->addMediaConversion('scheme_thumb_img')
            ->width(100)
            ->height(100);
    }
    public function getImageAttribute()
    {
       return $this->getFirstMediaUrl('scheme_images','scheme_img');
    }
    public function customerScheme()
    {
        return $this->hasMany(CustomerScheme::class);
    }
}
