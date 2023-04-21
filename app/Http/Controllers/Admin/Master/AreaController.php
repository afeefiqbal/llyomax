<?php

namespace App\Http\Controllers\Admin\Master;

use Exception;
use App\Models\Master\Area;
use Illuminate\Http\Request;
use App\Models\Master\Branch;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Master\District;
use App\Repositories\interfaces\Master\AreaInterface;
use Illuminate\Support\Str;

class AreaController extends Controller
{
    protected $areaInterface;
    public function __construct(AreaInterface $areaInterface)
    {
        $this->areaInterface = $areaInterface;
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
                $area = $this->areaInterface->listAreas();
                return DataTables::of($area)
                    ->addIndexColumn()
                    ->addColumn('district_id', function ($row) {
                        return $row->district->name ?? '';
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="areas/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
                            <i class="la la-pencil"></i>
                        </a>
                        <a data-id="' . $row->id . '" class="delete btn btn-danger btn-floating btn-sm">
                            <i class="la la-trash"></i>
                        </a>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'branch'])
                    ->make(true);
            }
            return view('backend.master.area.list-area');
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
        return view('backend.master.area.create-area', compact('districts'));
    }
    public function id(Request $request)
    {
        try {
            if ($request->ajax()) {
                $branch_id = Branch::find($request->branch)->branch_id;
                $id = Area::where('branch_id', $request->branch)->orderBy('id', 'desc')->first();
                if ($id != null) {
                    $number = Str::afterLast($id->area_id, 'A');
                    $area_id = $number + 1;
                    $area_id = '' . $branch_id . 'A' . $area_id;
                } else {
                    $area_id = '' . $branch_id . 'A1';
                }
                return $area_id;
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
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
        $request->validate([
            'area_id' => 'required|unique:areas,area_id',
            'name' => 'required |min:2',
            'district_id' => 'required',
        ]);
        try {
            $branch = $this->areaInterface->createArea($request);
            if ($branch) {
                return response()->json(['success' => 'Area successfully created']);
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
        $area = Area::where('id', $id)->with('branch')->first();
        $districts = District::get();
        return view('backend.master.area.create-area', compact('area', 'districts'));
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

            'area_id' => 'required|unique:areas,area_id,' . $id,
            'name' => 'required |min:2',
            'district_id' => 'required',
        ]);
        try {
            $branch = $this->areaInterface->updateArea($request, $id);
            if ($branch) {
                return response()->json(['success' => 'Area successfully updated']);
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
        $area = Area::find($id)->delete();
        return $area;
    }
}
