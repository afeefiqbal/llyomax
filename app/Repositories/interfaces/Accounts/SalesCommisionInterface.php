<?php

namespace App\Repositories\interfaces\Accounts;

use App\Repositories\RepositoryInterface;

interface SalesCommisionInterface extends RepositoryInterface
{
    public function listSalesCommisions();
    public function getSalesCommisionById($id);
    public function createSalesCommision($request);
    public function updateSalesCommision($request, $id);
    public function deleteSalesCommision($id);
}
