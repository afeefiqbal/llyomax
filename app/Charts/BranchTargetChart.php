<?php

namespace App\Charts;

use App\Models\BranchTarget;
use App\Models\CustomerScheme;
use App\Models\Master\Branch;
use App\Models\Scheme;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class BranchTargetChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($reqst)
    {
        if (isset($reqst->branch_target_id)) {
            $branchTargetSchemeID = BranchTarget::where('branch_id',$reqst->branch_target_id)->get()->pluck('scheme_id');
            if (isset($branchTargetSchemeID)) {
                # code...
                $schemes = Scheme::where('branch_id',$reqst->branch_target_id)->whereIn('id',[$branchTargetSchemeID])->get();

                $schemeName = $schemes->map(function($q){
                    return $q->name;
                })->toArray();
                $schemeIDs = $schemes->map(function($q){
                    return $q->id;
                })->toArray();
                $branchTarget = BranchTarget::where('branch_id',$reqst->branch_target_id)->whereIn('scheme_id',$schemeIDs)
                ->get()->pluck('target_per_month')->toArray();
                $branchtargets = BranchTarget::where('branch_id', $reqst->branch_target_id)->whereIn('scheme_id', $schemeIDs)->first();
                $achievedTargetCount = CustomerScheme::where('branch_id',$reqst->branch_target_id)->whereIn('scheme_id',$schemeIDs)->count();
                $achievedTargetCount = CustomerScheme::where('branch_id',$reqst->branch_target_id)->whereIn('scheme_id',$schemeIDs)->count();
                if ($branchtargets != null) {
                 $to_achieved = ($branchtargets->target_per_month) - $achievedTargetCount;
                } else {
                 $to_achieved = "";
                }

                 $to_achieved ;
                return $this->chart->barChart()
                ->setTitle('Branch Target ')
                ->addData('Target', $branchTarget)
                ->addData('To achieve', [$to_achieved])
                ->addData('Achieved',[$achievedTargetCount])
                ->setHeight(390)
                ->setXAxis($schemeName);
            }
            }

    }
}
