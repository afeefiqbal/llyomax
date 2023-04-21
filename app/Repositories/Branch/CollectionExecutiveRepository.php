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
use App\Models\Master\Manager;
use App\Repositories\interfaces\Branch\CollectionExecutiveInterface;
class CollectionExecutiveRepository extends BaseRepository implements CollectionExecutiveInterface
{
    public function getModel()
    {
        return Customer::class;
    }
    public function listCustomerCollectionExecutives(){
        $customer = CustomerExecutive::with(['executive','customer.area','scheme'])->get();

        return $customer;
    }
    public function listBranchCustomerCollectionExecutives($id){
        $manager = Manager::where('user_id',$id)->first();
        $customer = CustomerExecutive::where('branch_id',$manager->branch_id)->with(['executive','customer.area','scheme'])->get();

        return $customer;
    }
    public function createCollectionExecutive($args)
    {

        $customerExecutive = CustomerExecutive::create([
            'customer_id' => $args['customer_id'],
            'executive_id' => $args['collection_executive_id'],
            'scheme_id' => $args['scheme_id'],
            'branch_id' => $args['branch_id'],
        ]);
        return $customerExecutive;
    }
    public function updateCollectionExecutive(Request $args, $id)
    {
        $customerExecutive = CustomerExecutive::where('id', $id)
        ->update([
       'executive_id' => $args['collection_executive_id'],
        ]);
return $customerExecutive;
    }
    public function deleteCollectionExecutive($id)
    {

        $customerExecutive = CustomerExecutive::where('id', $id)->delete();

         return $customerExecutive;
    }

    public function assignCollectionExecutives(Request $args)
    {
        $customer = Customer::find($args['customer_id']);
        $customerExecutive = CustomerExecutive::create([
            'customer_id' => $args['customer_id'],
            'executive_id' => $args['form']['collection_executive_id'],
            'scheme_id' => $args['form']['scheme_id'],
            'branch_id' => $customer->branch_id,
        ]);
        return $customerExecutive;
    }

}
