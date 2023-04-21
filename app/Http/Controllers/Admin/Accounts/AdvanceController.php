<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Advance;
use App\Models\Office_admin\Staff;
use App\Repositories\interfaces\Accounts\AdvanceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class AdvanceController extends Controller
{
    protected $advanceInterface;

    public function __construct(AdvanceInterface $advanceInterface)
    {
        $this->advanceInterface = $advanceInterface;
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
                $advances = $this->advanceInterface->listAdvance();
                // if ($userRole == 'branch-manager') {
                //     $schemes = $this->schemes->listBranchSchemes($user->id);
                // } else {
                // }

                return DataTables::of($advances)
                    ->addIndexColumn()
                    ->addColumn('staff_id', function ($row) {
                        $staff = Staff::find($row->staff_id);
                        return $staff->staff_id ?? '';
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="advance/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
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
            return view('backend.accounts.advance.list-advance');
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
        $staffs = Staff::get();
        return view('backend.accounts.advance.create-advance', compact('staffs'));
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
            $advance = $this->advanceInterface->createAdvance($request);
            if ($advance) {
                return response()->json(['success' => 'Advance created successfully']);
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
        $advance = $this->advanceInterface->getAdvance($id);
        $staffs = Staff::get();
        return view('backend.accounts.advance.create-advance', compact('advance','staffs'));
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
            $advance = $this->advanceInterface->updateAdvance($request, $id);
            if ($advance) {
                return response()->json(['success' => 'Advance  successfully updated']);
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
        $delete = Advance::find($id)->delete();
        return $delete;
    }
}
