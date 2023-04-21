<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\RentAllowance;
use App\Repositories\BaseRepository;
use App\Repositories\interfaces\Accounts\RentAllowanceInterface;

class RentAllowanceRepository extends BaseRepository implements RentAllowanceInterface
{
    public function getModel()
    {
        return RentAllowance::class;
    }
    public function listRentAllowances(){
        return $this->model->all();
    }
    public function getRentAllowanceById($id){

        return $this->model->find($id);
    }
    public function createRentAllowance($request){
        $rentAllowance = $this->model->create($request->all());
        if (isset($request->bill_doc)) {
            $expDoc =    $rentAllowance->addMediaFromBase64(json_decode($request->bill_doc)->data)
                   ->toMediaCollection('rent_allowances');
                   $rentAllowance->bill_doc = $expDoc;
               }
        return $rentAllowance;

    }
    public function updateRentAllowance($request, $id){
        $rentAllowance = $this->model->find($id);
        $rentAllowance->update($request->all());
        if (isset($request->bill_doc)) {
            $rentAllowance->clearMediaCollection('rent_allowances');
            $expDoc =    $rentAllowance->addMediaFromBase64(json_decode($request->bill_doc)->data)
                   ->toMediaCollection('rent_allowances');
                   $rentAllowance->bill_doc = $expDoc;
               }
        return $rentAllowance;

    }
    public function deleteRentAllowance($id){
        $rentAllowance = $this->model->find($id);
        $rentAllowance->delete();
        return $rentAllowance;

    }
}
