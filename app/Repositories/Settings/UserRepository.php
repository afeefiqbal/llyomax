<?php

namespace App\Repositories\Settings;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;
use App\Repositories\interfaces\Settings\UserInterface;

class UserRepository extends BaseRepository implements UserInterface
{
    public function getModel()
    {
        return User::class;
    }
    public function listUsers()
    {
        $users = User::get();
        return $users;
    }
    public function getUser($id)
    {
        $user = User::find($id);
        return $user;
    }
    public function createUser($request)
    {
        $user = new User();
      $user->name  = $request->name;
      $user->mobile = $request->phone;
      $user->email = $request->email;
      $user->username = $request->username;
      $user->password = Hash::make($request->password);
      $user->status = $request->is_active == 'on' ? true : false;
      $user->is_admin = $request->role == 'customer' ? false : true;

      $user->save();

      $user->assignRole($request->role);
      return $user;
    }
    public function updateUser($request, $id)
    {
        return DB::transaction(function() use($request,$id) {

            $user = User::findOrFail($id);

            if($user->hasRole('customer')){

                $user->customer->update([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                ]);

            }

            $user->name  = $request->name;
            $user->mobile = $request->phone;
            $user->email = $request->email;
            $user->username = $request->username;
            if($request->has('password'))
            {
                $user->password = Hash::make($request->password);
            }
            $user->status = $request->is_active == 'on' ? true : false;
            $user->is_admin = $request->role == 'customer' ? false : true;
            $user->save();

            $user->syncRoles($request->role);
            return $user;

        });
    }
    public function deleteUser($id)
    {
        User::find($id)->syncRoles([]);
        return User::destroy($id);
    }
}
