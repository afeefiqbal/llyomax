<?php

namespace App\Repositories\office_admin;

use App\Models\Master\Manager;
use App\Models\Master\OfficeAdmin;
use App\Models\Office_admin\Staff;
use App\Repositories\BaseRepository;
use App\Repositories\interfaces\office_admin\StaffInterface;

class StaffRepository extends BaseRepository implements StaffInterface
{
    public function getModel()
    {
        return \App\Models\Office_admin\Staff::class;
    }
    public function listStaff()
    {
        $staffs = Staff::get();
        return $staffs;
    }
    public function listBranchStaff($id)
    {
        $manager = Manager::where('user_id',$id)->first();
        $staffs = Staff::where('branch_id',$manager->branch_id)->get();
        return $staffs;
    }
    public function listOffficAdminStaff($id)
    { 
        $officeadmin = OfficeAdmin::where('user_id',$id)->first();
        $staffs = Staff::where('branch_id',$officeadmin->branch_id)->get();
        return $staffs;
    }
    public function createStaff($request)
    {
                $staff = Staff::create([
                    'staff_id' => $request->staff_id,
                    'name' => $request->name,
                    'phone' => $request->mobile,
                    'branch_id' => $request->branch,
                    'designation' => $request->designation,
                    ]);
         return $staff;
    }
    public function updateStaff($request, $id)
    {
        $staff = Staff::find($id)->update([
            'staff_id' => $request->staff_id,
            'name' => $request->name,
            'phone' => $request->mobile,
            'branch_id' => $request->branch,
            'designation' => $request->designation,
        ]);
        return $staff;
    }
}
