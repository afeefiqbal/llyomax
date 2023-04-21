<?php

namespace App\Repositories\Master;

use App\Models\Master\District;
use App\Repositories\BaseRepository;
use App\Repositories\Master\DistrictInterface;
use Illuminate\Support\Facades\DB;

class DistrictRepository extends BaseRepository implements DistrictInterface
{
    public function getModel()
    {
        return \App\Models\Master\District::class;
    }
    public function listDistricts(){
        $districts = District::orderBy('id','desc')->get();
        return $districts;
    }
    public function createDistrict($request){
        $district = District::create([
           'district_id' => $request->district_id,
            'name' => $request->name,
        ]);
        return $district;


    }
    public function updateDistrict($request,$id){
        $district = District::find($id)->update([
            'district_id' => $request->district_id,
            'name' => $request->name,
        ]);
        return $district;
    }
    public function deleteDistrict($id)
    {
        $district = District::find($id)->delete();
        return $district;
    }

}
