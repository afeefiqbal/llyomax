<?php

namespace App\Repositories\Master;

use App\Models\Master\Area;
use App\Repositories\BaseRepository;
use App\Repositories\interfaces\Master\AreaInterface;
use Illuminate\Http\Request;

class AreaRepository extends BaseRepository implements AreaInterface
{
    public function getModel()
    {
        return \App\Models\Master\Area::class;
    }
    public function listAreas()
    {
        $areas = Area::orderBy('id','desc')->get();
        return $areas;
    }
    public function createArea(Request $request)
    {
        $area = Area::create([
            'district_id' =>  $request->district_id,
            'area_id' => $request->area_id,
            'name' => $request->name,
        ]);
        return $area;
    }
    public function updateArea($request, $id)
    {
        $area = Area::find($id)->update([
            'branch_id' => $request->branch,
            'area_id' => $request->area_id,
            'name' => $request->name,
            'district_id' =>  $request->district_id,
        ]);

        return $area;
    }
}
