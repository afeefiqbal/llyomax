<?php
namespace App\Repositories\Report;

use App\Repositories\RepositoryInterface;

interface ReportInterface extends RepositoryInterface
{
    public function listStaffAttendanceReport($request);
}
