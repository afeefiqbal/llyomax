<?php

namespace App\Repositories\interfaces\Accounts;

use App\Repositories\RepositoryInterface;

interface ExpenseBillInterface extends RepositoryInterface
{
    //
    public function listExpenseBill();
    public function createExpenseBill($request);
    public function updateExpenseBill($request, $id);
    public function deleteExpenseBill($id);
    public function getExpenseBill($id);
}
