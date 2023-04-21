<?php

namespace App\Repositories\Master;

use App\Models\Master\OfficeAdmin;
use App\Models\Office_admin\Staff;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;
use App\Repositories\interfaces\OfficeAdminInterface;
use Illuminate\Support\Facades\DB;

class OfficeAdminRepository extends BaseRepository implements OfficeAdminInterface
{
    public function getModel()
    {
        return \App\Models\Master\OfficeAdmin::class;
    }
    public function listOfficeAdmins()
    {
        $office = OfficeAdmin::get();
        return $office;
    }
    public function createOfficeAdmin($request)
    {
        return DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
                'username' => $request->name,
                'status' => $request->status == 'on' ? true : false,
                'is_admin' => 1,
             ]);
             if($user){
                $office  = OfficeAdmin::orderBy('id', 'desc')->first();
                if($office != null){
                    $id = substr($office->admin_id,3);
                    $id = $id+1;
                    $admin_id = 'LOA'.$id;
                }else{
                    $admin_id = 'LOA1';
                }
                $staff = Staff::create([
                    'staff_id' => $admin_id,
                    'branch_id' => $request->branch,
                    'name'  =>  $request->name,
                    'phone' => $request->mobile,
                    'designation' => 'Office Admin',
                    'user_id' => $user->id,
                ]);
                $office = OfficeAdmin::create([
                    'user_id' => $user->id,
                    'username' => $request->name,
                    'admin_id' => $admin_id,
                    'address' => $request->address,
                    'name' => $request->name,
                    'email' => $request->email,
                    'staff_id' => $staff->id,
                    'phone' => $request->mobile,
                    'designation' => 'Office Admin',
                    'branch_id' => $request->branch,
                    'status' =>  $request->status == 'on' ? true : false,
                    'password' => $request->password,
                ]);
             }
             $user->assignRole('office-administrator');
             return $office;
        });

    }
    public function updateOfficeAdmin($request, $id)
    {
       // dd($request);
        $officeAdmin = OfficeAdmin::where('id',$id)->first();
        return DB::transaction(function () use ($request,$officeAdmin,$id) {

            $user = User::where('id',$officeAdmin->user_id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
                'username' => $request->name,
                'status' => $request->status == 'on' ? true : false,
                'is_admin' => 1,
             ]);
             if($user){
                 $office  = OfficeAdmin::orderBy('id', 'desc')->first();
                 if($office != null){
                     $admin_id = substr($office->admin_id,3);
                     $admin_id = $id+1;
                     $admin_id = 'LOA'.$id;
                 }else{
                     $admin_id = 'LOA1';
                 }
                 $staff = Staff::where('user_id',$officeAdmin->user_id)->update([
                     'staff_id' => $admin_id,
                     'branch_id' => $request->branch,
                     'name'  =>  $request->name,
                     'phone' => $request->mobile,
                     'designation' => 'Office Admin',

                 ]);

                    $office = OfficeAdmin::where('id',$id)->update([

                        'username' => $request->name,
                        'admin_id' => $admin_id,
                        'address' => $request->address,
                        'name' => $request->name,
                       'email' => $request->email,
                        'phone' => $request->mobile,
                        'designation' => 'Office Admin',
                        'branch_id' => $request->branch,
                        'status' =>  $request->status == 'on' ? true : false,

                    ]);


                 return $office;
             }
        });

    }
}
