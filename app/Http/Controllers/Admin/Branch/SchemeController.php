<?php

namespace App\Http\Controllers\Admin\Branch;

use App\Http\Controllers\Controller;
use App\Models\Master\Branch;
use App\Models\Master\Cluster;
use App\Models\Scheme;
use App\Repositories\interfaces\Branch\SchemeInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class SchemeController extends Controller
{
    protected $schemes;
    public function __construct(SchemeInterface $schemes)
    {
        $this->middleware(['role:super-admin|developer-admin|branch-manager|collection-manager']);
        $this->schemes = $schemes;
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
                if ($userRole == 'branch-manager') {
                    $schemes = $this->schemes->listBranchSchemes($user->id);
                } else {
                    $schemes = $this->schemes->listSchemes();
                }

                return DataTables::of($schemes)
                    ->addIndexColumn()
                    ->addColumn('scheme_id',function($row){
                        return $row->scheme_a_id.'-'.$row->scheme_n_id;
                    })
                    ->addColumn('show_scheme', function ($row) {
                        $btn = '<a href="schemes/' . $row->id . '/" class="edit btn btn-info btn-floating btn-sm">
                        <i class="la la-eye"></i>
                    </a>';
                        return $btn;
                    })
                    ->addColumn('created_at', function ($row) {
                        return $row->created_at->format('d-m-Y H:i:s');
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="schemes/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
                            <i class="la la-pencil"></i>
                        </a>';
                        // <a data-id="' . $row->id . '" class="delete btn btn-danger btn-floating btn-sm">
                        //     <i class="la la-trash"></i>
                        // </a>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'branch','show_scheme'])
                    ->make(true);
            }
            return view('backend.branch.scheme.list-scheme');
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
        $schemeID = Scheme::latest()->first();
        if ($schemeID) {
            $schemeCode = $schemeID->scheme_n_id;
            $schemeCode = substr($schemeCode, -3);
            $schemeCode = (int) $schemeCode;
            $schemeCode++;
            $schemeCode =  sprintf('%03d', $schemeCode);
        } else {
            $schemeCode = '001';
        }
        $result = $schemeCode;
        $clusters = Cluster::get();

        return view('backend.branch.scheme.create-scheme', compact('result', 'clusters'));
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
            'cluster_id' => 'required',
            'start_date' => 'required | date ',
            'scheme_a_id' => 'required',
            'scheme_n_id' => 'required',

            'end_date' => 'required | date ',
            'join_start_date' => 'required | date |required_with:join_end_date|before:join_end_date',
            'join_end_date' => 'required | date ',
            // 'branch_id' => 'required',
        ], [
            '*.required' => 'This field is required'
        ]);
        return $scheme = $this->schemes->createScheme($request);
        try {
            if ($scheme) {
                return response()->json(['success' => 'Scheme successfully created']);
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
        $scheme = $this->schemes->getScheme($id);
        $branches = Branch::get();
        return view('backend.branch.scheme.show-scheme')->with(compact('scheme', 'branches'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $scheme = $this->schemes->getScheme($id);
        $clusters = Cluster::get();

        return view('backend.branch.scheme.create-scheme')->with(compact('scheme', 'clusters'));
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
            'name' => 'required' ,
            'cluster_id' => 'required|unique:schemes,cluster_id,'.$id,
            'start_date' => 'required | date ',
            'end_date' => 'required | date ',
            'join_start_date' => 'required | date |required_with:join_end_date|before:join_end_date',
            'join_end_date' => 'required | date ',
        ]);
        try {
            $scheme = $this->schemes->updateScheme($request, $id);
            if ($scheme) {
                return response()->json(['success' => 'Scheme successfully updated']);
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
        $scheme = Scheme::find($id)->delete();
        return true;
    }
    public function assigning()
    {
    }
   public function branchAssigning(Request $request){
         $request->validate([
              'branch_id' => 'required',
         ]);
         $branches = Branch::find($request->branch_id);
        foreach ($branches as $branches) {
           Branch::where('id', $branches->id)->update(['scheme_id' => $request->scheme_id]);
        }
   }
}
