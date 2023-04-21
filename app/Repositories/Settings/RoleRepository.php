<?php

namespace App\Repositories\Settings;

use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Repositories\BaseRepository;
use App\Repositories\interfaces\Settings\RoleInterface;

class RoleRepository extends BaseRepository implements RoleInterface
{
    public function getModel()
    {
        return Role::class;
    }
    public function listRoles()
    {
        $roles = Role::get();
        return $roles;
    }
    public function createRole($request)
    {
        $role = new Role();

      $role->name = Str::snake($request->display_name, '-');
      // $role->description = $args['description'];
      $role->save();

      return $role;
    }
    public function updateRole($request, $id)
    {
        $role = Role::find($id);
        $role->name = Str::snake($request->display_name, '-');
        $role->save();

      return $role;
    }
    public function deleteRole($id)
    {
        return Role::destroy($id);
    }
}
