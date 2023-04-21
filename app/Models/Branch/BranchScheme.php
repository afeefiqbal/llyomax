<?php

namespace App\Models\Branch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchScheme extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $table = 'branch_scheme';

}
