<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Master\Manager;
use App\Repositories\interfaces\Accounts\SalesCommisionInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class SalesCommisionController extends Controller
{
    protected $salesCommisionInterface;

    public function __construct(SalesCommisionInterface $salesCommisionInterface)
    {
        $this->salesCommisionInterface = $salesCommisionInterface;
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
                $salesCommision = $this->salesCommisionInterface->listSalesCommisions();
                // if ($userRole == 'branch-manager') {
                //     $schemes = $this->schemes->listBranchSchemes($user->id);
                // } else {
                // }

                return DataTables::of($salesCommision)
                    ->addIndexColumn()
                    ->addColumn('manager', function ($row) {
                        return $row->manager->manager_id.'-'.$row->manager->name;
                    })
                    ->addColumn('monthly',function($row){
                        $month = $row->monthly;
                        if($month == '1') { return 'January';  }
                        if($month == '2') { return 'February'; }
                        if($month == '3') { return 'March'; }
                        if($month == '4') { return 'April'; }
                        if($month == '5') { return 'May'; }
                        if($month == '6') { return 'June'; }
                        if($month == '7') { return 'July'; }
                        if($month == '8') { return 'August'; }
                        if($month == '9') { return 'September'; }
                        if($month == '10'){ return 'October'; }
                        if($month == '11'){ return 'November'; }
                        if($month == '12'){ return 'December'; }
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="sales-commisions/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
                            <i class="la la-pencil"></i>
                        </a>
                        <a data-id="' . $row->id . '" class="delete btn btn-danger btn-floating btn-sm">
                        <i class="la la-trash"></i>
                    </a>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'bill'])
                    ->make(true);
            }
            return view('backend.accounts.sales-commision.list-sales-commision');
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
        $managers = Manager::get();
        return view('backend.accounts.sales-commision.create-sales-commision', compact('managers'));
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
            'manager_id' => 'required',
            'amount' => 'required',
            'from_date' => 'required',
            'from_date' => 'required',
            'monthly' => 'required',
        ]);
        try{
            $this->salesCommisionInterface->createSalesCommision($request);
            return response()->json(['success' => 'Sales Commision created successfully']);
        }
        catch(Exception $e){
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
        $salesCommision = $this->salesCommisionInterface->getSalesCommisionById($id);
        $managers = Manager::get();
        return view('backend.accounts.sales-commision.create-sales-commision', compact('salesCommision', 'managers'));
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
            'manager_id' => 'required',
            'amount' => 'required',
            'from_date' => 'required',
            'from_date' => 'required',
            'monthly' => 'required',
        ]);
        try{
            $salesCommision = $this->salesCommisionInterface->updateSalesCommision($request, $id);
            if ($salesCommision) {
            return response()->json(['success' => 'Sales Commision updated successfully']);
            }
        }
        catch(Exception $e){
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
            $salesCommision = $this->salesCommisionInterface->deleteSalesCommision($id);
            if ($salesCommision) {
                return response()->json(['success' => 'Sales Commision deleted successfully']);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
}
