<?php

namespace App\Models\Branch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Branch extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;
    protected $fillable = [
        'scheme_id',
        'branch_id',
    ];
    public function scheme()
    {
        return $this->belongsTo('App\Models\Scheme');
    }
    public function customer()
    {
        return $this->hasOne('App\Models\Customer');
    }
    public function user()
    {
        return $this->hasOne('App\Models\User');
    }
    public function executive()
    {
        return $this->hasOne('App\Models\Executive\Executive');
    }
    public function cluster()
    {
        return $this->belongsTo('App\Models\Master\Cluster');
    }
    public function district()
    {
        return $this->belongsTo('App\Models\Master\District');
    }
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('branch_images')
            ->singleFile();
    }
}
