<?php

namespace App\Repositories\interfaces\Settings;

use App\Repositories\RepositoryInterface;

interface UserInterface extends RepositoryInterface
{
    public function listUsers();
    public function getUser($id);
    public function createUser($request);
    public function updateUser($request,$id);
    public function deleteUser($id);
}
