<?php

namespace App\Http\Controllers\Admin\Master;

use Exception;
use Illuminate\Http\Request;
use App\Models\Master\Branch;
use Yajra\DataTables\DataTables;
use App\Models\Master\OfficeAdmin;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Office_admin\Staff;
use App\Repositories\interfaces\OfficeAdminInterface;

class OfficeAdminController extends Controller
{
    protected $OfficeAdminInterface;
    public function __construct(OfficeAdminInterface $OfficeAdminInterface)
    {
        $this->OfficeAdminInterface = $OfficeAdminInterface;
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
                $office = $this->OfficeAdminInterface->listOfficeAdmins();
                return DataTables::of($office)
                    ->addIndexColumn()
                    ->addColumn('branch', function ($row) {
                        $branch = Branch::find($row->branch_id);
                        return isset($branch)? $branch->branch_name.'-'.$branch->branch_name : '';
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="office-admins/' . $row->id . '" data-id="' . $row->id . '" class="view btn btn-primary btn-floating btn-sm">
                                <i class="la la-eye"></i>
                            </a>
                        <a href="office-admins/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
                            <i class="la la-pencil"></i>
                        </a>
                        <a data-id="' . $row->id . '" class="delete btn btn-danger btn-floating btn-sm">
                            <i class="la la-trash"></i>
                        </a>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'branch'])
                    ->make(true);
            }
            return view('backend.master.office-admin.list-office-admin');
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
        return view('backend.master.office-admin.create-office-admin', compact('branches'));
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
            // 'username' => 'required|string',
            'mobile' => 'required | regex:/^([0-9\s\-\+\(\)]*)$/ | unique:users,mobile',

            'password' => 'required | min:8',
            'address' => 'required',
        ]);
        return $office = $this->OfficeAdminInterface->createOfficeAdmin($request);
        try {
            if ($office) {
                return response()->json(['success' => 'office admin successfully created']);
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
     * @param  \App\Models\Master\OfficeAdmin  $officeAdmin
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $office = OfficeAdmin::find($id);
        $branch = Branch::find($office->branch_id);
        return view('backend.master.office-admin.view-office-admin', compact('branch', 'office'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Master\OfficeAdmin  $officeAdmin
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $branches = Branch::get();
        $office = OfficeAdmin::find($id);
        return view('backend.master.office-admin.create-office-admin', compact('office', 'branches'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Master\OfficeAdmin  $officeAdmin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = OfficeAdmin::find($id)->user_id;
        $request->validate([
            'name' => 'required | string',
            'username' => 'required|string',
            'mobile' => 'required | regex:/^([0-9\s\-\+\(\)]*)$/ | unique:users,mobile,' . $user,
            'address' => 'required',
        ]);
        try {
            $office = $this->OfficeAdminInterface->updateOfficeAdmin($request, $id);
            if ($office) {
                return response()->json(['success' => 'office admin successfully created']);
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
     * @param  \App\Models\Master\OfficeAdmin  $officeAdmin
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // $office = OfficeAdmin::find($id)->user->delete();
            $officeadmin = OfficeAdmin::find($id)->delete();
            if ($officeadmin) {
                return response()->json(['success' => 'office admin successfully Deleted']);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
}
