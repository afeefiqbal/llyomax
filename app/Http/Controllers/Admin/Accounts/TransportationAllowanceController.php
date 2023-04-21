<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Http\Controllers\Controller;
use App\Repositories\interfaces\Accounts\TransportationAllowanceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class TransportationAllowanceController extends Controller
{
    protected $transportationAllowanceInterface;

    public function __construct(TransportationAllowanceInterface $transportationAllowanceInterface)
    {
        $this->transportationAllowanceInterface = $transportationAllowanceInterface;
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
                $transportationAllowances = $this->transportationAllowanceInterface->listTransportationAllowance();
                // if ($userRole == 'branch-manager') {
                //     $schemes = $this->schemes->listBranchSchemes($user->id);
                // } else {
                // }

                return DataTables::of($transportationAllowances)
                    ->addIndexColumn()
                    ->addColumn('bills', function ($row) {
                        $pdf = '<iframe src="' . $row->getFirstMediaUrl('transportation_allowances', 'transportation_allowance') . '" width="100%" height="100%" style="border: none;"></iframe>';
                        return $pdf?? null  ;
                    })

                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="transportation-allowances/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
                            <i class="la la-pencil"></i>
                        </a>
                        <a data-id="' . $row->id . '" class="delete btn btn-danger btn-floating btn-sm">
                        <i class="la la-trash"></i>
                    </a>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'bills'])
                    ->make(true);
            }
            return view('backend.accounts.transportation-allowance.list-transportation-allowance');
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
        try {
            return view('backend.accounts.transportation-allowance.create-transportation-allowance');
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
            'date'  => 'required',
            'amount'  => 'required',
            'type_of_vehicle'  => 'required',
            'running_km' => 'required',
            'complaint' => 'required',

        ]);
        try {
            $transportationAllowance = $this->transportationAllowanceInterface->createTransportationAllowance($request);
            if ($transportationAllowance) {
                return response()->json(['success' => 'Transportation Allowance created successfully']);
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
        $transportationAllowance = $this->transportationAllowanceInterface->getTransportationAllowance($id);
        return view('backend.accounts.transportation-allowance.create-transportation-allowance', compact('transportationAllowance'));
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
            'date'  => 'required',
            'amount'  => 'required',
            'type_of_vehicle'  => 'required',
            'running_km' => 'required',
            'complaint' => 'required',
            'bill_doc' => 'required',

        ]);
        try {
            $transportationAllowance = $this->transportationAllowanceInterface->updateTransportationAllowance($request, $id);
            if ($transportationAllowance) {
                return response()->json(['success' => 'Transportation Allowance updated successfully']);
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
            $transportationAllowance = $this->transportationAllowanceInterface->deleteTransportationAllowance($id);
            if ($transportationAllowance) {
                return response()->json(['success' => 'Transportation Allowance deleted successfully']);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
}
