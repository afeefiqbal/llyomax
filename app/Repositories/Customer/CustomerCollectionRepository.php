<?php

namespace App\Repositories\Customer;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerExecutive;
use Illuminate\Http\Request;
use App\Models\Master\Branch;
use App\Models\CustomerScheme;
use App\Models\Executive\Executive;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\Executive\ExecutiveReportSubmission;
use App\Models\Master\Manager;
use App\Models\Scheme;
use App\Repositories\interfaces\Customer\CustomerCollectionInterface;
use Illuminate\Support\Facades\Hash;
use Helper;
class CustomerCollectionRepository extends BaseRepository implements CustomerCollectionInterface
{
    public function getModel()
    {
        return Customer::class;
    }
    public function listCustomers()
    {
        return CustomerScheme::with('customer', 'scheme', 'branch')->latest()->get();
    }
    public function listmarketingexecutiveCustomers(Int $id){
        $exeutive = Executive::where('user_id', $id)->first();
        $customers = CustomerScheme::where('executive_id',$exeutive->id)->with('customer', 'scheme', 'branch')
            ->latest()->get();
        return $customers;
    }
    public function listbranchmanagerCustomers(Int $id){
        $manager = Manager::where('user_id', $id)->first();
        $customers = CustomerScheme::where('branch_id',$manager->branch_id)->with('customer', 'scheme', 'branch')
            ->latest()->get();
        return $customers;
    }
    public function listexecutiveCustomers($id)
    {
        $exeutive = Executive::where('user_id', $id)->first();
        $customerExecutive = CustomerExecutive::where('executive_id', $exeutive->id)->pluck('customer_id')->toArray();
        $customers = CustomerScheme::with('customer', 'scheme', 'branch')
            ->whereIn('customer_id', $customerExecutive)
            ->latest()->get();
        return $customers;
    }
    public function updateCustomerCollection(Request $args, $id)
    {

        $user = Auth::user();
        $userRole = $user->roles->pluck('name')->first();

        $exeutiveID = null;
        if ($userRole == 'collection-executive') {
            $executive = Executive::where('user_id', $user->id)->first();
            $exeutiveID =$executive->id;
        }
        $customerscheme = CustomerScheme::with(['scheme', 'customer.executive'])->where('id', $id)->first();
        $scheme_report = ExecutiveReportSubmission::where(['customer_id' => $customerscheme->customer_id, 'scheme_id' => $customerscheme->scheme_id])->orderBy('id', 'desc')->first();
        /**
         * --------------------------------------------------------------------------
         *  if scheme pending amount is existing
         * ---------------------------------------------------------------------------
         */
        if ($customerscheme->pending_amount > 0) {

            $log = ExecutiveReportSubmission::where(['customer_id' => $customerscheme->customer_id, 'scheme_id' => $customerscheme->scheme_id])->orderBy('id', 'desc')->first();
            $collected_amount = $args->amount;
            $log_pending = $log->due_amount;
            if($collected_amount == 0){
                 $log->due_amount = $log->due_amount - $collected_amount;
                    $log->paid_amount = $log->paid_amount + $collected_amount;
                    $log->pending_reason = $args['pending_reason'];
                    $log->save();
                    $customer = Customer::find($customerscheme->customer_id);
                    $name = $customer->name;
                    $schemeName = $customerscheme->scheme->scheme_a_id.'-'.$customerscheme->scheme->scheme_n_id;
                    $date = Carbon::now()->format('d-m-Y H:i:s');
                    $amount = $log->due_amount - $collected_amount;
                    $mobile = $customer->mobile;
                    Helper::sendSchemeDayPendingSMS($name,$schemeName,$date, $amount,$mobile );
                return response()->json(['message' => 'Log saved']);
            }
            else{
                if ($log_pending >= $collected_amount) {
                    $log->due_amount = $log->due_amount - $collected_amount;
                    $log->paid_amount = $log->paid_amount + $collected_amount;
                    $log->pending_reason	 = null;
                    $log->paid_date =  Carbon::now();
                    $log->save();
                    $customerscheme->last_paid_date =  Carbon::now();
                    $customerscheme->pending_amount = $customerscheme->pending_amount - $collected_amount;
                    $customerscheme->total_amount = $customerscheme->total_amount + $collected_amount;
                    $customerscheme->completed_date = ($customerscheme->total_amount == 6000 ?   Carbon::now(): null);
                    $customerscheme->status = ($customerscheme->total_amount == 6000 ? 2 : 1);//0 -> pending , 1->active, 2 -> completed , 3->lucky , 4->closed
                    $customerscheme->save();
                    $sms = $this->receiptSms($customerscheme);
                } elseif ($log_pending < $collected_amount) {
                    $log->due_amount = $log->due_amount - $log_pending;
                    $log->paid_amount = $log->paid_amount + $log_pending;
                    $log->pending_reason	 = null;
                    $log->paid_date =  Carbon::now();
                    $log->save();
                    $collected_amount = $collected_amount - $customerscheme->pending_amount;
                    $reportscheme = $this->reportSchemeInsertData($collected_amount, $scheme_report, $customerscheme, $args, $exeutiveID);
                }

            }
            return true;
            /**
             * --------------------------------------------------------------------------
             *  if scheme pending amount not existing(Zero)
             * ---------------------------------------------------------------------------
             */
        } else {

            $amount = $args->amount;
            $week_no = ($scheme_report->paid_week) + 1;
            if ($amount > 200) {
                $times = $amount / 200;
                for ($i = 0; $i < $times; $i++) {
                    $advance = 0;
                    $pending = 0;
                    $Famount = 200;
                    if ($amount < 200) {
                        $pending = 200 - $amount;
                        $advance = 0;
                        $Famount = $amount;
                    }
                    $dailyReports = ExecutiveReportSubmission::create([
                        'branch_id'  => $customerscheme->branch_id,
                        'scheme_id'  => $customerscheme->scheme_id,
                        'customer_id'  => $customerscheme->customer_id,
                        'advance_amount' => $advance,
                        'due_amount' => $pending,
                        'executive_id'  =>  $exeutiveID,
                        'paid_date' => Carbon::now(),
                        'paid_week' => $week_no,
                        'paid_amount' => $Famount,
                        'status' => true,
                    ]);
                    $week_no = $week_no + 1;
                    $amount = $amount - 200;
                }
                $customerscheme->pending_amount = $pending;
                $customerscheme->last_paid_date =  Carbon::now();
                $customerscheme->total_amount = $customerscheme->total_amount + $args->amount;
                $customerscheme->completed_date = ($customerscheme->total_amount == 6000 ?   Carbon::now(): null);
                $customerscheme->status = ($customerscheme->total_amount == 6000 ? 2 : 1);//0 -> pending , 1->active, 2 -> completed , 3->lucky , 4->closed
                $customerscheme->save();
                $sms = $this->receiptSms($customerscheme);
            } else {
                $pending = 200 - $args->amount;
                $advance = 0;
                $Famount = $args->amount;
                $dailyReports = ExecutiveReportSubmission::create([
                    'branch_id'  => $customerscheme->branch_id,
                    'scheme_id'  => $customerscheme->scheme_id,
                    'customer_id'  => $customerscheme->customer_id,
                    'advance_amount' => $advance,
                    'due_amount' => $pending,
                    'executive_id'  =>  $exeutiveID,
                    'paid_date' => Carbon::now(),
                    'paid_week' => $week_no,
                    'paid_amount' => $Famount,
                    'status' => true,
                ]);
                $customerscheme->pending_amount = $pending;
                $customerscheme->last_paid_date =  Carbon::now();
                $customerscheme->total_amount = $customerscheme->total_amount + $Famount;
                $customerscheme->completed_date = ($customerscheme->total_amount == 6000 ?   Carbon::now(): null);
                $customerscheme->status = ($customerscheme->total_amount == 6000 ? 2 : 1);//0 -> pending , 1->active, 2 -> completed , 3->lucky , 4->closed
                $customerscheme->save();
                $sms = $this->receiptSms($customerscheme);
            }
        }
        return true;
    }
    public function reportSchemeInsertData($collected_amount, $scheme_report, $customerscheme, $args, $exeutiveID)
    {
        if ($collected_amount > 0) {
            $amount = $collected_amount;
            $week_no = ($scheme_report->paid_week) + 1;
            if ($amount > 200) {
                $times = $amount / 200;
                for ($i = 0; $i < $times; $i++) {
                    $advance = 0;
                    $pending = 0;
                    $Famount = 200;
                    if ($amount < 200) {
                        $pending = 200 - $amount;
                        $advance = 0;
                        $Famount = $amount;
                    }
                    $dailyReports = ExecutiveReportSubmission::create([
                        'branch_id'  => $customerscheme->branch_id,
                        'scheme_id'  => $customerscheme->scheme_id,
                        'customer_id'  => $customerscheme->customer_id,
                        'advance_amount' => $advance,
                        'due_amount' => $pending,
                        'executive_id'  =>  $exeutiveID,
                        'paid_date' => Carbon::now(),
                        'paid_week' => $week_no,
                        'paid_amount' => $Famount,
                        'status' => true,
                    ]);
                    $week_no = $week_no + 1;
                    $amount = $amount - 200;
                }
                $customerscheme->pending_amount = $pending;
                $customerscheme->last_paid_date =  Carbon::now();
                $customerscheme->total_amount = $customerscheme->total_amount + $args->amount;
                $customerscheme->completed_date = ($customerscheme->total_amount == 6000 ?   Carbon::now(): null);
                $customerscheme->status = ($customerscheme->total_amount == 6000 ? 2 : 1);//0 -> pending , 1->active, 2 -> completed , 3->lucky , 4->closed
                $customerscheme->save();
                $sms = $this->receiptSms($customerscheme);
                return true;
            } else {
                $pending = 200 - $collected_amount;
                $advance = 0;
                $Famount = $collected_amount;
                $dailyReports = ExecutiveReportSubmission::create([
                    'branch_id'  => $customerscheme->branch_id,
                    'scheme_id'  => $customerscheme->scheme_id,
                    'customer_id'  => $customerscheme->customer_id,
                    'advance_amount' => $advance,
                    'due_amount' => $pending,
                    'executive_id'  =>  $exeutiveID,
                    'paid_date' => Carbon::now(),
                    'paid_week' => $week_no,
                    'paid_amount' => $Famount,
                    'status' => true,
                ]);
                $customerscheme->pending_amount = $pending;
                $customerscheme->last_paid_date =  Carbon::now();
                $customerscheme->total_amount = $customerscheme->total_amount + $args->amount;
                $customerscheme->completed_date = ($customerscheme->total_amount == 6000  ?   Carbon::now(): null);
                $customerscheme->status = ($customerscheme->total_amount == 6000 ? 2 : 1);//0 -> pending , 1->active, 2 -> completed , 3->lucky , 4->closed
                $customerscheme->save();
                $sms = $this->receiptSms($customerscheme);
                return true;
            }
        }
    }
    public function receiptSms($customerScheme)
    {

        $customer = Customer::where('id', $customerScheme->customer_id)->first();
        $branchscheme = Scheme::where('id', $customerScheme->scheme_id)->first();
        $schemeReport = ExecutiveReportSubmission::where('scheme_id', $customerScheme->scheme_id)
                        ->where('branch_id', $customerScheme->branch_id)
                        ->where('customer_id', $customerScheme->customer_id)
                        ->orderBy('id','desc')
                        ->first();
        $name = $customer->customer_id . "-" . $customer->name;
        $scheme = $branchscheme->scheme_a_id . "-" . $branchscheme->scheme_n_id;
        $paid_date = $schemeReport->paid_date;
        $amount = $customerScheme->total_amount;
        $due_amount = (6000 - $customerScheme->total_amount);
        /**
         * Send Recept Message
         */
        Helper::sendReceiptSMS($customer->name,$customer->phone,$scheme, $amount,$due_amount,$paid_date);
        if ($amount == 6000) {
              /**
         * completed Message
         */
        Helper::sendSchemeCompletedSMS($name,$customer->phone,$scheme ,$paid_date);
        }

        return true;
    }
    public function stopSchemeSms($customerScheme)
    {
        $customer = Customer::where('id', $customerScheme->customer_id)->first();
        $branchscheme = Scheme::where('id', $customerScheme->scheme_id)->first();
        $name =  $customer->name;
        $scheme = $branchscheme->scheme_a_id . "-" . $branchscheme->scheme_n_id;
        $stop_date = $customerScheme->stop_date;
        $amount = $customerScheme->total_amount;
        $due_amount = (6000 - $customerScheme->total_amount);
        /**
         * Send Scheme Stop SMS
         */
        Helper::sendSchemeStopSMS($name, $scheme, $stop_date,$customer->phone  );
        return true;
    }
    function stopCustomerScheme(Request $args){
       //0 -> pending , 1->active, 2 -> completed , 3->lucky , 4->closed
        $customerScheme = CustomerScheme::where('id', $args->id)
        ->update([
           'reason' =>$args->reason,
            'status' => "4",
            'stop_date' => Carbon::today(),
        ]);
        $sms = $this->stopSchemeSms($customerScheme);
        if ($sms) {
            return $customerScheme;
        }

    }
    function restartCustomerScheme(Request $args){

        $customerScheme = CustomerScheme::where('id', $args->id)
        ->update([
           'reason' =>null,
            'status' => "1",
            'stop_date' => null,
        ]);
        return $customerScheme;
    }
}
