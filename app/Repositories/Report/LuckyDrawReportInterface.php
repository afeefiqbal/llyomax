<?php
namespace App\Repositories\Report;

use App\Repositories\RepositoryInterface;

interface LuckyDrawReportInterface extends RepositoryInterface
{
    public function listLuckyDrawReports($request);
}
