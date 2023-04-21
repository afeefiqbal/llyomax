<?php

namespace App\Repositories\interfaces\Settings;

use App\Repositories\RepositoryInterface;

interface RoleInterface extends RepositoryInterface
{
    public function listRoles();
    public function createRole($request);
    public function updateRole($request,$id);
    public function deleteRole($id);
}
