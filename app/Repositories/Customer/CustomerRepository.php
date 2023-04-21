<?php

namespace App\Repositories\Customer;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerExecutive;
use App\Models\CustomerProduct;
use Illuminate\Http\Request;
use App\Models\Master\Branch;
use App\Models\CustomerScheme;
use App\Models\Executive\Executive;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\Executive\ExecutiveReportSubmission;
use App\Models\Master\Manager;
use App\Models\Master\OfficeAdmin;
use App\Models\Scheme;
use App\Repositories\interfaces\Customer\CustomerInterface;
use Helper;
use Illuminate\Support\Facades\Hash;

class CustomerRepository extends BaseRepository implements CustomerInterface
{
    public function getModel()
    {
        return Customer::class;
    }
    public function listCustomers()
    {
        return CustomerScheme::with('branch', 'customer', 'scheme')->latest()->get();
    }
    public function listofficeadministratorCustomers($id){
        $officAdmin = OfficeAdmin::where('user_id',$id)->first();
        $branchID = $officAdmin->branch_id;
        return CustomerScheme::where('branch_id', $branchID)->with('branch', 'customer', 'scheme')->latest()->get();

    }
    public function listbranchmanagerCustomers($id)
    {
        $manager = Manager::where('user_id', $id)->first();
        return CustomerScheme::where('branch_id', $manager->branch_id)->with('branch', 'customer', 'scheme')->latest()->get();
    }
    function listmarketingexecutiveCustomers(Int $id)
    {
        $exeutive = Executive::where('user_id', $id)->first();
        return CustomerScheme::where('executive_id', $exeutive->id)->with('branch', 'customer', 'scheme')->latest()->get();
    }
    public function listexecutiveCustomers($id)
    {
        $executive = Executive::where('user_id', $id)->first();
        return CustomerScheme::where('executive_id', $executive->id)->with('branch', 'customer', 'scheme')->latest()->get();
    }
    public function getCustomer(Int $id)
    {
        return Customer::class;
    }
    public function createCustomer(Request $args)
    {

        return DB::transaction(function () use ($args) {

            $password = rand(1000, 90000);
            $userExists = User::where('mobile',$args['phone'])->exists();
            if($userExists){
                $userDB = User::where('mobile',$args['phone'])->first();
                $userDB->delete();
            }
            $users = User::create([
                'name' => $args['name'],
                'username' => $args['username'],
                'mobile' => $args['phone'],
                'email' => $args['email'],
                'otp' => $args['otp'],
                // 'password' => Hash::make($args['password']),
                'password' =>  Hash::make($password),
                'status' => true,
            ]);
            $userId = $users->id;
            if ($users) {
                $users->assignRole('customer');
                $customer = Customer::create([
                    'name' => $args['name'],
                    'parenname' => $args['parent_name'],
                    'customer_id' =>  $args['customerCode'],
                    'user_id' => $userId,
                    'phone' => $args['phone'],
                    'phone_2' => $args['phone_2'],
                    'eil' => $args['email'],
                    // 'password' => $args['password'],
                    'password' => $password,
                    'username' => $args['name'],
                    'pincode' => $args['pincode'],
                    'otp' => $args['otp'],
                    'place' => $args['place'],
                    'building' => $args['building'],
                    'land_mark' => $args['land_mark'],
                    'city' => $args['city'],
                    'branch_id' => $args['branch_id'],
                    'executive_id' => null,
                    'address' => $args['address'],
                    'referenced_id' => Auth::user()->id,
                    'status' => $args['status'] == 'on' ? true : false,
                ]);
            }
            $customerProduct = new CustomerProduct();
            if(isset($args['product'])){

                foreach($args['products'] as $product) {
                    $customerProduct->create([
                        'customer_id' => $customer->id,
                        'product_id' => $product,
                    ]);
            }
            }

         $user_credential = $this->sendUserCredential($customer,$args,$password);
            $result = $this->CustomScheme($customer, $args);

            return $result;
        });

    }
    public function CustomScheme($customer, $args)
    {

        $user = Auth::user();
        $userRole = $user->roles->pluck('name')->first();
        if ($userRole == 'collection-executive'  || $userRole == 'marketing-executive') {
            $executive = Executive::where('user_id', $user->id)->first();
        } else {
            $executive = '';
        }
        if ($customer) {
            if ($args['amount'] == '200') {
                $amount = 200;
                $pending = 0;
                $advance = 0;
                $status = 1;
            } elseif ($args['amount'] == '0') {
                $amount = 0;
                $pending = 200;
                $advance = 0;
                $status = 0;
            } elseif ($args['amount'] == 'custom') {
                if ($args['custom_amount'] > 200) {
                    $amount = $args['custom_amount'];
                    $times = (int)($amount / 200);
                    $amount_ = $times * 200;
                    $pending = 200 - ($amount - $amount_);
                    $advance = 0;
                    $status = 1;
                } else {
                    $amount = $args['custom_amount'];
                    $pending = 200 - $amount;
                    $advance = 0;
                    $status = 1;
                }
            }
            $customerScheme = CustomerScheme::create([
                'customer_id' => $customer->id,
                'branch_id' => $args['branch_id'],
                'scheme_id' => $args['scheme_id'],
                'advance_amount' => $advance,
                'pending_amount' => $pending,
                'total_amount' => $amount,
                'collection_day' => $args['customer_collection_day'],
                'joining_date' => $args['scheme_start_date'],
                'next_collection_date' => null,
                'executive_id' => ($executive != '' ? $executive->id : null),
                'status' => $status,
            ]);
        }
        if ($customerScheme) {
            if ($args['amount'] == '200') {
                $amount = 200;
                $pending = 0;
                $advance = 0;
                $dailyReports = ExecutiveReportSubmission::create([
                    'branch_id'  => $args['branch_id'],
                    'scheme_id'  => $args['scheme_id'],
                    'customer_id'  => $customerScheme->customer_id,
                    'advance_amount' => $advance,
                    'due_amount' => $pending,
                    'executive_id'  => null,
                    'paid_date' => Carbon::now(),
                    'paid_week' => 1,
                    'paid_amount' => $amount,
                    'status' => true,
                ]);
            } elseif ($args['amount'] == '0') {

                $amount = 0;

                $pending = 200;
                $advance = 0;
                $dailyReports = ExecutiveReportSubmission::create([
                    'branch_id'  => $args['branch_id'],
                    'scheme_id'  => $args['scheme_id'],
                    'customer_id'  => $customerScheme->customer_id,
                    'advance_amount' => $advance,
                    'due_amount' => $pending,
                    'executive_id'  => null,
                    'paid_date' => Carbon::now(),
                    'paid_week' => 1,
                    'paid_amount' => $amount,
                    'status' => true,
                ]);
            } elseif ($args['amount'] == 'custom') {
                if ($args['custom_amount'] > 200) {
                    $amount = $args['custom_amount'];
                    $pending = 0;
                    $advance = 0;
                    $times = $amount / 200;
                    $week = 1;
                    for ($i = 0; $i < $times; $i++) {
                        $Famount = 200;
                        if ($amount < 200) {
                            $pending = 200 - $amount;
                            $advance = 0;
                            $Famount = $amount;
                        }
                        $dailyReports = ExecutiveReportSubmission::create([
                            'branch_id'  => $args['branch_id'],
                            'scheme_id'  => $args['scheme_id'],
                            'customer_id'  => $customerScheme->customer_id,
                            'advance_amount' => $advance,
                            'due_amount' => $pending,
                            'executive_id'  => null,
                            'paid_date' => Carbon::now(),
                            'paid_week' => $week,
                            'paid_amount' => $Famount,
                            'status' => true,
                        ]);
                        // $date = $date->addWeek();
                        // $date = strtotime($date);
                        // $date = strtotime("+7 day", $date);
                        // $date = date('Y/m/d', $date);
                        $week = $week + 1;
                        $amount = $amount - 200;
                    }
                } else {
                    $amount = $args['custom_amount'];
                    $pending = 200 - $amount;
                    $advance = 0;
                    $dailyReports = ExecutiveReportSubmission::create([
                        'branch_id'  => $args['branch_id'],
                        'scheme_id'  => $args['scheme_id'],
                        'customer_id'  => $customerScheme->customer_id,
                        'advance_amount' => $advance,
                        'due_amount' => $pending,
                        'executive_id'  => null,
                        'paid_date' => Carbon::now(),
                        'paid_week' => 1,
                        'paid_amount' => $amount,
                        'status' => true,
                    ]);
                }
            }
            $sms = $this->createSms($customerScheme);
            if ($sms) {
                return true;
            }
        } else {
            return false;
        }
    }
    public function sendUserCredential($customer,$args,$password){

        $branchscheme = Scheme::where('id', $args->scheme_id)->first();
        $date = Carbon::now()->format('d-M-Y');
        $name = $customer->customer_id . "-" . $customer->name;
        $password =$password;
        $scheme = $branchscheme->scheme_a_id . "-" . $branchscheme->scheme_n_id;
        /**
         * send Scheme Strarting SMS
         */

        Helper::sendSchemeStrartingSMS($customer->name, $scheme,$date,$customer->phone,$customer->phone,$password);
    }
    public function createSms($customerScheme)
    {
        $customer = Customer::where('id', $customerScheme->customer_id)->first();
        $branchscheme = Scheme::where('id', $customerScheme->scheme_id)->first();
        $name = $customer->customer_id . "-" . $customer->name;
        $scheme = $branchscheme->scheme_a_id . "-" . $branchscheme->scheme_n_id;
        /**
         * Send Welcome Message
         */
        Helper::sendWelcomeSMS($customer->name,$scheme,$customer->phone);
        $joining_date = $customerScheme->joining_date;
        $amount = $customerScheme->total_amount;
        $due_amount = (6000 - $customerScheme->total_amount);
        /**
         * Send Recept Message
         */
        Helper::sendReceiptSMS($customer->name,$customer->phone,$scheme, $amount,$due_amount,$joining_date);
        return true;
    }
    public function customerSchemeRegister(Request $args)
    {
        // dd($args['status']=='on' ? 1 : 0);
        $customer = Customer::where('id', $args->customer_id)->first();
        $result = $this->CustomScheme($customer, $args);
        return $result;
    }
    public function updateCustomer(Request $args, $id)
    {
        $customer = Customer::where('id', $id)->first();
        return DB::transaction(function () use ($args, $id, $customer) {
            $user = User::where('id', $customer->user_id)->first();
            $user->name = $args['name'];
            if($args['phone'] != null)
            $user->mobile = $args['phone'];
            $user->update();
            if ($customer) {
                $customer = Customer::where('id', $id)->first();
                $customer->name = $args['name'];
                $customer->username = $args['name'];
                if($args['phone'] != null)
                $customer->phone = $args['phone'];
                $customer->phone_2 = $args['phone_2'];
                $customer->email = $args['email'];
                $customer->address = $args['address'];
                $customer->city = $args['city'];
                $customer->pincode = $args['pincode'];
                $customer->place = $args['place'];
                $customer->area_id = $args['area_id'];
                $customer->building = $args['building'];
                $customer->land_mark = $args['land_mark'];
                $customer->update();
            }
            return $customer;
        });
    }
    public function deleteCustomer(Int $id)
    {
        $customerScheme = CustomerScheme::where('id', $id)->first();
        $customerid = Customer::where('id', $customerScheme->customer_id)->first();
        $user_id = $customerid->user_id;
        $customer = Customer::where('id', $customerScheme->customer_id)->delete();
        $schemeReport = ExecutiveReportSubmission::where('customer_id', $customerScheme->customer_id)->delete();
        $user = User::where('id', $user_id)->delete();
        $customerScheme = CustomerScheme::where('id', $id)->delete();
        return true;
    }
}
