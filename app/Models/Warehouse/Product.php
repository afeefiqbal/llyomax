<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class Product extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;
    protected $fillable = [
        'product_code',
        'name',
        'slug',
        'sku',
        'type',
        'description',
        'mrp',
        'category_id',
        'image',
        'lrp',
        'status',
        'qty'
    ];
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('product_images')
            ->singleFile();
    }
    public function category()
    {
        return $this->belongsTo('App\Models\Warehouse\Category');
    }
}
