<?php

namespace App\Charts;

use App\Models\BranchTarget;
use App\Models\CustomerScheme;
use App\Models\Master\Branch;
use App\Models\Scheme;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class Chart1
{
    protected $chart1;

    public function __construct(LarapexChart $chart1)
    {
        $this->chart1 = $chart1;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\BarChart
    {
        $branchTargetSchemeID = BranchTarget::where('branch_id', 1)->get()->pluck('scheme_id');
        if ($branchTargetSchemeID->count()) {
                $schemes = Scheme::where('branch_id',1)->whereIn('id',[$branchTargetSchemeID])->get();
            // no properties
                    $schemeName = $schemes->map(function($q){
                        return $q->name;
                    })->toArray();
                    $schemeIDs = $schemes->map(function($q){
                        return $q->id;
                    })->toArray();
                    $branchTarget = BranchTarget::where('branch_id',1)->whereIn('scheme_id',$schemeIDs)
                    ->get()->pluck('target_per_month')->toArray();
                    $branchtargets = BranchTarget::where('branch_id', 1)->whereIn('scheme_id', $schemeIDs)->first();
                    $achievedTargetCount = CustomerScheme::where('branch_id', 1)->whereIn('scheme_id',$schemeIDs)->count();
                    $achievedTargetCount = CustomerScheme::where('branch_id', 1)->whereIn('scheme_id',$schemeIDs)->count();
                    if ($branchtargets != null) {
                     $to_achieved = ($branchtargets->target_per_month) - $achievedTargetCount;
                    } else {
                     $to_achieved = "";
                    }

                     $to_achieved ;

                return $this->chart1->barChart()
                ->setTitle('Branch Target ')
                ->addData('Target', $branchTarget)
                ->addData('To achieve', [$to_achieved])
                ->addData('Achieved',[$achievedTargetCount])
                ->setHeight(400)
                ->setXAxis($schemeName);

        }
        else{
            $branchTarget = 0;
            $to_achieved = 0;
            $achievedTargetCount = 0;
            $schemeName = 0;
            return $this->chart1->barChart()
            ->setTitle('Branch Target ')
            ->addData('Target', [$branchTarget])
            ->addData('To achieve', [$to_achieved])
            ->addData('Achieved',[$achievedTargetCount])
            ->setHeight(400)
            ->setXAxis([$schemeName]);
        }
        // if (isset($branchTargetSchemeID)) {
        //     if (sizeof($branchTargetSchemeID) != 0) {




}

}
