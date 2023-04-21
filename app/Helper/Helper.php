<?php
use  App\Helpers;
use App\Models\Customer;
use App\Models\Settings;
use GuzzleHttp\Client;

class Helper
{
    //1. OTP SMS

        public static function sendSMS($mobile, $otp)
        {

            $client = new Client();
            $client->request(
                'POST',
                "https://2factor.in/API/R1/?module=TRANS_SMS&apikey=0036d9eb-6ee6-11ec-b710-0200cd936042&to=91$mobile&from=LLYOMX&templatename=OTPMSG&var1=$otp",
        );

    }
    //2. Receipt SMS
    public static function sendReceiptSMS($name,$mobile,$scheme_id, $amount,$due_amount,$date)
    {
        $customer = Customer::where('phone',$mobile)->first();
        $branch = $customer->branch;
        $branchNumber = $branch->mobile;
        $branchName = $branch->branch_name;
        $customerCareNumber = Settings::where('key','customer_care_number')->first();
        $customerCareNumber = $customerCareNumber->value;

        $client = new Client();
        $client->request(

            'POST',
            "https://2factor.in/API/R1/?module=TRANS_SMS&apikey=0036d9eb-6ee6-11ec-b710-0200cd936042&to=91$mobile&from=LLYOMX&templatename=Reciept-MSG&var1=$name&var2=$amount&var3=$scheme_id&var4=$date&var5=$due_amount&var6=$branchName&var7=$branchNumber&var8=$customerCareNumber",
        );

    }
    //3. Welcome SMS
    public static function sendWelcomeSMS($name,$scheme_id,$mobile)
    {
        $client = new Client();
        $customer = Customer::where('phone',$mobile)->first();
        $branch = $customer->branch;
        $branchNumber = $branch->mobile;
        $customerCareNumber = Settings::where('key','customer_care_number')->first();
        $customerCareNumber = $customerCareNumber->value;
        $client->request(
            'POST',
            "https://2factor.in/API/R1/?module=TRANS_SMS&apikey=0036d9eb-6ee6-11ec-b710-0200cd936042&to=91$mobile&from=LLYOMX&templatename=WlcmMSG&var1=$name&var2=$scheme_id&var3=$branchNumber&var4=$customerCareNumber",
        );

    }
    //4. lucky draw SMS
    public static function sendLuckyDrawSMS($name, $schemeName,$mobile)
    {
        $client = new Client();
        $customer = Customer::where('phone',$mobile)->first();
        $branch = $customer->branch;
        $branchName = $branch->branch_name;
        $branchNumber = $branch->mobile;
        $customerCareNumber = Settings::where('key','customer_care_number')->first();
        $customerCareNumber = $customerCareNumber->value;
        $client->request(
            'POST',
            "https://2factor.in/API/R1/?module=TRANS_SMS&apikey=0036d9eb-6ee6-11ec-b710-0200cd936042&to=91$mobile&from=LLYOMX&templatename=LCKYDRAW&var1=$name&var2=$schemeName&var3=$branchName&var4=$branchNumber&var5=$customerCareNumber",
        );

    }
    //5. Stop SMS
    public static function sendSchemeStopSMS($name, $schemeName, $date,$mobile  )
    {
        $client = new Client();
        $customer = Customer::where('phone',$mobile)->first();
        $branch = $customer->branch;
        $branchName = $branch->branch_name;
        $branchNumber = $branch->mobile;
        $customerCareNumber = Settings::where('key','customer_care_number')->first();
        $customerCareNumber = $customerCareNumber->value;
        $client->request(
            'POST',
            "https://2factor.in/API/R1/?module=TRANS_SMS&apikey=0036d9eb-6ee6-11ec-b710-0200cd936042&to=91$mobile&from=LLYOMX&templatename=SCHMSTOPMSG&var1=$name&var2=$schemeName&var3=$date&var4=$branchName&var5=$branchNumber&var6=$customerCareNumber",
        );

    }
    //6. Pending SMS
    public static function sendSchemePendingSMS($name,$schemeName,$date, $amount,$mobile )
    {

        $client = new Client();
        $customer = Customer::where('phone',$mobile)->first();
        $branch = $customer->branch;
        $branchName = $branch->branch_name;
        $branchNumber = $branch->mobile;
        $customerCareNumber = Settings::where('key','customer_care_number')->first();
        $customerCareNumber = $customerCareNumber->value;
        $client->request(
            'POST',
            "https://2factor.in/API/R1/?module=TRANS_SMS&apikey=0036d9eb-6ee6-11ec-b710-0200cd936042&to=91$mobile&from=LLYOMX&templatename=SCHPENDINGMSG&var1=$name&var2=$schemeName&var3=$amount&var4=$date&var5=$branchName&var6=$branchNumber&var7=$customerCareNumber",
        );

    }
    //7. Start SMS
    public static function sendSchemeStrartingSMS($name, $scheme_name,$date,$mobile,$username,$password)
    {
        $website = "www.llyomax.d5n.in";
        $date = date('d-m-Y',strtotime($date));
        $customer = Customer::where('phone',$mobile)->first();
        $customerID = $customer->customer_id;
        $branch = $customer->branch;
        $branchNumber = $branch->mobile;
        $customerCareNumber = Settings::where('key','customer_care_number')->first();
        $customerCareNumber = $customerCareNumber->value;
        $client = new Client();
        $msg=  $client->request(
            'POST',
            "https://2factor.in/API/R1/?module=TRANS_SMS&apikey=0036d9eb-6ee6-11ec-b710-0200cd936042&to=91$mobile&from=LLYOMX&templatename=SCHMESTMSG&var1=$name&var2=$date&var3=$customerID&var4=$website&var5=$username&var6=$password"
        );

    }
    //8. COmpleted SMS
    public static function sendSchemeCompletedSMS( $name ,$mobile,$scheme_name ,$date)
    {


        $client = new Client();
        $customer = Customer::where('phone',$mobile)->first();
        $branch = $customer->branch;
        $branchNumber = $branch->mobile;
        $branchName = $branch->name;
        $customerCareNumber = Settings::where('key','customer_care_number')->first();
        $customerCareNumber = $customerCareNumber->value;
        $client->request(
            'POST',
            "https://2factor.in/API/R1/?module=TRANS_SMS&apikey=0036d9eb-6ee6-11ec-b710-0200cd936042&to=91$mobile&from=LLYOMX&templatename=SCHCOMPLETEDMSG&var1=$name&var2=$scheme_name&var3=$date&var4=$branchName&var5=$branchNumber&var6=$customerCareNumber",
        );

    }
    //9. Scheme Day Penidng SMS
    public static function sendSchemeDayPendingSMS($name,$schemeName,$date, $amount,$mobile )
    {

        $client = new Client();
        $customer = Customer::where('phone',$mobile)->first();
        $branch = $customer->branch;
        $branchName = $branch->branch_name;
        $branchNumber = $branch->mobile;
        $customerCareNumber = Settings::where('key','customer_care_number')->first();
        $customerCareNumber = $customerCareNumber->value;
        $client->request(
            'POST',
            "https://2factor.in/API/R1/?module=TRANS_SMS&apikey=0036d9eb-6ee6-11ec-b710-0200cd936042&to=91$mobile&from=LLYOMX&templatename=SCHDAYPENDINGMSG &var1=$name&var2=$schemeName&var3=$amount&var4=$date&var5=$branchName&var6=$branchNumber&var7=$customerCareNumber",
        );

    }
    //10. Delivery  SMS
    public static function sendDeliverySMS($name,$date,$mobile )
    {

        $client = new Client();
        $customer = Customer::where('phone',$mobile)->first();
        $orderID='';
        $delvieryBoyName = '';
        $branch = $customer->branch;
        $branchName = $branch->branch_name;
        $branchNumber = $branch->mobile;
        $customerCareNumber = Settings::where('key','customer_care_number')->first();
        $customerCareNumber = $customerCareNumber->value;
        $client->request(
            'POST',
            "https://2factor.in/API/R1/?module=TRANS_SMS&apikey=0036d9eb-6ee6-11ec-b710-0200cd936042&to=91$mobile&from=LLYOMX&templatename=DelivredMSG&var1=$name&var2=$orderID&var3=$delvieryBoyName&var4=$date&var5=$branchName&var6=$branchNumber&var7=$customerCareNumber",
        );

    }
}
