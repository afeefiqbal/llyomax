<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Office_admin\Staff;
use App\Repositories\interfaces\Accounts\ExtraBonusInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ExtraBonusController extends Controller
{
    protected $extraBonusInterface;

    public function __construct(ExtraBonusInterface $extraBonusInterface)
    {
        $this->extraBonusInterface = $extraBonusInterface;
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
                $extraBonus = $this->extraBonusInterface->listExtraBonuses();
                // if ($userRole == 'branch-manager') {
                //     $schemes = $this->schemes->listBranchSchemes($user->id);
                // } else {
                // }

                return DataTables::of($extraBonus)
                    ->addIndexColumn()
                    ->addColumn('staff_id', function ($row) {
                        $staff = Staff::find($row->staff_id);
                        return $staff->staff_id.'-'.$staff->name ?? '';
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="extra-bonus/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
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
            return view('backend.accounts.extra-bonus.list-extra-bonus');
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
            $staffs = Staff::get();
            return view('backend.accounts.extra-bonus.create-extra-bonus', compact('staffs'));
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
            'staff_id' => 'required',
            'amount' => 'required',
            'date' => 'required',
            'type' => 'required',
            'particulars' => 'required',
        ]);
        try {
            $extraBonus = $this->extraBonusInterface->createExtraBonus($request);
            if ($extraBonus) {
                return response()->json(['success' => 'Extra Bonus created successfully']);
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
        $extraBonus = $this->extraBonusInterface->getExtraBonusById($id);
        $staffs = Staff::get();
        return view('backend.accounts.extra-bonus.create-extra-bonus', compact('extraBonus','staffs'));
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
            'staff_id' => 'required',
            'amount' => 'required',
            'date' => 'required',
            'type' => 'required',
            'particulars' => 'required',
        ]);
        try {
            $extraBonus = $this->extraBonusInterface->updateExtraBonus($request, $id);
            if ($extraBonus) {
                return response()->json(['success' => 'Extra Bonus updated successfully']);
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
            $extraBonus = $this->extraBonusInterface->deleteExtraBonus($id);
            if ($extraBonus) {
                return response()->json(['success' => 'Extra Bonus deleted successfully']);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
}
