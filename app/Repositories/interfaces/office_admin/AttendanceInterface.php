<?php

namespace App\Repositories\interfaces\office_admin;

use App\Repositories\RepositoryInterface;

interface AttendanceInterface extends RepositoryInterface
{
    public function listAttendance($branch_id);
    public function createAttendance($request);
   // public function updateAttendance($request,$date,$branch);
}
