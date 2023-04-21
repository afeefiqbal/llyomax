<?php

namespace App\Http\Controllers\Admin\Office_Admin;

use Exception;
use Illuminate\Http\Request;
use App\Models\Master\Branch;
use Yajra\DataTables\DataTables;
use App\Models\Office_admin\Staff;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Master\Manager;
use App\Models\Master\OfficeAdmin;
use App\Models\Office_admin\Attendance;
use App\Repositories\interfaces\office_admin\AttendanceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    protected $attendanceInterface;
    public function __construct(AttendanceInterface $attendanceInterface)
    {
        $this->middleware(['role:super-admin|developer-admin|office-administrator|branch-manager']);
        $this->attendanceInterface = $attendanceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $user = Auth::user();
                $userRole = $user->roles->pluck('name')->first();
                if ($userRole == 'super-admin'  || $userRole == 'developer-admin') {
                    $attendance = $this->attendanceInterface->listAttendance($request);
                } elseif ($userRole == 'office-administrator') {
                    $officeadmin = OfficeAdmin::where('user_id', $user->id)->first();
                    $attendance = Branch::where('id', $officeadmin->branch_id)->get();
                } elseif ($userRole == 'branch-manager') {
                    $manager = Manager::where('user_id', $user->id)->first();
                    $attendance = Branch::where('id', $manager->branch_id)->get();
                }
                return DataTables::of($attendance)
                    ->addIndexColumn()
                    ->addColumn('branch', function ($row) {
                        return $row->branch_name;
                    })
                    ->addColumn('present', function ($row) {
                        $attendances = Attendance::where(['branch_id' => $row->id, 'date' => Carbon::now()->format('y-m-d')])->get();
                        $present = 0;
                        foreach ($attendances as $attendance) {
                            if ($attendance->attendance) {
                                $present += 1;
                            }
                        }
                        return $present;
                    })
                    ->addColumn('absent', function ($row) {
                        $attendances = Attendance::where(['branch_id' => $row->id, 'date' => Carbon::now()->format('y-m-d')])->get();
                        $absent = 0;
                        foreach ($attendances as $attendance) {
                            if (!$attendance->attendance) {
                                $absent += 1;
                            }
                        }
                        return $absent;
                    })
                    ->addColumn('late', function ($row) {
                        $attendances = Attendance::where(['branch_id' => $row->id, 'date' => Carbon::now()->format('y-m-d')])->get();
                        $late = 0;
                        foreach ($attendances as $attendance) {
                            if ($attendance->late) {
                                $late += 1;
                            }
                        }
                        return $late;
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="attendances' . '/' . $row->id . '" class="view btn btn-primary btn-floating btn-sm">
                        <i class="la la-eye"></i>
                    </a>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'branch'])
                    ->make(true);
            }
            $branches = Branch::get();
            return view('backend.office.attendence.list-attendance', compact('branches'));
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branches = Branch::get();
        return view('backend.office.attendence.create-attendence', compact('branches'));
    }
    public function getStaff(Request $request)
    {
        return   $staffs = Staff::with(['attendance' => function ($q) {
            $q->whereDate('date', Carbon::today())->get();
        }])->where('branch_id', $request->branch)->get();
        return $staffs;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $attendance = $this->attendanceInterface->createAttendance($request);
        try {
            if ($attendance) {
                return response()->json(['success' => 'Attendance successfully created']);
            } else {
                return response()->json(['warning' => 'Attendence for the day already entered']);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($branch)
    {
        $attendances = Attendance::where(['branch_id' => $branch])->whereDate('date',Carbon::today())->get();
        $branch = Branch::find($branch);
        return view('backend.office.attendence.view-attendance', compact('attendances', 'branch'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($branch)
    {
        $staffs = Staff::where('branch_id', $branch)->get();
        $branches = Branch::where('id', $branch)->first();
        $branch_id = $branch;
        return view('backend.office.attendence.create-attendence', compact('staffs', 'branches', 'branch_id'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $date, $branch)
    {
        // $request->validate([
        //     'branch' => 'required',
        // ]);
        // try {
        //     $attendance = $this->attendanceInterface->updateAttendance($request, $date, $branch);
        //     if ($attendance) {
        //         return response()->json(['success' => 'Attendance successfully updated']);
        //     }
        // } catch (Exception $e) {
        //     Log::info($e->getMessage());
        //     $e->getCode();
        //     $e->getMessage();
        //     throw $e;
        // }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $date)
    {
        // $attendance = Attendance::where(['branch_id' => $date])->delete();
        // return true;
    }
}
