<?php

namespace App\Repositories\Report;

use App\Models\Branch\LuckyDraw;
use App\Models\Master\Branch;
use App\Models\Office_admin\Attendance;
use App\Repositories\BaseRepository;

class ReportRepository extends BaseRepository implements ReportInterface
{
    public function getModel()
    {

        return LuckyDraw::class;
    }
    public function listStaffAttendanceReport($request){

        return $branch = Branch::get();
    }
}
