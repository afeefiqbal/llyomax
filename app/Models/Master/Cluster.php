<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cluster extends Model
{
    use HasFactory, SoftDeletes;
    public $fillable = ['cluster_id','name','district_id'];
    public function district(){
        return $this->belongsTo('App\Models\Master\District');
    }
    public function branches(){
        return $this->hasMany('App\Models\Master\Branch');
    }
}
