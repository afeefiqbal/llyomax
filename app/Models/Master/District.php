<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class District extends Model
{
    use HasFactory, SoftDeletes;
    public $fillable = ['district_id','name'];
    public function areas(){
        return $this->hasMany('App\Models\Master\Area');
    }
    public function clusters(){
        return $this->hasMany('App\Models\Master\Cluster');
    }
}
