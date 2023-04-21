<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManagerReport extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $table = 'managers';
}
