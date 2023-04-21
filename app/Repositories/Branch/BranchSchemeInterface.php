<?php

namespace App\Repositories\Branch;

use App\Repositories\RepositoryInterface;

interface BranchSchemeInterface extends RepositoryInterface
{
    public function listBranchSchemes();
    public function createBranchScheme($request);
    public function updateBranchScheme($request, $id);
    public function deleteBranchScheme($id);

}
