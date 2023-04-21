<?php

namespace App\Repositories\Branch;

use App\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

interface LuckyDrawInterface extends RepositoryInterface
{
    public function listLuckyDraws();
    public function listBranchLuckyDraws($id);
    public function listExecutiveBranchLuckyDraws($id);
    public function createLuckyDraw(Request $request);
    public function updateLuckyDraw(Request $request,$id);
    public function deleteLuckyDraw($id);
}
