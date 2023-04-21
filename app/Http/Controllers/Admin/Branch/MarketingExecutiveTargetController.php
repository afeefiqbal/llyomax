<?php

namespace App\Http\Controllers\Admin\Branch;

use App\Http\Controllers\Controller;
use App\Models\Executive\Executive;
use App\Models\MarketingExecutiveTarget;
use App\Models\Master\Area;
use App\Models\Master\Branch;
use Illuminate\Http\Request;
use App\Repositories\interfaces\Branch\MarketingExecutiveTargetInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class MarketingExecutiveTargetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $marketingExecutive;
    public function __construct(MarketingExecutiveTargetInterface $marketingExecutive)
    {
        $this->middleware(['role:super-admin|developer-admin|branch-manager']);
        $this->marketingExecutive = $marketingExecutive;
    }
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $user = Auth::user();
                $userRole = $user->roles->pluck('name')->first();
                if ($userRole == 'branch-manager') {
                    $marketingExecutive = $this->marketingExecutive->listBranchmarketingExecutiveTarget($user->id);
                } else {
                    $marketingExecutive = $this->marketingExecutive->listmarketingExecutiveTarget();
                }
                return DataTables::of($marketingExecutive)
                    ->addIndexColumn()
                    ->addColumn('branch_id', function ($row) {
                        return $row->executive->branch->branch_name;
                    })
                    ->addColumn('executive_id', function ($row) {
                        return $row->executive->name;
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="marketing-executive-targets/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
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
            return view('backend.branch.marketing-executive-targets.list-marketing-executive-target');
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
        return view('backend.branch.marketing-executive-targets.create-marketing-executive-target', compact('branches'));
    }
    public function getMarketingExecutives(Request $request)
    {
        $maraketingExecutive = Executive::where('executive_type', 1)->where('branch_id',request()->branch_id)->where('status', 1)->get();
        return $maraketingExecutive;
    }
    public function getBranchArea(Request $request)
    {
        $area = Area::get();
        return $area;
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
                'target_per_month' => 'required ',
                'executive_id' => 'required|unique:marketing_executive_targets',
            ],
            [
                '*.required' => 'This field is required',
                'executive_id.unique' => 'The Marketing Executive Target has already been taken'
            ]
        );
        $target = $request->target_per_month;
        $target_per_day = $target / 30;
        $target_per_day = round($target_per_day);
        $request['target_per_day'] = $target_per_day;
        try {
            $marketingExecutive = $this->marketingExecutive->createMarketingExecutiveTarget($request);
            if ($marketingExecutive) {
                return response()->json(['success' => 'Marketing Executive Target successfully created']);
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
        $executiveTarget = MarketingExecutiveTarget::where('id', $id)->with('executive')->first();
        return view('backend.branch.marketing-executive-targets.create-marketing-executive-target', compact('branches', 'executiveTarget'));
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
                'target_per_day' => 'required ',
                'executive_id' => 'required|unique:marketing_executive_targets,executive_id,' . $id,
            ],
            [
                '*.required' => 'This field is required',
                'executive_id.unique' => 'The Marketing Executive Target has already been taken'
            ]
        );
        $target = $request->target_per_month;
        $target_per_day = $target / 30;
        $target_per_day = round($target_per_day);
        $request['target_per_day'] = $target_per_day;
        try {
            $marketingExecutive = $this->marketingExecutive->updateMarketingExecutiveTarget($request, $id);
            if ($marketingExecutive) {
                return response()->json(['success' => 'Marketing Executive Target successfully updated']);
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
            $marketingExecutive = $this->marketingExecutive->deleteMarketingExecutiveTarget($id);
            if ($marketingExecutive) {
                return response()->json(['success' => 'Marketing Executive Target successfully deleted']);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
}
