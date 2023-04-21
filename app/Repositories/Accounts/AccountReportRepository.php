<?php

namespace App\Repositories\Accounts;

use App\Repositories\BaseRepository;
use App\Repositories\interfaces\Accounts\AccountReportInterface;

class AccountReportRepository extends BaseRepository implements AccountReportInterface
{
    public function getModel()
    {
        return \App\Models\Accounts\Expense::class;
    }
}
