<?php

namespace App\Repositories\office_admin;

use App\Models\Master\Branch;
use App\Models\Office_admin\Attendance;
use App\Models\Office_admin\Staff;
use App\Repositories\BaseRepository;
use App\Repositories\interfaces\office_admin\AttendanceInterface;
use Carbon\Carbon;

class AttendanceRepository extends BaseRepository implements AttendanceInterface
{
    public function getModel()
    {
        return \App\Models\Office_admin\Attendance::class;
    }
    public function listAttendance($branch_id)
    {
        return $branch = Branch::get();
    }
    public function createAttendance($request)
    {
        $attendenceBranch = Attendance::where(['date' => Carbon::today()->toDateString(), 'branch_id' => $request->branch])->first();
        if ($attendenceBranch == null) {
            $attendence = $this->addAttendence($request);
        } else {
            $attendenceBranch = Attendance::where(['date' => Carbon::today()->toDateString(), 'branch_id' => $request->branch])->delete();
            if ($attendenceBranch) {
                $attendence = $this->addAttendence($request);
            }
        }
        return $attendence;
    }
    public function addAttendence($request)
    {
        $staffs = Staff::where('branch_id', $request->branch)->get();
        foreach ($staffs as $staff) {
            if ($request->has('attendance-' . $staff->id . '')) {
                $attendance = 1;
            } else {
                $attendance = 0;
            }
            if ($request->has('late-' . $staff->id . '')) {
                $late = 1;
            } else {
                $late = 0;
            }
            $staff = Attendance::create([
                'late' => $late,
                'attendance' => $attendance,
                'staff_id' => $staff->id,
                'user_id' => $staff->user_id,
                'name' => $staff->name,
                'date' => Carbon::today()->toDateString(),
                'branch_id' => $request->branch,
            ]);
        }
        return true;
    }
    // public function updateAttendance($request, $date, $branch)
    // {
    //     $staffs = Staff::where('branch_id', $branch)->get();
    //     foreach ($staffs as $staff) {
    //         if ($request->has('attendance-' . $staff->id . '')) {
    //             $attendance = true;
    //         } else {
    //             $attendance = false;
    //         }
    //         if ($request->has('late-' . $staff->id . '')) {
    //             $late = true;
    //         } else {
    //             $late = false;
    //         }
    //         $update = Attendance::where(['branch_id' => $branch, 'date' => $date, 'staff_id' => $staff->id])->first();
    //         $update->attendance = $attendance;
    //         $update->late = $late;
    //         $update->update();
    //     }
    //     return true;
    // }
}
