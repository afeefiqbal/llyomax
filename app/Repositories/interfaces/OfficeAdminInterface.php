<?php

namespace App\Repositories\interfaces;

use App\Repositories\RepositoryInterface;

interface OfficeAdminInterface extends RepositoryInterface
{
    public function listOfficeAdmins();
    public function createOfficeAdmin($request);
    public function updateOfficeAdmin($request,$id);
}
