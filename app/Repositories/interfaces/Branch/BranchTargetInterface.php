<?php

namespace App\Repositories\interfaces\Branch;

use App\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

interface BranchTargetInterface extends RepositoryInterface
{
    public function listbranchTargets();
    public function listSchemeTargets();
    public function listbranchUserTargets(Int $id);
    public function createBranchTarget(Request $args);
   public function updateBranchTarget(Request $args,$id);
    public function deleteBranchTarget(Int $id);
}
