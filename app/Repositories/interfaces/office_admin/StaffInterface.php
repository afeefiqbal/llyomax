<?php

namespace App\Repositories\interfaces\office_admin;

use App\Repositories\RepositoryInterface;

interface StaffInterface extends RepositoryInterface
{
    public function listStaff();
    public function listBranchStaff($id);
    public function listOffficAdminStaff($id);
    public function createStaff($request);
    public function updateStaff($request,$id);
}
