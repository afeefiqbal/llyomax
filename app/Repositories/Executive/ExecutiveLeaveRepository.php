<?php

namespace App\Repositories\Executive;

use App\Models\Executive\Executive;
use App\Models\Executive\ExecutiveLeave;
use App\Models\Master\Manager;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use App\Repositories\interfaces\ExecutiveLeaveInterface;

class ExecutiveLeaveRepository extends BaseRepository implements ExecutiveLeaveInterface
{
    public function getModel()
    {
        return \App\Models\Executive\ExecutiveLeave::class;
    }
    public function listExecutivesLeaveform()
    {
        $leave = ExecutiveLeave::get();
        return $leave;
    }
    public function listBranchExecutivesLeaveform($id)
    {
        $user = Auth::user();
        $userRole = $user->roles->pluck('name')->first();
        if ($userRole == 'collection-manager') {
            $manager = Manager::where('user_id',$id)->first();
             $leave = ExecutiveLeave::where('manager_id',$manager->id)->where('branch_id',$manager->branch_id)->get();
        }elseif ($userRole == 'branch-manager' ) {
            $manager = Manager::where('user_id',$id)->first();
            $leave = ExecutiveLeave::where('branch_id',$manager->branch_id)->get();
        }
      
      
        return $leave;
    }
    public function listUserExecutivesLeaveform($id)
    {
        $exxecutive = Executive::where('user_id',$id)->first();
        $leave = ExecutiveLeave::where('executive_id',$exxecutive->id)->get();
        return $leave;
    }
    public function createLeave($request)
    {
        $user = Auth::user()->id;
        $executive = Executive::where('user_id',$user)->first();
        $leave  = ExecutiveLeave::create([
            'executive_id' => $executive->id,
            'branch_id' => $executive->branch_id,
            'manager_id' => $executive->manager_id,
            'date' => $request->date,
            'reason' => $request->reason,
            'name' => $executive->name,
            'phone' => $executive->phone,
        ]);

        return $leave;
    }
    public function updateLeave($request, $id)
    {
        $leave  = ExecutiveLeave::find($id)->update([
            'date' => $request->date,
            'reason' => $request->reason,
        ]);

        return $leave;
    }
}
