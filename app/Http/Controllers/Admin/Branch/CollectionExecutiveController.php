<?php

namespace App\Http\Controllers\Admin\Branch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerExecutive;
use App\Models\CustomerScheme;
use App\Models\Executive\Executive;
use App\Models\Master\Area;
use App\Models\Master\Branch;
use App\Models\Scheme;
use App\Repositories\interfaces\Branch\CollectionExecutiveInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Validation\Rule;
use App\Models\Executive\ExecutiveReportSubmission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class CollectionExecutiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $collectionExecutive;
    public function __construct(CollectionExecutiveInterface $collectionExecutive)
    {
        $this->middleware(['role:super-admin|developer-admin|branch-manager']);
        $this->collectionExecutive = $collectionExecutive;
    }
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $user = Auth::user();
                $userRole = $user->roles->pluck('name')->first();
                if ($userRole == 'branch-manager') {
                    $colllectionExecutive = $this->collectionExecutive->listBranchCustomerCollectionExecutives($user->id);
                } else {
                    $colllectionExecutive = $this->collectionExecutive->listCustomerCollectionExecutives();
                }
                return DataTables::of($colllectionExecutive)
                    ->addIndexColumn()
                    ->addColumn('executive_id', function ($row) {
                        return $row->executive->name;
                    })
                    ->addColumn('customer_id', function ($row) {
                        return $row->customer->customer_id;
                    })
                    ->addColumn('customer_name', function ($row) {
                        return $row->customer->name;
                    })
                    ->addColumn('customer_area', function ($row) {
                        return $row->customer->area->name;
                    })
                    ->addColumn('executive_area', function ($row) {
                        $area = Area::where('id', $row->executive->collection_area_id)->first();
                        return $area->name;
                    })

                    ->addColumn('scheme_id', function ($row) {
                        // $customer = CustomerScheme::where('customer_id',$row->id)->with('scheme')->first();
                        return  $row->scheme->name;
                    })
                    ->addColumn('action', function ($row) {
                        $user = Auth::user();
                        $userRole = $user->roles->pluck('name')->first();
                        if ($userRole == 'branch-manager') {
                            $btn = '
                            <a href="collection-executives/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
                                <i class="la la-pencil"></i>
                            </a>
                            ';
                        } else {
                            $btn = '
                        <a href="collection-executives/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
                            <i class="la la-pencil"></i>
                        </a>
                        <a data-id="' . $row->id . '" class="delete btn btn-danger btn-floating btn-sm">
                        <i class="la la-trash"></i>
                    </a>
                        ';
                        }
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('backend.branch.collection-executives.list-collection-executive');
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

        $schemes = Scheme::get();
        $branches = Branch::get();
        return view('backend.branch.collection-executives.create-collection-executive', compact('branches','schemes'));
    }
    public function getBranchSchemes(Request $request)
    {
        $data['schemes'] = Scheme::where('status', 1)->get();
        $data['area'] = Area::get();
        return $data;
    }
    public function getCustomers(Request $request)
    {
        $customer = CustomerScheme::where('scheme_id', request()->scheme_id)->join('customers', 'customer_scheme.customer_id', '=', 'customers.id')->get('customers.*')->toArray();
        $customerData = [];
        foreach ($customer as $key => $value) {
            $customerData[] = [
                "id" => $value['id'],
                "name" => $value['name'],
                "customer_id" => $value['customer_id'],
            ];
        }
        return $customerData;
    }
    public function getAreas(Request $request)
    {
        $customer = Customer::where('id', $request->customer_id)->first();
        $area = Area::where('id', $customer->area_id)->first();
        $areaData = [];
        $areaData[] = [
            "id" => $area->id,
            "area_id" => $area->area_id,
            "name" => $area->name,
        ];
        return $areaData;
    }
    public function getExecutives(Request $request)
    {
        $executive = Executive::where('collection_area_id', request()->area_id)
            ->where('executive_type', 2)->where('status', 1)->get();
        $cust = CustomerScheme::where('customer_id', request()->customer_id)->first();
        $day = $cust->collection_day;
        $executiveData = [];
        foreach ($executive as $key => $value) {
            $countDay = CustomerExecutive::join('customer_scheme', 'customer_executives.customer_id', '=', 'customer_scheme.customer_id')
                ->where('customer_executives.executive_id', $value->id)->where('customer_scheme.collection_day', $day)->count();
            $executiveData[] = [
                "id" => $value->id,
                "executive_id" => $value->executive_id,
                "name" => $value->name,
                "count" => $countDay
            ];
        }
        return $executiveData;
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

                'scheme_id' => 'required',
                'customer_id' =>
                [
                    'required',
                    Rule::unique('customer_executives')
                        ->where('customer_id', $request->customer_id)
                        ->where('scheme_id', $request->scheme_id)
                ],
                // 'customer_id' => 'required',
                'area_id' => 'required ',
                'collection_executive_id' => 'required',
            ],
            [
                '*.required' => 'This field is required'
            ]
        );
        try {
            $collectionExecutive = $this->collectionExecutive->createCollectionExecutive($request);
            if ($collectionExecutive) {
                return response()->json(['success' => 'Collection Executive successfully created']);
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
        $customerExecutive = CustomerExecutive::where('id', $id)->with(['executive', 'customer.area', 'scheme'])->first();
        $areas = Area::get();
        return view('backend.branch.collection-executives.edit-collection-executive', compact('customerExecutive', 'areas'));
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

                'scheme_id' => 'required',
                'customer_id' => 'required',
                'area_id' => 'required ',
                'collection_executive_id' => 'required',
            ],
            [
                '*.required' => 'This field is required'
            ]
        );
        try {
            $collectionExecutive = $this->collectionExecutive->updateCollectionExecutive($request, $id);
            if ($collectionExecutive) {
                return response()->json(['success' => 'Collection Executive successfully updated']);
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
        //
    }

    public function assignce(Request $request)
    {
        $request->validate(
            [
                'customer_id' =>
                [
                    'required',
                    Rule::unique('customer_executives')
                        ->where('customer_id', $request->customer_id)
                        ->where('scheme_id', $request->form['scheme_id'])
                ],
                // 'customer_id' => 'required',
                'area_id' => 'required ',
                'form' => 'required',
            ],
            [
                '*.required' => 'This field is required',
                'customer_id.unique' => 'This customer is already assigned to a collection executive',
            ]
        );
        try {
            $collectionExecutive = $this->collectionExecutive->assignCollectionExecutives($request);
            if ($collectionExecutive) {
                return response()->json(['success' => 'Collection Executive Assigned successfully']);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
}
