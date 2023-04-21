<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Branch;
use App\Models\Master\Cluster;
use App\Models\Master\District;
use App\Repositories\Master\CLusterInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use DB;
class ClusterController extends Controller
{
    protected $cLusterInterface;

    public function __construct(CLusterInterface $cLusterInterface)
    {
        $this->cLusterInterface = $cLusterInterface;
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
                $cluster = $this->cLusterInterface->listClusters();

                return DataTables::of($cluster)
                    ->addIndexColumn()
                    ->addColumn('district', function ($row) {
                        return $row->district->name;
                    })
                    ->addColumn('branches', function ($row) {
                        $ul = '<ul>';
                        foreach ($row->branches as $branch) {
                            $ul .= '<li>' . $branch->branch_name . '</li>';
                        }
                        $ul .= '</ul>';
                        return $ul;
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="clusters/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
                            <i class="la la-pencil"></i>
                        </a>
                        <a data-id="' . $row->id . '" class="delete btn btn-danger btn-floating btn-sm">
                        <i class="la la-trash"></i>
                    </a>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'branches'])
                    ->make(true);
            }
            return view('backend.master.cluster.list-cluster');
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
       $districts =District::all();
       $branches =  Branch::get();

        $branches->pluck('id')->toArray();
       $branchCluser1 = DB::select('select branch_id from branch_cluster');
         $branchClusters = array();
            foreach ($branchCluser1 as $branchCluser) {
                $branchClusters[] = $branchCluser->branch_id;
            }
           $branches = Branch::whereNotIn('id', $branchClusters)->get();
        return view('backend.master.cluster.create-cluster',compact('districts','branches'));
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
            'name' => 'required|unique:clusters,name',
            'branches' => 'required|min:3',
            'district_id' => 'required|unique:clusters,district_id',
            'cluster_id' => 'required|unique:clusters,cluster_id',
        ],[
            '.min' => 'Select atleast 3 branches.',
        ]);

        try {
            $this->cLusterInterface->createCluster($request);
            return redirect()->route('clusters.index')->with('success', 'Cluster created successfully');
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
        // dd('edit');
        $districts =District::all();
        $branches =  Branch::all();
        $cluster = Cluster::with('branches')->find($id);

        return view('backend.master.cluster.create-cluster',compact('districts','cluster','branches'));
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
            'branches' => 'required|min:3',
            'name' => 'required|unique:clusters,name,'.$id,
            'district_id' => 'required|unique:clusters,district_id,'.$id,
            'cluster_id' => 'required|unique:clusters,cluster_id,'.$id,
        ]);
        return  $branch = $this->cLusterInterface->updateCluster($request, $id);
        try {
            if ($branch) {
                return response()->json(['success' => 'Cluster successfully updated']);
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
            $this->cLusterInterface->deleteCluster($id);
            return response()->json(['success', 'Cluster deleted successfully']);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
}
