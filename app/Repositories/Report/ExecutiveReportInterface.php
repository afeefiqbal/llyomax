<?php

namespace App\Repositories\Report;

use App\Repositories\RepositoryInterface;

interface ExecutiveReportInterface extends RepositoryInterface
{
    public function listExecutiveReport();
    public function listStaffReport();
    public function listBranchExecutiveReport($id);
}
