<?php

namespace App\Repositories\interfaces\Branch;

use App\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

interface AmountTransferDetailInterface extends RepositoryInterface
{
    public function listAmountTransferDetails(Request $args);
    public function listExecutiveAmountTransferDetails(Request $args,$id);
    public function listAllExecutiveAmountTransferDetails(Request $args);
    public function listBranchAmountTransferDetails(Request $args,$id);
    public function createAmountTransfer(Request $args);
    public function listBranchExecutiveAmountTransferDetails(Request $args,$id);
    public function deleteAmountTransferDetails(Int $id);
}
