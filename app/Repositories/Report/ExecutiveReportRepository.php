<?php

namespace App\Repositories\Report;

use App\Models\Master\Manager;
use App\Models\Office_admin\Staff;
use App\Models\Report\ExecutiveReport;
use App\Repositories\BaseRepository;
use App\Repositories\Report\ExecutiveReportInterface;

class ExecutiveReportRepository extends BaseRepository implements ExecutiveReportInterface
{
    public function getModel()
    {
        return \App\Models\Report\ExecutiveReport::class;
    }
    public function listExecutiveReport(){
        return $executiveReport = ExecutiveReport::get();
    }
    public function listBranchExecutiveReport($id){
        $manager = Manager::where('user_id',$id)->first();
        return $executiveReport = ExecutiveReport::where('branch_id',$manager->branch_id)->get();
    }
    public function listStaffReport(){
        return $staffReport = Staff::get();
    }
}
