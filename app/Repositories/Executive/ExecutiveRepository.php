<?php

namespace App\Repositories\Executive;

use App\Models\Executive\Executive;
use App\Models\Master\Manager;
use App\Models\Office_admin\Staff;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;
use App\Repositories\interfaces\ExecutiveInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExecutiveRepository extends BaseRepository implements ExecutiveInterface
{
    public function getModel()
    {
        return \App\Models\Executive\Executive::class;
    }
    public function listExecutives()
    {
        $executives = Executive::get();
        return $executives;
    }
    public function listBranchExecutives($id)
    {
        $user = Auth::user();
        $userRole = $user->roles->pluck('name')->first();
        if ($userRole == 'collection-manager') {
            $manager = Manager::where('user_id', $id)->first();
            $executives = Executive::where('manager_id', $manager->id)->where('branch_id', $manager->branch_id)->get();
        } elseif ($userRole == 'branch-manager') {
            $manager = Manager::where('user_id', $id)->first();
            $executives = Executive::where('branch_id', $manager->branch_id)->get();
        }

        return $executives;
    }
    public function createExecutive($request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'username' => $request->name,
            'status' => $request->status == 'on' ? true : false,
            'is_admin' => 1,
        ]);
        if ($user) {
            $executive  = Executive::orderBy('id', 'desc')->first();
            if ($request->type_id == 1) {
                if ($executive != null) {
                    $id = substr($executive->executive_id, 3);
                    $id = $id + 1;
                    $executive_id = 'LME' . $id;
                } else {
                    $executive_id = 'LME1';
                }
                $staff = Staff::create([
                    'staff_id' => $executive_id,
                    'branch_id' => $request->branch_id,
                    'name'  =>  $request->name,
                    'phone' => $request->mobile,
                    'designation' => 'Marketing Executive',
                    'user_id' => $user->id,
                ]);
            } elseif ($request->type_id == 2) {
                if ($executive != null) {
                    $id = substr($executive->executive_id, 3);
                    $id = $id + 1;
                    $executive_id = 'LCE' . $id;
                } else {
                    $executive_id = 'LCE1';
                }
                $staff = Staff::create([
                    'staff_id' => $executive_id,
                    'branch_id' => $request->branch_id,
                    'name'  =>  $request->name,
                    'phone' => $request->mobile,
                    'designation' => 'Collection Executive',
                    'user_id' => $user->id,
                ]);
            }
            $executive = Executive::create([
                'user_id' => $user->id,
                'username' => $request->name,
                'executive_id' => $executive_id,
                'place' => $request->place,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->mobile,
                'branch_id' => $request->branch_id,
                // 'manager_id' => $request->manager,
                'collection_area_id' => $request->area_id,
                'executive_type' => $request->type_id,
                'status' => $request->status == 'on' ? true : false,
                'password' => ($request->password),
                'staff_id' => $staff->id,
                // 'number_of_executives' => $request->no_of_executives,
            ]);
        }
        if ($request->type_id == 1) {
            $user->assignRole('marketing-executive');
        } elseif ($request->type_id == 2) {
            $user->assignRole('collection-executive');
        }
        return $executive;
    }
    public function updateExecutive($request, $id)
    {
        $executive = Executive::find($id);
        $staffDesignation = Staff::where('id', $executive->staff_id)->first();
        $designation  = $staffDesignation->designation;
        $userID = $executive->user_id;
        $type = $request->type_id;

        if ($executive->executive_type == $request->type_id) {
            $executive->executive_id = $executive->executive_id;
            $staffDesignation = Staff::where('id', $executive->staff_id)->first();
            if (isset($staffDesignation->designation)) {
                $designation  = $staffDesignation->designation;
            } else {
                $designation = null;
            }
            $executive->designation = $designation;
        } else {
            $type = $request->type_id;
            if ($type == 1) {
                $exc  = Executive::where('executive_type', 1)->orderBy('id', 'desc')->first();

                if ($exc != null) {
                    $executive_id = substr($exc->executive_id, 3);
                    $executive_id = $executive_id + 1;
                    $executive_id = 'LME' . $executive_id;
                    $designation = "Marketing Executive";
                } else {
                    $executive_id = 'LME1';
                    $designation  = 'Marketing Executive';
                }
            } else if ($type == 2) {
                $exc  = Executive::where('executive_type', 2)->orderBy('id', 'desc')->first();
                if ($exc != null) {
                    $executive_id = substr($exc->executive_id, 3);
                    $executive_id = $executive_id + 1;
                    $executive_id = 'LCE' . $executive_id;
                    $designation = "Collection Executive";
                } else {
                    $executive_id = 'LCE1';
                    $designation  = 'Collection Executive';
                }
            }


            $executive->executive_id = $executive_id;
            $executive->designation = $designation;
        }

        return DB::transaction(function () use ($request, $executive, $id, $userID, $designation) {
            $user = User::find($userID)->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'username' => $request->name,
                'status' => $request->status == 'on' ? true : false,
            ]);
            
            $user = User::find($userID);
            if ($request->type_id == 1) {
                $user->syncRoles('marketing-executive');
            } elseif ($request->type_id == 2) {
                $user->syncRoles('collection-executive');
            }

            Executive::find($id)->update([
                'username' => $request->username,
                'executive_id' => $executive->executive_id,
                'place' => $request->place,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->mobile,
                'branch_id' => $request->branch_id,
                'collection_area_id' => $request->area_id,
                'executive_type' => $request->type_id,
                'status' => $request->status == 'on' ? true : false,
            ]);

            $staff = Staff::where('user_id', $userID)->update([
                'staff_id' => $executive->executive_id,
                'branch_id' => $request->branch_id,
                'name'  =>  $request->name,
                'phone' => $request->mobile,
                'designation' => $executive->designation,
            ]);
        });
        return $executive;
    }
}
