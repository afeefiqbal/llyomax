<?php

namespace App\Repositories\interfaces\Accounts;

use App\Repositories\RepositoryInterface;

interface SalaryIncentiveInterface extends RepositoryInterface
{
    public function listSalaryIncentives();
    public function getSalaryIncentiveById($id);
    public function createSalaryIncentive($request);
    public function updateSalaryIncentive($request, $id);
    public function deleteSalaryIncentive($id);
}
