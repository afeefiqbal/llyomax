<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\Expense;
use App\Repositories\BaseRepository;
use App\Repositories\interfaces\Accounts\ExpenseInterface;

class ExpenseRepository extends BaseRepository implements ExpenseInterface
{
    public function getModel()
    {
        return Expense::class;
    }
    public function listExpenses(){
        $expences = Expense::get();
        return $expences;
    }
    public function createExpense($request){
        $expense = new Expense;
        $expense->expense_name = $request->expense_name;
        $expense->date = $request->date;
        $expense->amount = $request->amount;
        $expense->particulars = $request->particulars;

        $expense->save();
        if (isset($request->bill_doc)) {
         $expDoc =    $expense->addMediaFromBase64(json_decode($request->bill_doc)->data)
                ->toMediaCollection('expense_bills');
                $expense->bill_doc = $expDoc;
            }
        return $expense;
    }
    public function updateExpense($request, $id){
        $expense = Expense::find($id);
        $expense->expense_name = $request->expense_name;
        $expense->date = $request->date;
        $expense->amount = $request->amount;
        $expense->particulars = $request->particulars;
        $expense->save();
        if (isset($request->bill_doc)) {
            $expense->clearMediaCollection('expense_bills');
            $expDoc =    $expense->addMediaFromBase64(json_decode($request->bill_doc)->data)
                   ->toMediaCollection('expense_bills');
                   $expense->bill_doc = $expDoc;
               }
        return $expense;
    }
    public function deleteExpense( $request, $id){
        $expense = Expense::find($id);
        $expense->delete();
        return $expense;


    }
    public function getExpense($id){
        $expense = Expense::find($id);
        return $expense;


    }
}
