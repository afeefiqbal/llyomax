<?php

namespace App\Http\Controllers\Admin\Executive;

use Exception;
use Illuminate\Http\Request;
use App\Models\Master\Branch;
use App\Models\Master\Manager;
use Yajra\DataTables\DataTables;
use App\Models\Executive\Executive;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Executive\ExecutiveLeave;
use App\Repositories\interfaces\ExecutiveLeaveInterface;
use Illuminate\Support\Facades\Auth;
class ExecutiveLeaveController extends Controller
{
    protected $executiveLeaveInterface;

    public function __construct(ExecutiveLeaveInterface $executiveLeaveInterface)
    {
        $this->middleware(['role:super-admin|developer-admin|collection-manager|branch-manager|marketing-executive|collection-executive']);
        $this->executiveLeaveInterface = $executiveLeaveInterface;
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
                    $leave = $this->executiveLeaveInterface->listExecutivesLeaveform();
                }elseif ($userRole == 'collection-manager' || $userRole == 'branch-manager' ) {

                    $leave = $this->executiveLeaveInterface->listBranchExecutivesLeaveform($user->id);
                }elseif ($userRole == 'marketing-executive' || $userRole == 'collection-executive') {

                    $leave = $this->executiveLeaveInterface->listUserExecutivesLeaveform($user->id);
                }

                return DataTables::of($leave)
                    ->addIndexColumn()
                    ->addColumn('executive', function ($row) {
                        $id = Executive::find($row->executive_id)->executive_id;
                        return $id;
                    })
                    ->addColumn('manager', function ($row) {

                        $manager = Manager::find($row->manager_id);
                        return $manager->name ?? '';
                        return $manager;
                    })
                    ->addColumn('branch', function ($row) {
                        $branch = Branch::find($row->branch_id)->branch_name;
                        return $branch;
                    })
                    ->addColumn('status', function ($row) {
                        $user = Auth::user();
                        $userRole = $user->roles->pluck('name')->first();
                        if ($userRole == 'super-admin'  || $userRole == 'developer-admin'|| $userRole == 'branch-manager') {
                                if($row->status == 'Pending')
                            {
                                $btn = '
                                <a data-id="' . $row->id . '" style="color: white;" class="status btn btn-primary">Pending
                                </a>';
                                return $btn;
                            }elseif($row->status == 'Accepted')
                            {
                                return 'Accepted';
                            }else{
                                return 'Rejected';
                            }
                        }elseif ($userRole == 'collection-manager' || $userRole == 'marketing-executive' || $userRole == 'collection-executive' ) {
                            return $row->status;
                        }

                    })
                    ->addColumn('action', function ($row) {
                        $user = Auth::user();
                        $userRole = $user->roles->pluck('name')->first();
                        if ($userRole == 'super-admin'  || $userRole == 'developer-admin') {
                            $btn = '
                        <a href="leave-form/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
                            <i class="la la-pencil"></i>
                        </a>
                        <a data-id="' . $row->id . '" class="delete btn btn-danger btn-floating btn-sm">
                            <i class="la la-trash"></i>
                        </a>';
                        } elseif ($userRole == 'branch-manager' || $userRole == 'collection-manager') {
                            $btn = '';

                        } elseif ($userRole == 'marketing-executive' || $userRole == 'collection-executive') {
                            if ($row->status == 'Pending') {
                                $btn = '
                                <a href="leave-form/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
                                    <i class="la la-pencil"></i>
                                </a>
                                <a data-id="' . $row->id . '" class="delete btn btn-danger btn-floating btn-sm">
                                    <i class="la la-trash"></i>
                                </a>';
                            } else {
                                $btn = '';
                            }

                        }

                        return $btn;
                    })
                    ->rawColumns(['action','status'])
                    ->make(true);
            }
            return view('backend.executive.leave-form.list-leave-form');
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
        return view('backend.executive.leave-form.create-leave-form');
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
            'date' => 'required | date',
            'reason' => 'required | string',
        ]);


        try {
             $leave = $this->executiveLeaveInterface->createLeave($request);

            if ($leave) {
                return response()->json(['success' => 'Executive Leave successfully created']);
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $leave = ExecutiveLeave::find($id);
        return view('backend.executive.leave-form.create-leave-form',compact('leave'));
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
            'date' => 'required | date',
            'reason' => 'required | string',
        ]);


        try {
             $leave = $this->executiveLeaveInterface->UpdateLeave($request,$id);

            if ($leave) {
                return response()->json(['success' => 'Executive Leave successfully updated']);
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
        return $leave = ExecutiveLeave::find($id)->delete();
    }
    public function leave_status(Request $request,$id)
    {
        $leave = ExecutiveLeave::find($id)->update([
            'status' => $request->leave,
        ]);
        if ($leave) {
            return response()->json(['success' => 'Executive Leave successfully updated']);
        }
    }
}
