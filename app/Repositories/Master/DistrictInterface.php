<?php

namespace App\Repositories\Master;

use App\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

interface DistrictInterface extends RepositoryInterface
{
    public function listDistricts();
    public function createDistrict(Request $request);
    public function updateDistrict($request,$id);
    public function deleteDistrict($id);
}
