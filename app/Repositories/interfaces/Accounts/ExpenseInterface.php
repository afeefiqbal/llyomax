<?php

namespace App\Repositories\interfaces\Accounts;

use App\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

interface ExpenseInterface extends RepositoryInterface
{
    public function listExpenses();
    public function createExpense(Request $request);
    public function updateExpense(Request $request, $id);
    public function deleteExpense(Request $request, $id);
    public function getExpense($id);
}
