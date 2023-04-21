<?php

namespace App\Repositories\Master;

use App\Models\Master\Branch;
use App\Models\Master\District;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Repositories\BaseRepository;
use App\Repositories\interfaces\BranchInterface;

class BranchRepository extends BaseRepository implements BranchInterface
{
    public function getModel()
    {
        return \App\Models\Master\Branch::class;
    }
    public function listBranches()
    {
        $branches = Branch::get();
        return $branches;
    }
    public function createBranch(Request $request)
    {
        $district = District::where('id', $request->district_id)->first();
        $district = $district->district_id;
        $district = explode('-', $district);
        $branch = Branch::create([
            'branch_name' => $request->name,
            'address' => $request->address,
            'branch_id' => $request->branch_id,
            'mobile' => $request->mobile,
            'place' => $request->place,
            'district' => $district[1] ?? '',
            'district_id' => $request->district_id,
            'status' => ($request->status == 'on' ? 1 : 0),
        ]);

            if (isset($request->branch_image)) {
                $branch->addMediaFromBase64(json_decode($request->branch_image)->data)
                    ->usingFileName(Str::random() . '.jpeg')
                    ->toMediaCollection('branch_images');
            }
        return $branch;
    }
    public function updateBranch(Request $request, $id)
    {
        $branch = Branch::find($id);
        $branch->branch_id = $request->branch_id;
        $branch->branch_name = $request->name;
        $branch->address = $request->address;
        $branch->mobile = $request->mobile;
        $branch->place  = $request->place;
        $branch->district_id  = $request->district_id;
        $branch->status = ($request->status == 'on' ? 1 : 0);
        $branch->update();
        if (isset($request->branch_image)) {
            $branch->addMediaFromBase64(json_decode($request->branch_image)->data)
                ->usingFileName(Str::random() . '.jpeg')
                ->toMediaCollection('branch_images');
        }
        return $branch;
    }
}
