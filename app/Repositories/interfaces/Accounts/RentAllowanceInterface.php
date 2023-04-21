<?php

namespace App\Repositories\interfaces\Accounts;

use App\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

interface RentAllowanceInterface extends RepositoryInterface
{
    public function listRentAllowances();
    public function getRentAllowanceById($id);
    public function createRentAllowance(Request $request);
    public function updateRentAllowance(Request $request, $id);
    public function deleteRentAllowance($id);
}
