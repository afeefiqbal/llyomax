<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\Advance;
use App\Repositories\BaseRepository;
use App\Repositories\interfaces\Accounts\AdvanceInterface;

class AdvanceRepository extends BaseRepository implements AdvanceInterface
{
    public function getModel()
    {
        return Advance::class;
    }
    public function listAdvance(){
        $advance = Advance::get();
        return $advance;
    }
    public function createAdvance($request){

        $advance = new Advance;
        $advance->date = $request->date;
        $advance->amount = $request->amount;
        $advance->name_of_employee = $request->name_of_employee;
        $advance->designation = $request->designation;
        $advance->staff_id = $request->employee_id;
        $advance->save();
        return $advance;
    }
    public function updateAdvance($request, $id){
        $advance = Advance::find($id);
        $advance->date = $request->date;
        $advance->amount = $request->amount;
        $advance->name_of_employee = $request->name_of_employee;
        $advance->designation = $request->designation;
        $advance->staff_id = $request->employee_id;
        $advance->save();
        return $advance;
    }
    public function deleteAdvance( $request, $id){
        $advance = Advance::find($id);
        $advance->delete();
        return $advance;
    }
    public function getAdvance($id){
        $advance = Advance::find($id);
        return $advance;
    }
}
