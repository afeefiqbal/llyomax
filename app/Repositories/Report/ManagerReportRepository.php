<?php

namespace App\Repositories\Report;

use App\Models\Report\ManagerReport;
use App\Repositories\BaseRepository;
use App\Repositories\Report\ManagerReportInterface;

class ManagerReportRepository extends BaseRepository implements ManagerReportInterface
{
    public function getModel()
    {
        return ManagerReport::class;
    }
    public function listManagerReport()
    {

        return $managerReport = ManagerReport::get();

        // if($branch_id){
        //     $attendance = Attendance::where('date',$date)->get();

        // }
        // else{
        //     $dates =  Attendance::distinct()->get(['date']);
        // }
        // return $attendance;
    }
}
