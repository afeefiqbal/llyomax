<?php

namespace App\Repositories\Branch;

use App\Models\AmountTransferDetail;
use App\Models\BranchTarget;
use Illuminate\Support\Str;
use App\Models\Customer;
use App\Models\Executive\Executive;
use App\Models\Master\Manager;
use Illuminate\Http\Request;
use App\Repositories\BaseRepository;
use App\Repositories\interfaces\Branch\AmountTransferDetailInterface;
use Illuminate\Support\Facades\Auth;

class AmountTransferDetailRepository extends BaseRepository implements AmountTransferDetailInterface
{
    public function getModel()
    {
        return Customer::class;
    }
    public function listAmountTransferDetails($args)
    {

        if ($args->date != '') {
            $amountTransferDetails = AmountTransferDetail::where('executive_id', null)->where('date', $args->date)->with('branch')->get();
        } else {
            $amountTransferDetails = AmountTransferDetail::where('executive_id', null)->orderBy('date', 'DESC')->with('branch')->get();
        }
        return $amountTransferDetails;
    }
    public function listAllExecutiveAmountTransferDetails($args)
    {
        if ($args->date != '') {
            $amountTransferDetails = AmountTransferDetail::where('executive_id', '!=', null)->where('date', $args->date)->with('branch')->get();
        } else {
            $amountTransferDetails = AmountTransferDetail::where('executive_id', '!=', null)->orderBy('date', 'DESC')->with('branch')->get();
        }
        return $amountTransferDetails;
    }
    public function listExecutiveAmountTransferDetails($args, $id)
    {
        $executive = Executive::where('user_id', $id)->first();
        if ($args->date != '') {
            $amountTransferDetails = AmountTransferDetail::where('executive_id', $executive->id)->where('date', $args->date)->with('branch')->get();

        } else {
            $amountTransferDetails = AmountTransferDetail::where('executive_id', $executive->id)->orderBy('date', 'DESC')->with('branch')->get();
        }
        return $amountTransferDetails;
    }
    public function listBranchAmountTransferDetails(Request $args, $id)
    {
        $manager = Manager::where('user_id', $id)->first();
        if ($args->date != '') {
            $amountTransferDetails = AmountTransferDetail::where('executive_id', '!=', null)->where('date', $args->date)->where('branch_id', $manager->branch_id)->with('branch')->get();
        } else {
            $amountTransferDetails = AmountTransferDetail::where('executive_id', '!=', null)->where('branch_id', $manager->branch_id)->orderBy('date', 'DESC')->with('branch')->get();
        }


        return $amountTransferDetails;
    }
    public function listBranchExecutiveAmountTransferDetails(Request $args, $id)
    {

        $manager = Manager::where('user_id', $id)->first();
        if ($args->date != '') {
            $amountTransferDetails = AmountTransferDetail::where('executive_id', '!=', null)->where('date', $args->date)->where('branch_id', $manager->branch_id)->with('branch')->get();
        } else {
            $amountTransferDetails = AmountTransferDetail::where('executive_id', '!=', null)->where('branch_id', $manager->branch_id)->orderBy('date', 'DESC')->with('branch')->get();
        }


        return $amountTransferDetails;
    }
    public function createAmountTransfer($args)
    {
        $amountTransferDetails = AmountTransferDetail::create([
            'branch_id' => $args['branch_id'],
            'executive_id' => ($args['executive_id'] == "" ? null : $args['executive_id']),
            'date' => $args['date'],
            'transfer_amount' => $args['transfer_amount'],
            'transfer_time' => $args['transfer_time'],
            'transfer_type' => $args['transfer_type'],
        ]);
        if (isset($args->receipt_image)) {
            $amountTransferDetails->addMediaFromBase64(json_decode($args->receipt_image)->data)
                ->usingFileName(Str::random() . '.jpeg')
                ->toMediaCollection('receipt_images');
        }
        return $amountTransferDetails;
    }
    public function deleteAmountTransferDetails($id)
    {
        $amountTransferDetails = AmountTransferDetail::where('id', $id)->delete();
        return $amountTransferDetails;
    }
}
