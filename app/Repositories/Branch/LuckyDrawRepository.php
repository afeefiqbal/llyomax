<?php

namespace App\Repositories\Branch;

use App\Models\Branch\LuckyDraw;
use App\Models\Customer;
use App\Models\CustomerScheme;
use App\Models\Executive\Executive;
use App\Models\Master\Manager;
use App\Models\Scheme;
use App\Repositories\BaseRepository;
use App\Repositories\Branch\LuckyDrawInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Helper;

class LuckyDrawRepository extends BaseRepository implements LuckyDrawInterface
{
    public function getModel()
    {
        return LuckyDraw::class;
    }
    public function listLuckyDraws()
    {
        return LuckyDraw::orderBy('draw_date', 'DESC')->get();
    }
    public function listBranchLuckyDraws($id)
    {
        $manager = Manager::where('user_id', $id)->first();
        return LuckyDraw::where('branch_id', $manager->branch_id)->orderBy('draw_date', 'DESC')->get();
    }
    public function listExecutiveBranchLuckyDraws($id)
    {
        $executive = Executive::where('user_id', $id)->first();
        return LuckyDraw::where('branch_id', $executive->branch_id)->orderBy('draw_date', 'DESC')->get();
    }
    public function createLuckyDraw(Request $request)
    {
        $luckyDraw = LuckyDraw::create([
            'scheme_id' => $request->scheme_id,
            'customer_id' => $request->customer_id,
            'draw_date' => date('Y-m-d'),
            'branch_id' => $request->branch_id,
            'week' => $request->week,
        ]);
        if ($luckyDraw) {
            $customerScheme =  CustomerScheme::where('branch_id', $request->branch_id)->where('customer_id', $request->customer_id)->where('scheme_id', $request->scheme_id)
                ->update([
                    'status' => 3,
                ]);
        }
        $sms = $this->luckyDrawWinner($luckyDraw);
        if ($sms) {
            return $luckyDraw;
        }
    }
    public function luckyDrawWinner($luckyDraw)
    {
        $customer = Customer::where('id', $luckyDraw->customer_id)->first();
        $branchscheme = Scheme::where('id', $luckyDraw->scheme_id)->first();
        $name =  $customer->name;
        $scheme = $branchscheme->scheme_a_id . "-" .$branchscheme->scheme_n_id;

        /**
         * Send Lucky Draw Message
         */
        Helper::sendLuckyDrawSMS($name, $scheme,$customer->phone);
        return true;
    }
    public function updateLuckyDraw(Request $request, $id)
    {
    }
    public function deleteLuckyDraw($id)
    {
        $luckyDraw = LuckyDraw::where('id', $id)->delete();
        return $luckyDraw;
    }
}
