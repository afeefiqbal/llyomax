<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\SalaryIncentive;
use App\Repositories\BaseRepository;
use App\Repositories\interfaces\Accounts\SalaryIncentiveInterface;

class SalaryIncentiveRepository extends BaseRepository implements SalaryIncentiveInterface
{
    public function getModel()
    {
        return SalaryIncentive::class;
    }
    public function listSalaryIncentives()
    {
        return $this->model->all();
    }
    public function getSalaryIncentiveById($id)
    {

        return $this->model->find($id);
    }
    public function createSalaryIncentive($request)
    {
        $salaryIncentive = $this->model->create($request->all());
        return $salaryIncentive;

    }
    public function updateSalaryIncentive($request, $id)
    {
        $salaryIncentive = $this->model->find($id);
        $salaryIncentive->update($request->all());

        return $salaryIncentive;

    }
    public function deleteSalaryIncentive($id)
    {
        $salaryIncentive = $this->model->find($id);
        $salaryIncentive->delete();
        return $salaryIncentive;

    }
}
