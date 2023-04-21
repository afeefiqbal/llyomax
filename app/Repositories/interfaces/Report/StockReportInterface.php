<?php

namespace App\Repositories\interfaces\Report;

use App\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

interface StockReportInterface extends RepositoryInterface
{
    public function listProductStockReport();
    public function listProductStockReportByDate(Request $request);
    public function listCategoryStockReport();
    public function listOrderReport();
    public function listOrderReportByDate(Request $request);

}
