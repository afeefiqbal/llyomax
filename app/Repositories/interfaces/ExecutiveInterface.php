<?php

namespace App\Repositories\interfaces;

use App\Repositories\RepositoryInterface;

interface ExecutiveInterface extends RepositoryInterface
{
    public function listExecutives();
    public function listBranchExecutives($id);
    public function createExecutive($request);
    public function updateExecutive($request,$id);
}
