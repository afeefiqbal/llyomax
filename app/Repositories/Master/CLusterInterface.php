<?php

namespace App\Repositories\Master;

use App\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

interface CLusterInterface extends RepositoryInterface
{
    public function listClusters();
    public function createCluster(Request $request);
    public function updateCluster($request,$id);
    public function deleteCluster($id);
   
}
