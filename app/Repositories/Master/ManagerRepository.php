<?php

namespace App\Repositories\Master;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Master\Manager;
use App\Models\Office_admin\Staff;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;
use App\Repositories\interfaces\ManagerInterface;
use Illuminate\Support\Facades\DB;

class ManagerRepository extends BaseRepository implements ManagerInterface
{
    public function getModel()
    {
        return \App\Models\Master\Manager::class;
    }
    public function listManagers()
    {
        $managers = Manager::get();
        return $managers;
    }
    public function createManager(Request $request)
    {

        return DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
                'username' => $request->name,
                'is_admin' => 1,
                'status' => $request->status == 'on' ? true : false
            ]);
            if ($user) {
                $type = $request->type;
                if ($type == 0) {
                    $user->assignRole('marketing-manager');
                    $managerId = Manager::where('type', 0)->orderBy('id', 'desc')->first();
                    if ($managerId != null) {
                        $id = substr($managerId->manager_id, 3);
                        $id = $id + 1;
                        $manager_id = 'LMM' . $id;
                    } else {
                        $manager_id = 'LMM1';
                    }
                } elseif ($type == 1) {
                    $user->assignRole('branch-manager');
                    $managerId = Manager::where('type', 1)->orderBy('id', 'desc')->first();
                    if ($managerId != null) {
                        $id = substr($managerId->manager_id, 3);
                        $id = $id + 1;
                        $manager_id = 'LBM' . $id;
                    } else {
                        $manager_id = 'LBM1';
                    }
                    $staff = Staff::create([
                        'staff_id' => $manager_id,
                        'branch_id' => $request->branch,
                        'name'  =>  $request->name,
                        'phone' => $request->mobile,
                        'designation' => 'Branch Manager',
                        'user_id' => $user->id,
                    ]);
                } else {
                    $user->assignRole('collection-manager');
                    $managerId = Manager::where('type', 2)->orderBy('id', 'desc')->first();
                    if ($managerId != null) {
                        $id = substr($managerId->manager_id, 3);
                        $id = $id + 1;
                        $manager_id = 'LCM' . $id;
                    } else {
                        $manager_id = 'LCM1';
                    }
                    $staff = Staff::create([
                        'staff_id' => $manager_id,
                        'branch_id' => $request->branch,
                        'name'  =>  $request->name,
                        'phone' => $request->mobile,
                        'designation' => 'Collection Manager',
                        'user_id' => $user->id,
                    ]);
                }
            $manager = Manager::create([
                    'user_id' => $user->id,
                    'username' => $request->name,
                    'manager_id' => $manager_id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => $request->password,
                    'mobile' => $request->mobile,
                    'type' => $request->type,
                    'staff_id' => ($type == 0 ? NULL : $staff->id),
                    'branch_id' => isset($request->branch)  ? $request->branch  : null,
                    'status' => $request->status == 'on' ? true : false,
                ]);
            }
            return $manager;
        });
    }
    public function updateManager(Request $request, $id)
    {
        $manager = Manager::find($id);

        $staffDesignation = Staff::where('id', $manager->staff_id)->first();

        if(isset($designation)){
            $designation  = $staffDesignation->designation;
        }
        else {
            $designation = null;
        }
        if ($manager->type == $request->type) {

            $manager->manager_id = $manager->manager_id;
            $staffDesignation = Staff::where('id', $manager->staff_id)->first();
            if(isset( $staffDesignation->designation)){
                $designation  = $staffDesignation->designation;
            }
            else {
                $designation = null;
            }
            $manager->designation = $designation;
        } else {
            $type = $request->type;
            if ($type == 0) {
                $mgr = Manager::where('type', 0)->orderBy('id', 'desc')->first();
                if ($mgr != null) {
                    $manager_id = substr($mgr->manager_id, 3);
                    $manager_id = $manager_id + 1;
                    $manager_id = 'LMM' . $manager_id;

                    $designation = "Marketing Manager";
                } else {
                    $manager_id = 'LMM1';
                    $designation = "Marketing Manager";
                }
            } elseif ($type == 1) {
                $mgr = Manager::where('type', 1)->orderBy('id', 'desc')->first();
                if ($mgr != null) {
                    $manager_id = substr($mgr->manager_id, 3);
                    $manager_id = $manager_id + 1;

                    $manager_id = 'LBM' . $manager_id;
                    $designation = "Branch Manager";
                } else {
                    $manager_id = 'LBM1';
                    $designation = "Branch Manager";
                }
            } else {
                $mgr = Manager::where('type', 2)->orderBy('id', 'desc')->first();
                if ($mgr != null) {
                    $manager_id = substr($mgr->manager_id, 3);
                    $manager_id = $manager_id + 1;

                    $manager_id = 'LCM' . $manager_id;
                    $designation = "Collection Manager";
                } else {
                    $manager_id = 'LCM1';
                    $designation = "Collection Manager";
                }
            }
            $manager->manager_id = $manager_id;
            $manager->designation = $designation;
        }
        return DB::transaction(function () use ($request, $manager, $id) {
            Manager::find($id)->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'manager_id' =>  $manager->manager_id,
                'type' => $request->type,
                'branch_id' => isset($request->branch)  ? $request->branch  : null,
                'status' => $request->status == 'on' ? true : false,
            ]);
            $staff = Staff::where('user_id', $manager->user_id)->update([
                'staff_id' => $manager->manager_id,
                'branch_id' => $request->branch,
                'name'  =>  $request->name,
                'phone' => $request->mobile,
                'designation' => $manager->designation,
            ]);
            $user = User::find($manager->user_id);
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->update();
            if ($manager->type == 0) {
                $user->syncRoles('marketing-manager');
            } elseif ($manager->type == 1) {
                $user->syncRoles('branch-manager');
            } else {
                $user->syncRoles('collection-manager');
            }
            return $manager;
        });
    }
}
