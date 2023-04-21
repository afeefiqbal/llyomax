<?php

namespace App\Http\Controllers\Admin\Master;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Master\Branch;
use App\Models\Master\Manager;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Executive\Executive;
use App\Models\Office_admin\Staff;
use App\Repositories\interfaces\ManagerInterface;

class ManagerController extends Controller
{
    protected $managerInterface;

    public function __construct(ManagerInterface $managerInterface)
    {
        $this->managerInterface = $managerInterface;
    }
    public function index(Request $request)
    {

        try {

            if ($request->ajax()) {


                $Manager = $this->managerInterface->listManagers();
                return DataTables::of($Manager)
                    ->addIndexColumn()
                    ->addColumn('branch', function ($row) {
                        if($row->branch_id != null)
                        {
                            $branch = Branch::where('id',$row->branch_id)->first();
                            return $branch->branch_name ?? '';
                        }
                        else{
                            return '';
                        }
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="managers/' . $row->id . '" data-id="' . $row->id . '" class="view btn btn-primary btn-floating btn-sm">
                                <i class="la la-eye"></i>
                            </a>
                        <a href="managers/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
                            <i class="la la-pencil"></i>
                        </a>
                        <a data-id="' . $row->id . '" class="delete btn btn-danger btn-floating btn-sm">
                            <i class="la la-trash"></i>
                        </a>';
                        return $btn;
                    })
                    ->rawColumns(['action','branch'])
                    ->make(true);
            }
            return view('backend.master.manager.list-managers');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
    public function create()
    {
        $branches = Branch::get();
        return view('backend.master.manager.create-manager',compact('branches'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required | string',
            // 'username' => 'required|string',
            'type' => 'required',
            'branch' => 'nullable|string',
            'mobile' => 'required | regex:/^([0-9\s\-\+\(\)]*)$/ | unique:users,mobile',
            // 'email' => 'unique:users,email',
            'password' => 'required | min:8'
        ]);


        try {
             $Manager = $this->managerInterface->createManager($request);

            if ($Manager) {
                return response()->json(['success' => 'Manager successfully created']);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
    public function show($id)
    {

        $manager = Manager::find($id);
        $branch = Branch::find($manager->branch_id);
        $executives = Executive::where('manager_id',$manager->id)->get();
        return view('backend.master.manager.view-manager',compact('manager','branch','executives'));
    }
    public function edit($id)
    {
        $branches = Branch::get();
        $manager = Manager::find($id);
        return view('backend.master.manager.create-manager',compact('manager','branches'));
    }
    public function update(Request $request,$id)
    {
        $user = Manager::find($id)->user_id;
        $request->validate([
            'name' => 'required | string',
            // 'mobile' => 'required | regex:/^([0-9\s\-\+\(\)]*)$/ | unique:managers,mobile,'.$id,
            // 'mobile' => 'required | regex:/^([0-9\s\-\+\(\)]*)$/ | unique:users,mobile,'.$user,
            // 'email' => 'required | email | unique:users,email,'.$user,
            'username' => 'required|string',
            'branch' => 'nullable|string',
        ]);

        return $manager = $this->managerInterface->updateManager($request,$id);
        try {

            if ($manager) {
                return response()->json(['success' => 'Marketing Manager details successfully updated']);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
    public function destroy($id)
    {
        $manager = Manager::find($id);
        $manager->user->delete();
        $manager->delete();
        return true;
    }
}
