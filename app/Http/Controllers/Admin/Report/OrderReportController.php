<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Repositories\interfaces\Report\StockReportInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class OrderReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(StockReportInterface $stockReportInterface)
    {
        $this->stockReportInterface = $stockReportInterface;
    }

    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {

                if(isset($request->from_date) && isset($request->to_date)){
                    $stockReports = $this->stockReportInterface->listOrderReportByDate($request);
                }else{
                    $stockReports = $this->stockReportInterface->listOrderReport();
                }
                return DataTables::of($stockReports)
                ->addIndexColumn()
                ->addColumn('status', function ($productReport) {
                    if ($productReport->status == 0) {
                    return '<span class="badge badge-success">Pending</span>';
                    } else if($productReport->status == 1){
                        return '<span class="badge badge-success">delivered</span>';
                    }
                    else if($productReport->status == 2){
                        return '<span class="badge badge-info">cancelled</span>';
                    }
                    else if($productReport->status == 3){
                        return '<span class="badge badge-warning">to be delivered</span>';
                    }
                })
                ->addColumn('customer_details', function ($row) {
                    return $row->customer->customer_id . ' - ' . $row->customer->name. ' - ' . $row->customer->phone;
                })
                ->addColumn('delivery_status', function ($row) {
                    return $row->deliveryOrder->is_delivered == 1 ? '<span class="badge badge-success">Delivered</span>' : '<span class="badge badge-danger">Not Delivered</span>';
                })
                ->addColumn('delivery_date', function ($row) {
                    return $row->deliveryOrder->delivery_date->format('F m-y');
                })
                ->addColumn('order_date', function ($row) {
                    return $row->order_date->format('F m-y');
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
                ->rawColumns(['action','status','delivery_status'])
                ->make(true);
        }
        return view('backend.reports.order-report');
    } catch (Exception $e) {
        Log::info($e->getMessage());
        $e->getCode();
        $e->getMessage();
        throw $e;
    }
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
