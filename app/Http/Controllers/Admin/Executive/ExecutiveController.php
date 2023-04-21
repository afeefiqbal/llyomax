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
use App\Models\Master\Area;
use App\Models\Scheme;
use App\Models\User;
use App\Repositories\interfaces\ExecutiveInterface;
use Illuminate\Support\Facades\Auth;
class ExecutiveController extends Controller
{
    protected $executiveInterface;
    public function __construct(ExecutiveInterface $executiveInterface)
    {
        $this->middleware(['role:super-admin|developer-admin|collection-manager|branch-manager']);
        $this->executiveInterface = $executiveInterface;
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
                    $executive = $this->executiveInterface->listExecutives();
                } elseif ($userRole == 'collection-manager' || $userRole == 'branch-manager' ) {
                    $executive = $this->executiveInterface->listBranchExecutives($user->id);
                }

                return DataTables::of($executive)
                    ->addIndexColumn()
                    ->addColumn('branch', function ($row) {
                        $branch = Branch::find($row->branch_id);
                        return $branch->branch_name ?? '';
                    })
                    ->addColumn('collection-area', function ($row) {
                        $area = Area::find($row->collection_area_id);
                        // return $area->name;
                        return isset($area)? $area->area_id.'-'.$area->name : '';
                    })

                    ->addColumn('action', function ($row) {
                        $user = Auth::user();
                        $userRole = $user->roles->pluck('name')->first();
                        if ($userRole == 'super-admin'  || $userRole == 'developer-admin' || $userRole == 'branch-manager') {
                            $btn = '
                            <a href="executives/' . $row->id . '" data-id="' . $row->id . '" class="view btn btn-primary btn-floating btn-sm">
                                    <i class="la la-eye"></i>
                                </a>
                            <a href="executives/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
                                <i class="la la-pencil"></i>
                            </a>
                            <a data-id="' . $row->id . '" class="delete btn btn-danger btn-floating btn-sm">
                                <i class="la la-trash"></i>
                            </a>';
                        } elseif ($userRole == 'collection-manager' ) {
                            $btn = '
                            <a href="executives/' . $row->id . '" data-id="' . $row->id . '" class="view btn btn-primary btn-floating btn-sm">
                                    <i class="la la-eye"></i>
                                </a>
                           ';
                        }

                        return $btn;
                    })
                    ->rawColumns(['action', 'branch', 'collection-area'])
                    ->make(true);
            }
            return view('backend.executive.executive.list-executives');
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
        $areas  = Area::get();
        return view('backend.executive.executive.create-executive', compact('branches','areas'));
    }
    public function getData(Request $request)
    {
        $data['areas']  = Area::where('branch_id', $request->branch_id)->get();
        $managers = Manager::where('branch_id', $request->branch_id)->where('type', $request->type_id)->get();
        $managerData = [];
        foreach ($managers as $key => $value) {
            $count = Executive::where('manager_id', $value->id)->where('executive_type', $request->type_id)->count();
            $managerData[] = [
                "id" => $value->id,
                "manager_id" => $value->manager_id,
                "name" => $value->name,
                "count" => $count
            ];
        }
        $data['managers'] = $managerData;
        return $data;
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
            'branch_id' => 'required|string',
            'mobile' => 'required | regex:/^([0-9\s\-\+\(\)]*)$/ | unique:users,mobile',
            'email' => 'required | email | unique:users,email',
            'password' => 'required | min:8',
            'place' => 'required',
            // 'area_id' => 'required',
            'type_id' => 'required',
            // 'manager' => 'required'
        ]);
        try {
            $executive = $this->executiveInterface->createExecutive($request);
            if ($executive) {
                return response()->json(['success' => 'Executive successfully created']);
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
        $executive = Executive::find($id);
        $manager = Manager::find($executive->manager_id);
        $branch = Branch::find($executive->branch_id);
        return view('backend.executive.executive.view-executive', compact('executive', 'manager', 'branch'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $areas  = Area::get();
        $executive = Executive::find($id);
        $branches = Branch::get();
        return view('backend.executive.executive.create-executive', compact('branches', 'executive','areas'));
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
        $user_id = Executive::find($id)->user_id;
        $request->validate([
            'name' => 'required | string',
            // 'username' => 'required|string',
            'branch_id' => 'required|string',
            'mobile' => 'required | regex:/^([0-9\s\-\+\(\)]*)$/ | unique:users,mobile,' . $user_id,
            'email' => 'required | email | unique:users,email,' . $user_id,
            'place' => 'required',
            // 'area_id' => 'required',
            'type_id' => 'required',
            // 'manager' => 'required'
        ]);
        try {
            $executive = $this->executiveInterface->updateExecutive($request, $id);
            if ($executive) {
                return response()->json(['success' => 'Executive successfully updated']);
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

        $executive = Executive::find($id)->delete();
        return $executive;
    }
}
