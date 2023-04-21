<?php

namespace App\Repositories\Report;

use App\Repositories\RepositoryInterface;

interface ManagerReportInterface extends RepositoryInterface
{
    public function listManagerReport();
}
