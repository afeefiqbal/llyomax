<?php

namespace App\Repositories\Branch;

use App\Models\Branch\BranchScheme;
use App\Models\Master\Branch;
use App\Models\Master\District;
use App\Models\Scheme;
use App\Repositories\BaseRepository;
use App\Repositories\Branch\BranchSchemeInterface;

class BranchSchemeRepository extends BaseRepository implements BranchSchemeInterface
{
    public function getModel()
    {
        return Scheme::class;
    }
    public function listBranchSchemes()
    {
        return District::with('clusters')->get();
    }
    public function createBranchScheme($request)
    {
        foreach ($request->branch_id as $branch) {
            $branch = Branch::find($branch);
            $branch->update(['scheme_id' => $request->scheme_id]);
        }
        return $branch;
    }
    public function updateBranchScheme($request, $id)
    {

    }
    public function deleteBranchScheme($id)
    {

    }

}
