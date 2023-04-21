<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\ExtraBonus;
use App\Repositories\BaseRepository;
use App\Repositories\interfaces\Accounts\ExtraBonusInterface;

class ExtraBonusRepository extends BaseRepository implements ExtraBonusInterface
{
    public function getModel()
    {
        return ExtraBonus::class;
    }
    public function listExtraBonuses()
    {
        return $this->model->all();
    }
    public function getExtraBonusById($id)
    {

        return $this->model->find($id);
    }
    public function createExtraBonus($request)
    {
        $extraBonus = $this->model->create($request->all());
        return $extraBonus;

    }
    public function updateExtraBonus($request, $id)
    {
        $extraBonus = $this->model->find($id);
        $extraBonus->update($request->all());

        return $extraBonus;

    }
    public function deleteExtraBonus($id)
    {
        $extraBonus = $this->model->find($id);
        $extraBonus->delete();
        return $extraBonus;

    }
}
