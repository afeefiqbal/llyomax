<?php

namespace App\Http\Controllers\Admin\Master;

use Exception;
use Illuminate\Http\Request;
use App\Models\Master\Branch;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Executive\Executive;
use App\Models\Master\Area;
use App\Models\Master\Cluster;
use App\Models\Master\District;
use App\Models\Master\Manager;
use App\Models\Office_admin\Staff;
use App\Models\Scheme;
use App\Repositories\interfaces\BranchInterface;
use Illuminate\Support\Str;
use DB;
class BranchController extends Controller
{
    protected $branchInterface;
    public function __construct(BranchInterface $branchInterface)
    {
        $this->branchInterface = $branchInterface;
    }
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $branch = $this->branchInterface->listBranches();
                return DataTables::of($branch)
                    ->addIndexColumn()
                    ->addColumn('image', function ($row) {
                        $url = $row->getFirstMediaUrl('branch_images');
                        return '<img src="' . $url . '" height="75" align="center" />';
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="branches/' . $row->id . '" data-id="' . $row->id . '" class="view btn btn-primary btn-floating btn-sm">
                                <i class="la la-eye"></i>
                            </a>
                        <a href="branches/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
                            <i class="la la-pencil"></i>
                        </a>
                        <a data-id="' . $row->id . '" class="delete btn btn-danger btn-floating btn-sm">
                        <i class="la la-trash"></i>
                    </a>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'image'])
                    ->make(true);
            }
            return view('backend.master.branch.list-branches');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
    public function id(Request $request)
    {
        try {
            if ($request->ajax()) {
                $district = District::where('id', $request->district)->first();
                $district = $district->district_id;
                $district = explode('-', $district );
                $id = Branch::where('branch_id', 'like', 'L' . $district[0] . 'B%')->orderBy('id', 'desc')->first();
                if ($id != null) {
                    $id = substr($id->branch_id, 4);
                    $id = $id + 1;
                    $b_id = 'L' . $district[0] . 'B' . $id;
                } else {
                    $b_id = 'L' . $district[0] . 'B1';
                }
                return $b_id;
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
    public function create()
    {
        $districts = District::all();
        return view('backend.master.branch.create-branch',compact('districts'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'branch_id' => 'required | unique:branches,branch_id',
            'name' => 'required | string',
            'mobile' => 'required | regex:/^([0-9\s\-\+\(\)]*)$/ | unique:branches,mobile',
            'place' => 'required ',
            'address' => 'required | string',
            'district_id' => 'required',
            'status' => 'nullable'
        ]);
        return $branch = $this->branchInterface->createBranch($request);
        try {
            if ($branch) {
                return response()->json(['success' => 'Branch successfully created']);
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
        $branch = Branch::find($id);
        $managers = Manager::where(['branch_id' => $branch->id, 'type' => 1])->get();
        $executives = Executive::where('branch_id', $branch->id)->get();
        $schemes = Scheme::where('branch_id', $branch->id)->get();
        return view('backend.master.branch.view-branch', compact('branch', 'managers', 'executives', 'schemes'));
    }
    public function edit($id)
    {
        $branch = Branch::find($id);
        return view('backend.master.branch.create-branch', compact('branch'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required | string',
            'branch_id' => 'required | unique:branches,branch_id,' . $id,
            'mobile' => 'required | regex:/^([0-9\s\-\+\(\)]*)$/ | unique:branches,mobile,' . $id,
            'place' => 'required | regex:/^[a-zA-Z]+$/u',
            'address' => 'required | string',
            'district' => 'required',
            'status' => 'nullable'
        ]);
        try {
            $branch = $this->branchInterface->updateBranch($request, $id);
            if ($branch) {
                return response()->json(['success' => 'Branch details successfully updated']);
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
        $delete = Branch::find($id)->delete();
        return $delete;
    }
    public function getBranches()
    {
        $clustor = Cluster::where('id',request()->cluster_id)->first();

        $branchesCluster = DB::select("SELECT * FROM branch_cluster WHERE cluster_id = '" . $clustor->id . "'");
        $branch = [];
        foreach($branchesCluster as $branchCluster){
            $branch[] = ($branchCluster->branch_id);
        }
        $branches = Branch::whereIn('id',$branch)->get();
        return $branches;

    }
}
