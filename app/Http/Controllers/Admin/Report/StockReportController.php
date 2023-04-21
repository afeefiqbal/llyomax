<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Repositories\interfaces\Report\StockReportInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class StockReportController extends Controller
{
    protected $stockReportInterface;

    public function __construct(StockReportInterface $stockReportInterface)
    {
        $this->stockReportInterface = $stockReportInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {

                if(isset($request->from_date) && isset($request->to_date)){
                    $stockReports = $this->stockReportInterface->listProductStockReportByDate($request);
                }else{
                    $stockReports = $this->stockReportInterface->listProductStockReport();
                }
                return DataTables::of($stockReports)
                ->addIndexColumn()
                ->addColumn('status', function ($productReport) {
                    if ($productReport->status == 1) {
                    return '<span class="badge badge-success">In Stock</span>';
                    } else {
                        return '<span class="badge badge-danger">Out od stock</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '
                    <a href="branches/' . $row->id . '" data-id="' . $row->id . '" class="view btn btn-primary btn-floating btn-sm">
                            <i class="la la-eye"></i>
                        </a>
                    <a href="branches/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
                        <i class="la la-pencil"></i>
                    </a>
                    <a data-id="' . $row->id . '" class="delete btn btn-danger btn-floating btn-sm">
                        <i class="la la-trash"></i>
                    </a>';
                    return $btn;
                })
                ->rawColumns(['action','status'])
                ->make(true);
        }
        return view('backend.reports.stock-report');
    } catch (Exception $e) {
        Log::info($e->getMessage());
        $e->getCode();
        $e->getMessage();
        throw $e;
    }
}

}
