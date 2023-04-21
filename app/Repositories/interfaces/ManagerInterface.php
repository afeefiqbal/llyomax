<?php

namespace App\Repositories\interfaces;

use Illuminate\Http\Request;
use App\Repositories\RepositoryInterface;

interface ManagerInterface extends RepositoryInterface
{
    public function listManagers();
    public function createManager(Request $request);
    public function updateManager(Request $request,$id);
}
