<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\WeeklyGift;
use App\Repositories\BaseRepository;
use App\Repositories\interfaces\Accounts\WeeklyGiftInterface;

class WeeklyGiftRepository extends BaseRepository implements WeeklyGiftInterface
{
    public function getModel()
    {
        return WeeklyGift::class;
    }
    public function listWeeklyGifts(){
        $weeklyGift = WeeklyGift::get();
        return $weeklyGift;
    }
    public function createWeeklyGift($request){

        $weeklyGift = new WeeklyGift;
        $weeklyGift->date = $request->date;
        $weeklyGift->amount = $request->amount;
        $weeklyGift->scheme_id = $request->scheme_id;
        $weeklyGift->given_by = $request->given_by;
        $weeklyGift->branch_id = $request->branch_id;
        $weeklyGift->gift_items = $request->gift_items;
        $weeklyGift->customer_id = $request->customer_id;
        $weeklyGift->week = $request->week;
        $weeklyGift->save();
        if (isset($request->bill_doc)) {
            $expDoc =    $weeklyGift->addMediaFromBase64(json_decode($request->bill_doc)->data)
                   ->toMediaCollection('weeklyGifts');

               }
        return $weeklyGift;
    }
    public function updateWeeklyGift($request, $id){
        $weeklyGift = WeeklyGift::find($id);
        $weeklyGift->date = $request->date;
        $weeklyGift->amount = $request->amount;
        $weeklyGift->scheme_id = $request->scheme_id;
        $weeklyGift->given_by = $request->given_by;
        $weeklyGift->branch_id = $request->branch_id;
        $weeklyGift->gift_items = $request->gift_items;
        $weeklyGift->customer_id = $request->customer_id;
        $weeklyGift->week = $request->week;
        $weeklyGift->save();
        if (isset($request->bill_doc)) {
            $weeklyGift->clearMediaCollection('weeklyGifts');
            $expDoc =    $weeklyGift->addMediaFromBase64(json_decode($request->bill_doc)->data)
                   ->toMediaCollection('weeklyGifts');
               }
        return $weeklyGift;
    }
    public function deleteWeeklyGift( $request, $id){
        $weeklyGift = WeeklyGift::find($id);
        $weeklyGift->delete();
        return $weeklyGift;
    }
    public function getWeeklyGift($id){
        $weeklyGift = WeeklyGift::find($id);
        return $weeklyGift;
    }

}
