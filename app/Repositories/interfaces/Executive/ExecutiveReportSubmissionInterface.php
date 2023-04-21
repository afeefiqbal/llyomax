<?php

namespace App\Repositories\interfaces\Executive;

use App\Repositories\RepositoryInterface;

interface ExecutiveReportSubmissionInterface extends RepositoryInterface
{
    public function createSubmission($request);
}
