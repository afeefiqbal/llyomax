<?php

namespace App\Repositories\interfaces\Accounts;

use App\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

interface WeeklyGiftInterface extends RepositoryInterface
{
    public function listWeeklyGifts();
    public function createWeeklyGift(Request $request);
    public function updateWeeklyGift(Request $request, $id);
    public function deleteWeeklyGift(Request $request, $id);
    public function getWeeklyGift($id);
}
