<?php

namespace App\Repositories\interfaces\Accounts;

use App\Repositories\RepositoryInterface;

interface TransportationAllowanceInterface extends RepositoryInterface
{
    public function listTransportationAllowance();
    public function getTransportationAllowance($id);
    public function createTransportationAllowance($request);
    public function updateTransportationAllowance($request, $id);
    public function deleteTransportationAllowance($id);
}
