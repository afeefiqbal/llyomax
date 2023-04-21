<?php

namespace App\Http\Controllers\Admin\Branch;

use App\Http\Controllers\Controller;
use App\Models\Branch\Branch;
use App\Models\Master\Cluster;
use App\Models\Master\District;
use App\Models\Scheme;
use App\Repositories\Branch\BranchSchemeInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class BranchSchemeController extends Controller
{
    protected $branchSchemeInterface;

    public function __construct(BranchSchemeInterface $branchSchemeInterface)
    {
        $this->branchSchemeInterface = $branchSchemeInterface;
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
                $schemes = $this->branchSchemeInterface->listBranchSchemes();

                // if ($userRole == 'branch-manager') {
                //     $schemes = $this->schemes->listBranchSchemes($user->id);
                // } else {
                // }

                return DataTables::of($schemes)
                    ->addIndexColumn()
                    ->addColumn('district_id', function ($row) {
                       return $districtID = $row->district_id;
                    })
                    ->addColumn('name', function ($row) {
                      return  $districtID = $row->name;
                    })
                    ->addColumn('clusters', function ($row) {
                        $ul = '<ul>';
                        foreach ($row->clusters as $cluster) {
                            $ul .= '<li>'.$cluster->name.'</li>';
                        }
                        $ul .= '</ul>';
                        return $ul;

                    })


                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="branch-assigning/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
                            <i class="la la-pencil"></i>
                        </a>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'clusters'])
                    ->make(true);
            }
            return view('backend.branch.scheme.list-branch-scheme');
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
        $districts = District::get();
        $clusters = Cluster::get();
        return view('backend.branch.scheme.create-branch-scheme', compact('districts', 'clusters'));
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
            'district_id' => 'required',
            'cluster_id' => 'required',
        ]);
        try {
            $cluster = District::where('id', $request->district_id)->first();
            $cluster->update(['cluster_id' => $request->cluster_id]);
            foreach ($request->cluster_id as $cluster_id) {
                $cluster = Cluster::find($cluster_id);
                $cluster->update(['district_id' => $request->district_id]);
            }
            return response()->json(['success' => 'Clusters assigned to district successfully.']);
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
        $branches = Branch::get();
        $schemes = Scheme::with('branches')->get();

        return view('backend.branch.scheme.create-branch-scheme', compact('branches', 'schemes'));
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
        $scheme = Scheme::with('branches')->where('id', $id)->first();
        $clusters = Cluster::with('branches')->get();
        $schemes = Scheme::get();
        return view('backend.branch.scheme.create-branch-scheme', compact('branches', 'scheme', 'schemes', 'clusters'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Branch::find($id)->update(['' => null]);
    }
    public function getBranchesByScheme(Request $request)
    {

        $branches = Branch::whereNotIn('scheme_id', [$request->scheme_id])->get();

        return response()->json(['branches' => $branches]);
    }
}
