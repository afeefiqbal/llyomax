<?php

namespace App\Http\Controllers\Admin\Settings;

use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\interfaces\Settings\RoleInterface;

class RoleController extends Controller
{
    protected $roleInterface;

    public function __construct(RoleInterface $roleInterface)
    {
        $this->roleInterface = $roleInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listRoles()
    {
        $roles = $this->roleInterface->listRoles();

        foreach($roles as $role){
            $response[] = array(
                'id' => $role->id,
                'text' => $role->name
            );
        }

        return $response;
    }
    public function index(Request $request)
    {
        try {

            if($request->ajax()) {

                $outlet = $this->roleInterface->listRoles();

                return DataTables::of($outlet)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '
                        <a href="roles/'.$row->id.'/edit" class="edit btn btn-info btn-floating btn-sm">
                            <i class="la la-pencil"></i>
                        </a>
                        <a data-id="'.$row->id.'" class="delete btn btn-danger btn-floating btn-sm">
                            <i class="la la-trash"></i>
                        </a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);

            }

            return view('backend.settings.role.list-role');

        } catch(Exception $e) {
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
        return view('backend.settings.role.create-role');
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
            // 'role_name' => 'required',
            'display_name' => 'required|unique:roles,name',
            'description' => 'nullable'
        ]);

        try {

            $role = $this->roleInterface->createRole($request);

            if($role){
                return response()->json(['success' => 'Role successfully created']);
            }

        }catch(Exception $e){
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
        $role = Role::find($id);
        return view('backend.settings.role.create-role',compact('role'));
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
            // 'role_name' => 'required',
            'display_name' => 'required|unique:roles,name,'.$id,
        ]);

        try {

            $role = $this->roleInterface->updateRole($request,$id);

            if($role){
                return response()->json(['success' => 'Role successfully updated']);
            }

        }catch(Exception $e){
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
        try {

            $role = $this->roleInterface->deleteRole($id);

            if($role){
                return response()->json(['message'=>'Role successfully deleted']);
            }

        }catch(Exception $e){
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
}
