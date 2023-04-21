<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Executive\Executive;
use App\Models\Master\Branch;
use App\Repositories\Report\ManagerReportInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

use function PHPSTORM_META\type;

class ManagerReportController extends Controller
{
    protected $managerReportInterface;

    public function __construct(ManagerReportInterface $managerReportInterface)
    {
         $this->middleware(['role:super-admin|developer-admin|branch-manager']);
        $this->managerReportInterface = $managerReportInterface;
    }
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $managerReport = $this->managerReportInterface->listManagerReport();
                
              return DataTables::of($managerReport)
              ->addIndexColumn()
              ->addColumn('type', function($row){
                  if($row->type == 0){
                      return 'Marketing Manager';
                  }
                  elseif($row->type == 2){
                    return 'Collection Manager';
                  }
                  else{
                    return 'Branch Manager';
                  }
              })
              ->addColumn('no_of_executives', function($row){
               return $noOfExecutives = Executive::where('manager_id',$row->id)->count();
            })
            ->addColumn('branch', function($row){
                $branch = Branch::where('id',$row->branch_id)->first();
                if (isset($branch)) {
                  return $branch->branch_name;
                }
            })
            ->addColumn('joined_date', function($row){
                return $row->created_at->format('d-M-y');
            })
            ->addColumn('status', function($row){
                if($row->status == 1){
                    return 'active';
                }
                else
                {
                    return 'not active';
                }
            })
            ->make(true);

          }
            return view('backend.reports.manager-report');
        }
        catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
}
