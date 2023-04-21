<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\TransportationAllowance;
use App\Repositories\BaseRepository;
use App\Repositories\interfaces\Accounts\TransportationAllowanceInterface;

class TransportationAllowanceRepository extends BaseRepository implements TransportationAllowanceInterface
{
    public function getModel()
    {
        return TransportationAllowance::class;
    }
    public function listTransportationAllowance()
    {
        return $this->model->all();
    }
    public function getTransportationAllowance($id)
    {
        return $this->model->find($id);
    }
    public function createTransportationAllowance($request)
    {
        $transportationAllowance = $this->model->create($request->all());
        if (isset($request->bill_doc)) {
            $expDoc =    $transportationAllowance->addMediaFromBase64(json_decode($request->bill_doc)->data)
                   ->toMediaCollection('transportation_allowances');
                   $transportationAllowance->bill_doc = $expDoc;
               }
        return $transportationAllowance;
    }
    public function updateTransportationAllowance($request, $id)
    {
        $transportationAllowance = $this->model->find($id);
        $transportationAllowance->update($request->all());
        if (isset($request->bill_doc)) {
            $transportationAllowance->clearMediaCollection('transportation_allowances');
            $expDoc =    $transportationAllowance->addMediaFromBase64(json_decode($request->bill_doc)->data)
                   ->toMediaCollection('transportation_allowances');
                   $transportationAllowance->bill_doc = $expDoc;
               }
        return $transportationAllowance;
    }
    public function deleteTransportationAllowance($id)
    {
        $transportationAllowance = $this->model->find($id);
        $transportationAllowance->delete();
        return $transportationAllowance;
    }
}
