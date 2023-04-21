<?php

namespace App\Repositories\Report;

use App\Models\Report\StockReport;
use App\Models\Warehouse\Category;
use App\Models\Warehouse\Order;
use App\Models\Warehouse\Product;
use App\Repositories\BaseRepository;
use App\Repositories\interfaces\Report\StockReportInterface;

class StockReportRepository extends BaseRepository  implements StockReportInterface
{
    public function getModel()
    {
        return StockReport::class;
    }
    public function listProductStockReport()
    {
        return Product::all();
    }
    public function listCategoryStockReport(){
        return Category::all();
    }
   public function listProductStockReportByDate($request){
        return Product::whereBetween('created_at', [$request->from_date, $request->to_date])->get();
    }
    public function listOrderReport(){
        return Order::all();
    }
    public function listOrderReportByDate($request){
        return Order::whereBetween('created_at', [$request->from_date, $request->to_date])->get();
    }
}
