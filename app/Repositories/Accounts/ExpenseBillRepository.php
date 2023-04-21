<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\ExpenseBill;
use App\Repositories\BaseRepository;
use App\Repositories\interfaces\Accounts\ExpenseBillInterface;

class ExpenseBillRepository extends BaseRepository implements ExpenseBillInterface
{
    public function getModel()
    {
        return ExpenseBill::class;
    }
    public function listExpenseBill(){
        return $this->model->all();
    }
    public function createExpenseBill($request){
        $expenseBill = $this->model->create($request->all());
        if (isset($request->bill_doc)) {
            $expDoc =    $expenseBill->addMediaFromBase64(json_decode($request->bill_doc)->data)
                   ->toMediaCollection('bills');
                   $expenseBill->bill_doc = $expDoc;
               }
        return $expenseBill;
    }
    public function updateExpenseBill($request, $id){
        $expenseBill = $this->model->find($id);
        $expenseBill->update($request->all());
        if (isset($request->bill_doc)) {
            $expenseBill->clearMediaCollection('bills');
            $expDoc =    $expenseBill->addMediaFromBase64(json_decode($request->bill_doc)->data)
                   ->toMediaCollection('bills');
                   $expenseBill->bill_doc = $expDoc;
               }
        return $expenseBill;
    }
    public function deleteExpenseBill($id){
        $expenseBill = $this->model->find($id);
        $expenseBill->delete();
        return $expenseBill;
    }
    public function getExpenseBill($id){
        return $this->model->find($id);

    }
}
