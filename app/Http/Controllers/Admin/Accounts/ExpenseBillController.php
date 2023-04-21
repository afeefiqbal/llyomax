<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\ExpenseBill;
use App\Repositories\interfaces\Accounts\ExpenseBillInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ExpenseBillController extends Controller
{
    protected $expenseBillInterface;

    public function __construct(ExpenseBillInterface $expenseBillInterface)
    {
        $this->expenseBillInterface = $expenseBillInterface;
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
                $expenseBill = $this->expenseBillInterface->listExpenseBill();

                return DataTables::of($expenseBill)
                    ->addIndexColumn()
                    ->addColumn('bills', function ($row) {
                        $pdf = '<iframe src="' .$row->getFirstMediaUrl('bills','bill'). '" width="100%" height="100%" style="border: none;"></iframe>';
                        return $pdf;
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="expense-bills/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
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
            return view('backend.accounts.expense-bills.list-expense-bills');
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
        return view('backend.accounts.expense-bills.create-expense-bills');
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
            'date' => 'required',
            'amount' => 'required',
            'electricity_bill' => 'required',
            'bill_doc' => 'required',
        ]);
        try {
            $expenseBill = $this->expenseBillInterface->createExpenseBill($request);
            if ($expenseBill) {
                return response()->json(['success' => 'Expense Bill created successfully']);
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
        $expenseBill = $this->expenseBillInterface->getExpenseBill($id);
        return view('backend.accounts.expense-bills.create-expense-bills', compact('expenseBill'));
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
            'date' => 'required',
            'amount' => 'required',
            'electricity_bill' => 'required',
            // 'bill_doc' => 'required',
        ]);
        try {
            $expenseBill = $this->expenseBillInterface->updateExpenseBill($request, $id);
            if ($expenseBill) {
                return response()->json(['success' => 'Expense Bill updated successfully']);
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
        $delete = ExpenseBill::find($id)->delete();
        return $delete;
    }
}
