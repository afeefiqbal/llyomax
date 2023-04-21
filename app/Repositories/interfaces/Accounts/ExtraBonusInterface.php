<?php

namespace App\Repositories\interfaces\Accounts;

use App\Repositories\RepositoryInterface;

interface ExtraBonusInterface extends RepositoryInterface
{
    public function listExtraBonuses();
    public function getExtraBonusById($id);
    public function createExtraBonus($request);
    public function updateExtraBonus($request, $id);
    public function deleteExtraBonus($id);
}
