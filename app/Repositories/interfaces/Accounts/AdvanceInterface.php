<?php

namespace App\Repositories\interfaces\Accounts;

use App\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

interface AdvanceInterface extends RepositoryInterface
{
    public function listAdvance();
    public function createAdvance(Request $request);
    public function updateAdvance(Request $request, $id);
    public function deleteAdvance(Request $request, $id);
    public function getAdvance($id);
}
