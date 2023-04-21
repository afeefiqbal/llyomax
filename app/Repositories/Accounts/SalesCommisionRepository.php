<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\SalesCommision;
use App\Repositories\BaseRepository;
use App\Repositories\interfaces\Accounts\SalesCommisionInterface;

class SalesCommisionRepository extends BaseRepository implements SalesCommisionInterface
{
    public function getModel()
    {
        return SalesCommision::class;
    }
    public function listSalesCommisions()
    {
        return $this->model->all();
    }
    public function getSalesCommisionById($id)
    {

        return $this->model->find($id);
    }
    public function createSalesCommision($request)
    {
        $salesCommision = $this->model->create($request->all());
        return $salesCommision;

    }
    public function updateSalesCommision($request, $id)
    {
        $salesCommision = $this->model->find($id);
        $salesCommision->update($request->all());

        return $salesCommision;

    }
    public function deleteSalesCommision($id)
    {
        $salesCommision = $this->model->find($id);
        $salesCommision->delete();
        return $salesCommision;

    }
}
