<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\RentAllowance;
use App\Repositories\interfaces\Accounts\RentAllowanceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class RentAllowanceController extends Controller
{
    protected $rentAllowanceInterface;

    public function __construct(RentAllowanceInterface $rentAllowanceInterface)
    {
        $this->rentAllowanceInterface = $rentAllowanceInterface;
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
                $rentAllowance = $this->rentAllowanceInterface->listRentAllowances();

                return DataTables::of($rentAllowance)
                    ->addIndexColumn()
                    ->addColumn('bills', function ($row) {
                        $pdf = '<iframe src="' .$row->getFirstMediaUrl('rent_allowances','rent_allowance'). '" width="100%" height="100%" style="border: none;"></iframe>';
                        return $pdf;
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="rent-allowance/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
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
            return view('backend.accounts.rent-allowance.list-rent-allowance');
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
        return view('backend.accounts.rent-allowance.create-rent-allowance');
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
            'date' =>'required',
            'amount' =>'required',
            'type' =>'required',
            'bill_doc' =>'required',
        ]);
        try {
            $rentAllowance = $this->rentAllowanceInterface->createRentAllowance($request);
            if ($rentAllowance) {
                return response()->json(['success' => 'Rent allowance created successfully']);
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
        $rentAllowance = $this->rentAllowanceInterface->getRentAllowanceById($id);
        return view('backend.accounts.rent-allowance.create-rent-allowance', compact('rentAllowance'));
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
            'date' =>'required',
            'amount' =>'required',
            'type' =>'required',
        ]);
        try {
            $rentAllowance = $this->rentAllowanceInterface->updateRentAllowance($request, $id);
            if ($rentAllowance) {
                return response()->json(['success' => 'Rent allowance updated successfully']);
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
        $delete = RentAllowance::find($id)->delete();
        return $delete;
    }
}
