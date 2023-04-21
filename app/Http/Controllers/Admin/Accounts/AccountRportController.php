<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Expense;
use App\Models\AmountTransferDetail;
use App\Models\Executive\ExecutiveReportSubmission;
use App\Repositories\interfaces\Accounts\AccountReportInterface;
use Illuminate\Http\Request;

class AccountRportController extends Controller
{
    protected $accountReportInterface;

    public function __construct(AccountReportInterface $accountReportInterface)
    {
        $this->accountReportInterface = $accountReportInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.accounts.account-reports.list-account-reports');
    }
    public function getReports(){
        $TotalExpenses = Expense::sum('amount');
        $totalCollection = ExecutiveReportSubmission::sum('paid_amount');
        $totalTransfBal = AmountTransferDetail::sum('transfer_amount');
        $openingBalance = $TotalExpenses - $totalCollection;
        $closingBalance = $openingBalance + $totalTransfBal;
        $data = [
            'totalExpenses' => $TotalExpenses,
            'totalCollection' => $totalCollection,
            'totalTransfBal' => $totalTransfBal,
            'openingBalance' => $openingBalance,
            'closingBalance' => $closingBalance
        ];
        return response()->json($data);
    }
}
