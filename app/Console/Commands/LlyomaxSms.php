<?php
namespace App\Console\Commands;
use App\Models\Customer;
use App\Models\CustomerScheme;
use App\Models\Executive\ExecutiveReportSubmission;
use App\Models\Scheme;
use Carbon\Carbon;
use Helper;
use Illuminate\Console\Command;
class LlyomaxSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'llyomax:sms';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function getWeekDate($scheme_start_date, $days)
    {
        $date = strtotime($scheme_start_date);
        $date = strtotime("+$days day", $date);
        $paid_date = date('Y-m-d', $date);
        return $paid_date;
    }
    public function handle()
    {
        //  return 0;
        $day = Carbon::yesterday()->format('l');
        $customeScheme = CustomerScheme::where('collection_day', $day)->whereIn('status', [0, 1])->get();
        foreach ($customeScheme as $key => $custScheme) {
            $schemeReport = ExecutiveReportSubmission::where('customer_id', $custScheme->customer_id)->where('scheme_id', $custScheme->scheme_id)->orderBy('id', 'desc')->first();
            $scheme = Scheme::where('id', $schemeReport->scheme_id)->first();
            if ($schemeReport != '') {
                $report_lastweek = $schemeReport->paid_week;
                if ($report_lastweek  == 1) {
                    $report_lastweek_date = $scheme->start_date;
                } else {
                    $last_week_no = (7 * $schemeReport->paid_week) - 7;
                    $report_lastweek_date = $this->getWeekDate($scheme->start_date, $last_week_no);
                }
                $next_week_no = 7 * $schemeReport->paid_week;
                $next_week_date = $this->getWeekDate($scheme->start_date, $next_week_no);
                $currentDate = date('Y-m-d');
                $currentDate = date('Y-m-d', strtotime($currentDate));
                if (($currentDate >= $report_lastweek_date) && ($currentDate <= $next_week_date)) {
                    /**
                     * --------------------------------------------------------------------------
                     *  Current date is between two dates
                     * ---------------------------------------------------------------------------
                     */
                        $customer = Customer::where('id',$customeScheme->customer_id)->first();
                        $name = $customer->name;
                        $scheme_name = $scheme->scheme_a_id."-".$scheme->scheme_n_id;
                        $amount = ($schemeReport->due_amount == 0 ? 200 : ($schemeReport->due_amount + 200));
                        Helper::sendSchemePendingSMS($customer->name,$scheme_name,$currentDate, $amount,$customer->phone );
                }

            }
        }
    }
}
