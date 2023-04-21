<?php

namespace App\Repositories\Executive;

use Carbon\Carbon;
use App\Models\CustomerScheme;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\Executive\ExecutiveReportSubmission;
use App\Repositories\interfaces\Executive\ExecutiveReportSubmissionInterface;

class ExecutiveReportSubmissionRepository extends BaseRepository implements ExecutiveReportSubmissionInterface
{
    public function getModel()
    {
        return \App\Models\Executive\ExecutiveReportSubmission::class;
    }
    public function createSubmission($request)
    {

        $customerscheme = CustomerScheme::with(['scheme','customer.executive'])->where(['customer_id' => request()->customer_id,'scheme_id' => request()->scheme_id])->first();
        if($customerscheme->pending_amount > 0)
        {
            $logs = ExecutiveReportSubmission::where(['customer_id' => request()->customer_id,'scheme_id' => request()->scheme_id])->where('due_amount','>', 0)->get();
            $collected_amount = $request->collected_amount;
            foreach($logs as $log)
            {
                $log_pending = $log->due_amount;
                if($log_pending>=$collected_amount)
                {
                    $log->due_amount = $log->due_amount - $collected_amount;
                    $log->paid_amount = $log->paid_amount + $collected_amount;
                    $log->save();
                    $customerscheme->pending_amount = $customerscheme->pending_amount - $collected_amount;
                    $customerscheme->total_amount = $customerscheme->total_amount + $collected_amount;
                    $customerscheme->save();
                    $collected_amount = 0;
                }elseif($log_pending<$collected_amount){
                    $log->due_amount = $log->due_amount - $log_pending;
                    $log->paid_amount = $log->paid_amount + $log_pending;
                    $log->save();
                    $customerscheme->pending_amount = $customerscheme->pending_amount - $log_pending;
                    $customerscheme->total_amount = $customerscheme->total_amount + $log_pending;
                    $customerscheme->save();
                    $collected_amount = $collected_amount - $log_pending;
                }
            }
            if($collected_amount>0)
            {
                $amount = $collected_amount;
                $date = Carbon::now();
                if($amount>=200)
                {
                    $times = (int)($amount/200);
                    for($i = 0;$i<$times;$i++)
                    {
                        $advance = 0;
                        $pending = 0;
                        $Famount = 200;
                        if($amount<200)
                        {
                            $pending = 200 - $amount;
                            $advance = 0;
                            $Famount = $amount;
                        }
                        $dailyReports = ExecutiveReportSubmission::create([
                            'branch_id'  => $request['branch_id'],
                            'scheme_id'  => $request['scheme_id'],
                            'customer_id'  => $customerscheme->customer_id,
                            'advance_amount' => $advance,
                            'due_amount' => $pending,
                            'executive_id'  => Auth::user()->id,
                            'date' => $date,
                            'paid_amount' => $Famount,
                            'status' => true,
                        ]);
                        $customerscheme->pending_amount = $customerscheme->pending_amount - $pending;
                        $customerscheme->total_amount = $customerscheme->total_amount + $Famount;
                        $customerscheme->save();
                        $date = $date->addWeek();
                        $amount = $amount-200;
                    }
                }
                else{
                    $pending = 200 - $amount;
                        $advance = 0;
                        $Famount = $amount;
                    $dailyReports = ExecutiveReportSubmission::create([
                        'branch_id'  => $request['branch_id'],
                        'scheme_id'  => $request['scheme_id'],
                        'customer_id'  => $customerscheme->customer_id,
                        'advance_amount' => $advance,
                        'due_amount' => $pending,
                        'executive_id'  => Auth::user()->id,
                        'date' => $date,
                        'paid_amount' => $Famount,
                        'status' => true,
                    ]);
                    $customerscheme->pending_amount = $customerscheme->pending_amount - $pending;
                    $customerscheme->total_amount = $customerscheme->total_amount + $Famount;
                    $customerscheme->save();
                }
            }
            else{
                $advance=0;
                $pending=200;
                $date = Carbon::now();
                $amount = 0;
                $dailyReports = ExecutiveReportSubmission::create([
                    'branch_id'  => $request['branch_id'],
                    'scheme_id'  => $request['scheme_id'],
                    'customer_id'  => $customerscheme->customer_id,
                    'advance_amount' => $advance,
                    'due_amount' => $pending,
                    'executive_id'  => Auth::user()->id,
                    'date' => $date,
                    'paid_amount' => $amount,
                    'status' => true,
                ]);
                $customerscheme->pending_amount = $customerscheme->pending_amount + $pending;
                $customerscheme->save();
            }
        }else{
            $amount = $request->collected_amount;
            $date = Carbon::now();
            if($amount>=200)
            {
                $times = (int)($amount/200);
                for($i = 0;$i<$times;$i++)
                {
                    $advance = 0;
                    $pending = 0;
                    $Famount = 200;
                    if($amount<200)
                    {
                        $pending = 200 - $amount;
                        $advance = 0;
                        $Famount = $amount;
                    }
                    $dailyReports = ExecutiveReportSubmission::create([
                        'branch_id'  => $request['branch_id'],
                        'scheme_id'  => $request['scheme_id'],
                        'customer_id'  => $customerscheme->customer_id,
                        'advance_amount' => $advance,
                        'due_amount' => $pending,
                        'executive_id'  => Auth::user()->id,
                        'date' => $date,
                        'paid_amount' => $Famount,
                        'status' => true,
                    ]);
                    if($pending>0)
                    $customerscheme->pending_amount = $customerscheme->pending_amount + $pending;
                    $customerscheme->total_amount = $customerscheme->total_amount + $Famount;
                    $customerscheme->save();
                    $date = $date->addWeek();
                    $amount = $amount-200;
                }
            }
            else{
                    $pending = 200 - $amount;
                    $advance = 0;
                    $Famount = $amount;
                $dailyReports = ExecutiveReportSubmission::create([
                    'branch_id'  => $request['branch_id'],
                    'scheme_id'  => $request['scheme_id'],
                    'customer_id'  => $customerscheme->customer_id,
                    'advance_amount' => $advance,
                    'due_amount' => $pending,
                    'executive_id'  => Auth::user()->id,
                    'date' => $date,
                    'paid_amount' => $Famount,
                    'status' => true,
                ]);
                $customerscheme->pending_amount = $customerscheme->pending_amount + $pending;
                $customerscheme->total_amount = $customerscheme->total_amount + $Famount;
                $customerscheme->save();
            }
        }

        return true;
    }
}
