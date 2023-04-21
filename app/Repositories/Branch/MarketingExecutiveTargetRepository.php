<?php
namespace App\Repositories\Branch;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerExecutive;
use Illuminate\Http\Request;
use App\Models\Master\Branch;
use App\Models\CustomerScheme;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\Executive\ExecutiveReportSubmission;
use App\Models\MarketingExecutiveTarget;
use App\Models\Master\Manager;
use App\Repositories\interfaces\Branch\MarketingExecutiveTargetInterface;
class MarketingExecutiveTargetRepository extends BaseRepository implements MarketingExecutiveTargetInterface
{
    public function getModel()
    {
        return Customer::class;
    }
    public function listMarketingExecutiveTarget(){
        $marketingExecutive = MarketingExecutiveTarget::with('executive.branch')->get();

        return $marketingExecutive;
    }
    public function listBranchmarketingExecutiveTarget($id){
        $manager = Manager::where('user_id',$id)->first();
        $marketingExecutive = MarketingExecutiveTarget::join('executives', 'marketing_executive_targets.executive_id', '=', 'executives.id')
        ->where('executives.branch_id',$manager->branch_id)
        ->with('executive.branch')
        ->get(['marketing_executive_targets.*']);

       // $marketingExecutive = MarketingExecutiveTarget::with('executive.branch')->where('executive.branch',$manager->branch_id)->get();

        return $marketingExecutive;
    }
    public function createMarketingExecutiveTarget($args)
    {


        $marketingExecutive = MarketingExecutiveTarget::create([

            'executive_id' => $args['executive_id'],
            'target_per_day' => $args['target_per_day'],
            'target_per_month' => $args['target_per_month'],
        ]);
        return $marketingExecutive;

    }
    public function updateMarketingExecutiveTarget(Request $args, $id)
    {

        $marketingExecutive = MarketingExecutiveTarget::where('id', $id)
        ->update([
            'executive_id' => $args['executive_id'],
            'target_per_day' => $args['target_per_day'],
            'target_per_month' => $args['target_per_month'],
        ]);
         return $marketingExecutive;
    }
    public function deleteMarketingExecutiveTarget($id)
    {

        $marketingExecutive = MarketingExecutiveTarget::where('id', $id)->delete();

         return $marketingExecutive;
    }
}
