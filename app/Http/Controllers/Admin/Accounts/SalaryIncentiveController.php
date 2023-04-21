<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Http\Controllers\Controller;
use App\Repositories\interfaces\Accounts\SalaryIncentiveInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class SalaryIncentiveController extends Controller
{
    protected $salaryIncentiveInterface;

    public function __construct(SalaryIncentiveInterface $salaryIncentiveInterface)
    {
        $this->salaryIncentiveInterface = $salaryIncentiveInterface;
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
                $salaryIncentive = $this->salaryIncentiveInterface->listSalaryIncentives();

                return DataTables::of($salaryIncentive)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="salary-incentives/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
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
            return view('backend.accounts.salary-incentives.list-salary-incentives');
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
        return view('backend.accounts.salary-incentives.create-salary-incentive');
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
            'total_amount' => 'required',
        ]);
        try {
            $salaryIncentive = $this->salaryIncentiveInterface->createSalaryIncentive($request);
            if ($salaryIncentive) {
                return response()->json(['success' => 'Salary Incentives created successfully']);
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
        try {
            $salaryIncentive = $this->salaryIncentiveInterface->getSalaryIncentiveById($id);
            return view('backend.accounts.salary-incentives.create-salary-incentive', compact('salaryIncentive'));
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
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
            'total_amount' => 'required',
        ]);
        try {
            $salaryIncentive = $this->salaryIncentiveInterface->updateSalaryIncentive($request, $id);
            if ($salaryIncentive) {
                return response()->json(['success' => 'Salary Incentives updated successfully']);
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
            $salaryIncentive = $this->salaryIncentiveInterface->deleteSalaryIncentive($id);
            if ($salaryIncentive) {
                return response()->json(['success' => 'Salary Incentives deleted successfully']);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
}
