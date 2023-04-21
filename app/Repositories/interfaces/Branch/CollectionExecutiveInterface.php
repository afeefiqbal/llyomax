<?php

namespace App\Repositories\interfaces\Branch;

use App\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

interface CollectionExecutiveInterface extends RepositoryInterface
{
    public function listCustomerCollectionExecutives();
    public function listBranchCustomerCollectionExecutives($id);
    // public function getCustomer(Int $id);
    public function createCollectionExecutive(Request $args);
    public function updateCollectionExecutive(Request $args,$id);
    public function deleteCollectionExecutive(Int $id);
    public function assignCollectionExecutives(Request $args);
}
