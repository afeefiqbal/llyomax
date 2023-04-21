<?php

namespace App\Repositories\Report;

use App\Models\Branch\LuckyDraw;
use App\Repositories\BaseRepository;
use App\Repositories\Report\LuckyDrawReportInterface;

class LuckyDrawReportRepository extends BaseRepository implements LuckyDrawReportInterface
{
    public function getModel()
    {

        return LuckyDraw::class;
    }
    public function listLuckyDrawReports($request){
        return LuckyDraw::get();
    }
}
