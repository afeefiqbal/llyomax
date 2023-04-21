<?php

namespace App\Repositories\interfaces;

use App\Repositories\RepositoryInterface;

interface ExecutiveLeaveInterface extends RepositoryInterface
{
    public function listExecutivesLeaveform();
    public function listUserExecutivesLeaveform($id);
    public function listBranchExecutivesLeaveform($id);
    public function createLeave($request);
    public function updateLeave($request,$id);
}
