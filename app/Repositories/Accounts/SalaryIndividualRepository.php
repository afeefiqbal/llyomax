<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\SalaryIndividual;
use App\Repositories\BaseRepository;
use App\Repositories\interfaces\Accounts\SalaryIndividualInterface;

class SalaryIndividualRepository extends BaseRepository implements SalaryIndividualInterface
{
    public function getModel()
    {
        return SalaryIndividual::class;
    }
    public function listSalaaryIndividuals(){
        $salaryIndividuals = SalaryIndividual::get();
        return $salaryIndividuals;
    }
    public function getSalaryIndividualById($id){

            return $this->model->find($id);

    }
    public function createSalaryIndividual($request){

            $salaryIndividual = new SalaryIndividual;
            $salaryIndividual->date = $request->date;
            $salaryIndividual->amount = $request->amount;
            $salaryIndividual->name_of_employee = $request->name_of_employee;
            $salaryIndividual->staff_id = $request->employee_id;
            $salaryIndividual->designation = $request->designation;
            $salaryIndividual->save();
            return $salaryIndividual;

    }
    public function updateSalaryIndividual($request, $id){
            $salaryIndividual = SalaryIndividual::find($id);
            $salaryIndividual->date = $request->date;
            $salaryIndividual->amount = $request->amount;
            $salaryIndividual->name_of_employee = $request->name_of_employee;
            $salaryIndividual->staff_id = $request->employee_id;
            $salaryIndividual->designation = $request->designation;
            $salaryIndividual->save();
            return $salaryIndividual;


    }
    public function deleteSalaryIndividual($id){
            $salaryIndividual = SalaryIndividual::find($id);
            $salaryIndividual->delete();
            return $salaryIndividual;


    }
}
