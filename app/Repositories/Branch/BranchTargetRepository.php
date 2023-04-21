<?php

namespace App\Repositories\Branch;

use App\Models\BranchTarget;
use App\Models\Customer;
use App\Models\Master\Manager;
use Illuminate\Http\Request;
use App\Repositories\BaseRepository;
use App\Repositories\interfaces\Branch\BranchTargetInterface;

class BranchTargetRepository extends BaseRepository implements BranchTargetInterface
{
    public function getModel()
    {
        return Customer::class;
    }
    public function listbranchTargets()
    {
        $branchTarget = BranchTarget::where('branch_id','!=',null)->with('branch')->get();
        return $branchTarget;
    }
    public function listSchemeTargets()
    {
        $branchTarget = BranchTarget::where('scheme_id','!=',null)->with('branch')->get();
        return $branchTarget;
    }
    public function listbranchUserTargets($id)
    {
        $manager = Manager::where('user_id',$id)->first();
        $branchTarget = BranchTarget::where('branch_id',$manager->branch_id)->with('branch')->get();
        return $branchTarget;
    }
    public function createBranchTarget($args)
    {
        $branchTarget = BranchTarget::create([
            'branch_id' => $args['branch_id'],
            'scheme_id' => $args['scheme_id'],
            'target_per_month' => $args['target_per_month'],
            'target_per_day' => $args['target_per_day'],
        ]);
        return $branchTarget;
    }
    public function updateBranchTarget(Request $args, $id)
    {
        
        $branchTarget = BranchTarget::where('id', $id)
            ->update([
                'branch_id' => $args['branch_id'],
                'scheme_id' => $args['scheme_id'],
                'target_per_month' => $args['target_per_month'],
                'target_per_day' => $args['target_per_day'],

            ]);
        return $branchTarget;
    }
    public function deleteBranchTarget($id)
    {
        $branchTarget = BranchTarget::where('id', $id)->delete();
        return $branchTarget;
    }
}
