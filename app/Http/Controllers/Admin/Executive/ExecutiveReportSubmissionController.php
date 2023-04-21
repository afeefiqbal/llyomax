<?php

namespace App\Http\Controllers\Admin\Executive;

use Exception;
use Carbon\Carbon;
use App\Models\Scheme;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Master\Branch;
use App\Models\CustomerScheme;
use Illuminate\Support\Facades\DB;
use App\Models\Executive\Executive;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Executive\ExecutiveReportSubmission;
use App\Repositories\interfaces\Executive\ExecutiveReportSubmissionInterface;

class ExecutiveReportSubmissionController extends Controller
{
    protected $executiveReportSubmissionInterface;

    public function __construct(ExecutiveReportSubmissionInterface $executiveReportSubmissionInterface)
    {
        $this->executiveReportSubmissionInterface = $executiveReportSubmissionInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schemes = Scheme::get();
        $branches = Branch::get();
        $executives = Executive::get();
        return view('backend.executive.report-submission.create-report-submission',compact('schemes','executives','branches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $schemes = Scheme::get();
        $branches = Branch::get();
        $executives = Executive::get();
        return view('backend.executive.report-submission.create-report-submission',compact('schemes','executives','branches'));
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
            'branch_id' => 'required',
            'scheme_id' => 'required',
            'customer_id' => 'required',
        ]);
        try {
            $dailyReports = $this->executiveReportSubmissionInterface->createSubmission($request);

            if ($dailyReports) {
                return response()->json(['success' => 'Report Submitted  Successfully']);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function getSchemes(){
        $id = request()->branch_id;
        $schemes = Scheme::where('branch_id',$id)->get();
        return $schemes;
    }
    public function getCustomer(){
         return  $customerscheme = CustomerScheme::with(['scheme','customer'=>function($q){
             $q->with('executive')->where('branch_id',request()->branch_id)->get();
         }])->where('scheme_id',request()->scheme_id)->get();

    }
    public function getCustomerDetails(){
       return CustomerScheme::with('scheme','customer.executive')->where('customer_id',request()->customer_id)->where('scheme_id',request()->scheme_id)->first();
    }
}
