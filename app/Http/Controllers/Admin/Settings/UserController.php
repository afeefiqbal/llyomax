<?php

namespace App\Http\Controllers\Admin\Settings;

use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\interfaces\Settings\UserInterface;

class UserController extends Controller
{
    protected $userInterface;

    public function __construct(UserInterface $userInterface)
    {
        $this->userInterface = $userInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            if($request->ajax()) {

                $user = $this->userInterface->listUsers();

                return DataTables::of($user)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn ='
                        <a href="users/'.$row->id.'/edit" class="edit btn btn-info btn-floating btn-sm">
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

            return view('backend.settings.users.list-users');

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
        return view('backend.settings.users.create-user');
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
            'name' => 'required',
            'email' => 'required|unique:users',
            'phone' => 'required|unique:users,mobile',
            'role' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required',
        ]);

        try{

            $user = $this->userInterface->createUser($request);

            if($user){
                return response()->json(['success' => 'User successfully created']);
            }

        } catch (Exception $e){
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
        $user = $this->userInterface->getUser($id);

        return view('backend.settings.users.create-user',compact('user'));
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
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$id,
            'phone' => 'required|unique:users,mobile,'.$id,
            'role' => 'required',
            'username' => 'required|unique:users,username,'.$id,
        ]);

        try {

            $user = $this->userInterface->updateUser($request,$id);

            if($user){
                return response()->json(['success', 'User successfully updated']);
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

            $user = $this->userInterface->deleteUser($id);

            if($user){
                return response()->json(['message'=>'User successfully deleted']);
            }

            return response()->json(['message'=>'Unable to delete user'])->setStatusCode('422');

        }catch(Exception $e){
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
}
