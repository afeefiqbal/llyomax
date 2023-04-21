<?php

namespace App\Repositories\interfaces\Accounts;

use App\Repositories\RepositoryInterface;

interface SalaryIndividualInterface extends RepositoryInterface
{
    public function listSalaaryIndividuals();
    public function getSalaryIndividualById($id);
    public function createSalaryIndividual($request);
    public function updateSalaryIndividual($request, $id);
    public function deleteSalaryIndividual($id);


}
