<?php

namespace App\Http\Controllers\Admin\Branch;

use App\Http\Controllers\Controller;
use App\Models\BranchTarget;
use Illuminate\Http\Request;

use App\Models\Executive\Executive;
use App\Models\MarketingExecutiveTarget;
use App\Models\Master\Area;
use App\Models\Master\Branch;
use App\Models\Scheme;
use App\Repositories\interfaces\Branch\BranchTargetInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
class BranchTargetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $branchTarget;
    public function __construct(BranchTargetInterface $branchTarget)
    {
        $this->middleware(['role:super-admin|developer-admin|branch-manager']);
        $this->branchTarget = $branchTarget;
    }
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $user = Auth::user();
                $userRole = $user->roles->pluck('name')->first();
                if ($userRole == 'branch-manager') {
                    $branchTarget = $this->branchTarget->listbranchUserTargets($user->id);
                } else {
                    $branchTarget = $this->branchTarget->listbranchTargets();
                }


                return DataTables::of($branchTarget)
                    ->addIndexColumn()
                    ->addColumn('branch_id', function ($row) {
                        return $row->branch->branch_name ?? '';
                    })
                    ->addColumn('scheme_id', function ($row) {
                        return $row->scheme->name ?? '';
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="branch-targets/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
                            <i class="la la-pencil"></i>
                        </a>
                        <a data-id="' . $row->id . '" class="delete btn btn-danger btn-floating btn-sm">
                        <i class="la la-trash"></i>
                    </a>
                        ';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('backend.branch.branch-target.list-branch-target');
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
        return view('backend.branch.branch-target.create-branch-target', compact('branches'));
    }
    public function getBranchSchemes(Request $request)
    {
        $branch = Branch::with('scheme')->find($request->branch_id);

        $schemes = $branch->scheme;

        if(isset($schemes)){
            $schemes = $schemes;
            return response()->json(['schemes' => $schemes]);
        }
        else{
            $schemes = null;
            return response()->json(['schemes' => $schemes]);
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

        $request->validate(
            [
                'target_per_month' => 'required|integer|min:30',
                'branch_id' => 'required|unique:branch_targets,branch_id,{$this->id},id,deleted_at,NULL',
            ],
            [
                '*.required' => 'This field is required',
                'scheme_id.unique' => 'The scheme Target has already been taken',
                'target_per_month.min' => 'Target per month must be at least 30',
            ]
        );
        $target = $request->target_per_month;
        $target_per_day = $target / 30;
        $target_per_day = round($target_per_day);
        $request['target_per_day'] = $target_per_day;
        try {
            $branchTarget = $this->branchTarget->createBranchTarget($request);

            if ($branchTarget) {
                return response()->json(['success' => 'Branch Target successfully created']);
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
        $branches = Branch::get();
        $branchTarget = BranchTarget::where('id',$id)->first();
        return view('backend.branch.branch-target.create-branch-target', compact('branches','branchTarget'));
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

        $request->validate(
            [

                'target_per_month' => 'required ',
                'branch_id' => 'required',

                'scheme_id' => 'unique:branch_targets,scheme_id,' . $id,

            ],
            [
                '*.required' => 'This field is required',
                'scheme_id.unique' => 'The Target has already been taken'
            ]
        );
        $target = $request->target_per_month;
        $target_per_day = $target / 30;
        $target_per_day = round($target_per_day);
        $request['target_per_day'] = $target_per_day;
        try {

            $branchTarget = $this->branchTarget->updateBranchTarget($request,$id);
            if ($branchTarget) {
                return response()->json(['success' => ' Target successfully updated']);
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
        try {

            $branchTarget = $this->branchTarget->deleteBranchTarget($id);
            if ($branchTarget) {
                return response()->json(['success' => 'Target successfully deleted']);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
}
