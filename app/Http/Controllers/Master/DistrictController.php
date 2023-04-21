<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Area;
use App\Models\Master\District;
use App\Repositories\Master\DistrictInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class DistrictController extends Controller
{
    protected $districtInterface;

    public function __construct(DistrictInterface $districtInterface)
    {
        $this->districtInterface = $districtInterface;
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
                $district = $this->districtInterface->listDistricts();
                return DataTables::of($district)
                    ->addIndexColumn()

                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="districts/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
                            <i class="la la-pencil"></i>
                        </a>
                        <a data-id="' . $row->id . '" class="delete btn btn-danger btn-floating btn-sm">
                        <i class="la la-trash"></i>
                    </a>
                    ';
                        return $btn;
                    })
                    ->rawColumns(['action', 'branch'])
                    ->make(true);
            }
            return view('backend.master.district.list-district');
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
        //
        $areas = Area::all();
        return view('backend.master.district.create-district', compact('areas'));
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
            'district_id' => 'required|unique:districts,district_id',
            'name' => 'required |min:2',
        ]);
        try {
            $branch = $this->districtInterface->createDistrict($request);
            if ($branch) {
                return response()->json(['success' => 'District successfully created']);
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
        $district = District::with('areas')->find($id);
        return view('backend.master.district.create-district', compact('district'));
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
            'district_id' => 'required|unique:districts,district_id,' . $id,
            'name' => 'required |min:2',
        ]);
        try {
            $district = $this->districtInterface->updateDistrict($request, $id);
            if ($district) {
                return response()->json(['success' => 'District successfully updated']);
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
            $district = $this->districtInterface->deleteDistrict($id);
            if ($district) {
                return response()->json(['success' => 'District successfully deleted']);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
}
