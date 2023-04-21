<?php

namespace App\Repositories\interfaces\Branch;

use App\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

interface MarketingExecutiveTargetInterface extends RepositoryInterface
{
    public function listMarketingExecutiveTarget();
    public function listBranchmarketingExecutiveTarget(Int $id);
    public function createMarketingExecutiveTarget(Request $args);
   public function updateMarketingExecutiveTarget(Request $args,$id);
    public function deleteMarketingExecutiveTarget(Int $id);
}
