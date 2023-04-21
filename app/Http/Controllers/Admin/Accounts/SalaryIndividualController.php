<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Office_admin\Staff;
use App\Repositories\interfaces\Accounts\SalaryIndividualInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class SalaryIndividualController extends Controller
{
    protected $salaryIndividualInterface;

    public function __construct(SalaryIndividualInterface $salaryIndividualInterface)
    {
        $this->salaryIndividualInterface = $salaryIndividualInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function  index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $salaryIndividual = $this->salaryIndividualInterface->listSalaaryIndividuals();
                // if ($userRole == 'branch-manager') {
                //     $schemes = $this->schemes->listBranchSchemes($user->id);
                // } else {
                // }

                return DataTables::of($salaryIndividual)
                    ->addIndexColumn()
                    ->addColumn('staff_id', function ($row) {
                        $staff = Staff::find($row->staff_id);
                        return $staff->staff_id ?? '';
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="salary-of-individuals/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
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
            return view('backend.accounts.salary-individuals.list-salary-individuals');
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
            $staffs = Staff::all();
            return view('backend.accounts.salary-individuals.create-salary-individual', compact('staffs'));
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
            'employee_id' => 'required',
            'amount' => 'required',
            'date' => 'required',
        ]);
        $staffs = Staff::where('id',$request->employee_id)->first();

        $request->name_of_employee = $staffs->name;
        $request->designation = $staffs->designation;
        try {
            $salaryIndividual = $this->salaryIndividualInterface->createSalaryIndividual($request);
            if ($salaryIndividual) {
                return response()->json(['success' => 'Salary Individual created successfully']);
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
        $salaryIndividual = $this->salaryIndividualInterface->getSalaryIndividualById($id);
        $staffs = Staff::get();
        return view('backend.accounts.salary-individuals.create-salary-individual', compact('salaryIndividual','staffs'));
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
            'employee_id' => 'required',
            'amount' => 'required',
            'date' => 'required',
        ]);
        $staffs = Staff::where('id',$request->employee_id)->first();

        $request->name_of_employee = $staffs->name;
        $request->designation = $staffs->designation;
        try {
            $salaryIndividual = $this->salaryIndividualInterface->updateSalaryIndividual($request, $id);
            if ($salaryIndividual) {
                return response()->json(['success' => 'Salary Individual  successfully updated']);
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
            $salaryIndividual = $this->salaryIndividualInterface->deleteSalaryIndividual($id);
            if ($salaryIndividual) {
                return response()->json(['success' => 'Salary Individual  successfully deleted']);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
}
