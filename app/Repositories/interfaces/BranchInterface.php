<?php

namespace App\Repositories\interfaces;

use Illuminate\Http\Request;
use App\Repositories\RepositoryInterface;

interface BranchInterface extends RepositoryInterface
{
    public function listBranches();
    public function createBranch(Request $request);
    public function updateBranch(Request $request,$id);
}
