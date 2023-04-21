<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Expense;
use App\Repositories\interfaces\Accounts\ExpenseInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ExpenseController extends Controller
{
    protected $expenseInterface;

    public function __construct(ExpenseInterface $expenseInterface)
    {
        $this->expenseInterface = $expenseInterface;
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
                $expenses = $this->expenseInterface->listExpenses();
                // if ($userRole == 'branch-manager') {
                //     $schemes = $this->schemes->listBranchSchemes($user->id);
                // } else {
                // }

                return DataTables::of($expenses)
                    ->addIndexColumn()
                    ->addColumn('bill', function ($row) {
                        $pdf = '<iframe src="' .$row->getFirstMediaUrl('expense_bills','expense_bill'). '" width="100%" height="100%" style="border: none;"></iframe>';
                        return $pdf;
                    })

                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="expense/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
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
            return view('backend.accounts.expense.list-expense');
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
        return view('backend.accounts.expense.create-expense');
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
            'expense_name' => 'required',
            'date' => 'required',
            'amount' => 'required',
            'particulars' => 'required',
            'bill_doc' => 'required',
        ]);

        try {
            $expense = $this->expenseInterface->createExpense($request);
            if ($expense) {
                return response()->json(['success' => 'Expense created successfully']);
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
        //
        $expense = $this->expenseInterface->getExpense($id);
        return view('backend.accounts.expense.create-expense', compact('expense'));
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
            'expense_name' => 'required|unique:expenses,expense_name,'.$id,
            'date' => 'required',
            'amount' => 'required',
            'particulars' => 'required',
        ]);
        try {
            $expense = $this->expenseInterface->updateExpense($request, $id);
            if ($expense) {
                return response()->json(['success' => 'Office Expense successfully updated']);
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
        $delete = Expense::find($id)->delete();
        return $delete;
    }
}
