<?php

namespace App\Repositories\interfaces\Master;

use Illuminate\Http\Request;
use App\Repositories\RepositoryInterface;

interface AreaInterface extends RepositoryInterface
{
    public function listAreas();
    public function createArea(Request $request);
    public function updateArea($request,$id);
}
