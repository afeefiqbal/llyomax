<?php

namespace App\Http\Controllers\Admin\Office_Admin;

use Exception;
use Illuminate\Http\Request;
use App\Models\Master\Branch;
use App\Models\Master\Manager;
use Yajra\DataTables\DataTables;
use App\Models\Office_admin\Staff;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\interfaces\office_admin\StaffInterface;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    protected $staffInterface;
    public function __construct(StaffInterface $staffInterface)
    {
        $this->middleware(['role:super-admin|developer-admin|office-administrator|branch-manager']);
        $this->staffInterface = $staffInterface;
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
                ##-- user Role--##
                $user = Auth::user();
                $userRole = $user->roles->pluck('name')->first();
                if ($userRole == 'super-admin' || $userRole == 'developer-admin') {
                    $staff = $this->staffInterface->listStaff();
                } elseif ($userRole == 'office-administrator') {
                    $staff = $this->staffInterface->listOffficAdminStaff($user->id);
                } elseif ($userRole == 'branch-manager') {
                    $staff = $this->staffInterface->listBranchStaff($user->id);
                }
                return DataTables::of($staff)
                    ->addIndexColumn()
                    ->addColumn('branch', function ($row) {
                        $branch = Branch::find($row->branch_id);
                        return $branch->branch_name ?? '';
                    })
                    ->addColumn('action', function ($row) {
                        if ($row->user_id == null) {
                            $btn = '
                            <a href="staffs/' . $row->id . '" data-id="' . $row->id . '" class="view btn btn-primary btn-floating btn-sm">
                                    <i class="la la-eye"></i>
                                </a>
                            <a href="staffs/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
                                <i class="la la-pencil"></i>
                            </a>
                            <a data-id="' . $row->id . '" class="delete btn btn-danger btn-floating btn-sm">
                                <i class="la la-trash"></i>
                            </a>';
                        } else {
                            $btn = '
                        <a href="staffs/' . $row->id . '" data-id="' . $row->id . '" class="view btn btn-primary btn-floating btn-sm">
                                <i class="la la-eye"></i>
                            </a>
                      ';
                        }
                        return $btn;
                    })
                    ->rawColumns(['action', 'branch'])
                    ->make(true);
            }
            return view('backend.office.staff.list-staff');
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
        return view('backend.office.staff.create-staff', compact('branches'));
    }
    public function manager(Request $request)
    {
        $branch = $request->branch;
        $managers  = Manager::where(['branch_id' => $branch])->get();
        return $managers;
    }
    public function staff_id(Request $request)
    {
        try {
            if ($request->ajax()) {
                $branch_id = $request->branch;
                $branch_id = Branch::find($branch_id)->branch_id;
                $last_staff = Staff::where('staff_id', 'like', '' . $branch_id . 'S%')->orderBy('id', 'desc')->first();
                if ($last_staff != null) {
                    $s_id = substr($last_staff->staff_id, 6);
                    $s_id = $s_id + 1;
                    $s_id = '' . $branch_id . 'S' . $s_id;
                } else {
                    $s_id = '' . $branch_id . 'S1';
                }
                return $s_id;
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required | string',
            'branch' => 'required',
            'mobile' => 'required | regex:/^([0-9\s\-\+\(\)]*)$/ | unique:staffs,phone',
            'staff_id' => 'required',
            'designation' => 'required',
        ]);
        try {
            $staff = $this->staffInterface->createStaff($request);
            if ($staff) {
                return response()->json(['success' => 'Staff successfully created']);
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
    public function show($id)
    {
        $staff = Staff::find($id);
        $branch = Branch::find($staff->branch_id);
        return view('backend.office.staff.view-staff', compact('staff', 'branch'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $staff  = Staff::find($id);
        $branches = Branch::get();
        return view('backend.office.staff.create-staff', compact('branches', 'staff'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required | string',
            'branch' => 'required',
            'mobile' => 'required | regex:/^([0-9\s\-\+\(\)]*)$/ | unique:staffs,phone,' . $id,
            'staff_id' => 'required',
            'designation' => 'required',
        ]);
        try {
            $staff = $this->staffInterface->updateStaff($request, $id);
            if ($staff) {
                return response()->json(['success' => 'Staff successfully updated']);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $staff = Staff::find($id)->delete();
        return true;
    }
}
