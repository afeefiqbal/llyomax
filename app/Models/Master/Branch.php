<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Models\Office_admin\Attendance;
use App\Models\Scheme;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Branch extends Model implements HasMedia
{
    use HasFactory,SoftDeletes;
    use InteractsWithMedia;
    protected $fillable = [
        'branch_name',
        'address',
        'branch_id',
        'place',
        'mobile',
        'district',
        'status',
        'district_id'
    ];
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('branch_images')
            ->singleFile();
    }
    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
    public function cluster()
    {
        return $this->belongsToMany(Cluster::class, 'branch_cluster', 'branch_id', 'cluster_id');
    }
    public function scheme(){
        return $this->belongsTo(Scheme::class, 'scheme_id');
    }
}
