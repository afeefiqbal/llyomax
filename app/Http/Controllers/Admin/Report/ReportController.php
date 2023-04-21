<?php
namespace App\Http\Controllers\Admin\Report;
use App\Http\Controllers\Controller;
use App\Models\Branch\LuckyDraw;
use App\Models\Master\Branch;
use App\Models\Office_admin\Attendance;
use App\Repositories\Report\LuckyDrawReportInterface;
use App\Repositories\Report\ReportInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
class ReportController extends Controller
{
    protected $luckyDrawReportInterface;
    protected $reportInterface;
    public function __construct(LuckyDrawReportInterface $luckyDrawReportInterface, ReportInterface $reportInterface)
    {
        $this->luckyDrawReportInterface = $luckyDrawReportInterface;
        $this->reportInterface = $reportInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function luckyDraw(Request $request)
    {
        try {
            if ($request->ajax()) {
                
                if ($request->branch_id == 0 && $request->scheme_id == 0 ) {
                    $brluckyDrawReportInterfaceanch = LuckyDraw::whereBetween('draw_date', [$request->from_date, $request->to_date])->orderBy('id','desc')->get();
                }elseif ($request->branch_id != 0 && $request->scheme_id == 0 ) {
                    $brluckyDrawReportInterfaceanch = LuckyDraw::where('branch_id',$request->branch_id)->whereBetween('draw_date', [$request->from_date, $request->to_date])->orderBy('id','desc')->get();
                }elseif ($request->branch_id != 0 && $request->scheme_id != 0 ) {
                    $brluckyDrawReportInterfaceanch = LuckyDraw::where('branch_id',$request->branch_id)->where('scheme_id',$request->scheme_id)->whereBetween('draw_date', [$request->from_date, $request->to_date])->orderBy('id','desc')->get();
                }
            
                return DataTables::of($brluckyDrawReportInterfaceanch)
                    ->addIndexColumn()
                    ->addColumn('scheme_id', function ($row) {
                        return $row->scheme->scheme_id;
                    })
                    ->addColumn('scheme_name', function ($row) {
                        return $row->scheme->name;
                    })
                    ->addColumn('week', function ($row) {
                        if ($row->week == '1') {
                            return 'Week 1';
                        }
                        if ($row->week == '2') {
                            return 'Week 2';
                        }
                        if ($row->week == '3') {
                            return 'Week 4';
                        }
                        if ($row->week == '4') {
                            return 'Week 4';
                        }
                        if ($row->week == '5') {
                            return 'Week 5';
                        }
                        if ($row->week == '6') {
                            return 'Week 6';
                        }
                        if ($row->week == '7') {
                            return 'Week 7';
                        }
                        if ($row->week == '8') {
                            return 'Week 8';
                        }
                        if ($row->week == '9') {
                            return 'Week 9';
                        }
                        if ($row->week == '10') {
                            return 'Week 10';
                        }
                        if ($row->week == '11') {
                            return 'Week 11';
                        }
                        if ($row->week == '12') {
                            return 'Week 12';
                        }
                        if ($row->week == '13') {
                            return 'Week 13';
                        }
                        if ($row->week == '14') {
                            return 'Week 14';
                        }
                        if ($row->week == '15') {
                            return 'Week 15';
                        }
                        if ($row->week == '16') {
                            return 'Week 16';
                        }
                        if ($row->week == '17') {
                            return 'Week 17';
                        }
                        if ($row->week == '18') {
                            return 'Week 18';
                        }
                        if ($row->week == '19') {
                            return 'Week 19';
                        }
                        if ($row->week == '20') {
                            return 'Week 20';
                        }
                        if ($row->week == '21') {
                            return 'Week 21';
                        }
                        if ($row->week == '22') {
                            return 'Week 22';
                        }
                        if ($row->week == '23') {
                            return 'Week 23';
                        }
                        if ($row->week == '24') {
                            return 'Week 24';
                        }
                        if ($row->week == '25') {
                            return 'Week 25';
                        }
                        if ($row->week == '26') {
                            return 'Week 26';
                        }
                        if ($row->week == '27') {
                            return 'Week 27';
                        }
                        if ($row->week == '28') {
                            return 'Week 28';
                        }
                        if ($row->week == '29') {
                            return 'Week 29';
                        }
                        if ($row->week == '30') {
                            return 'Week 30';
                        }
                    })
                    // ->addColumn('previous_draw_date', function ($row) {
                    //     return $row->latest()->first()->draw_date;
                    // })
                    ->addColumn('lucky_draw_winner', function ($row) {
                        return $row->customer->customer_id . '- ' . $row->customer->name;
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
                    ->rawColumns(['action'])
                    ->make(true);
            }
            $branches = Branch::get();
            return view('backend.reports.lucky_draw_report', compact('branches'));
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
    public function staffAttendance(Request $request)
    {
        try {
            if ($request->ajax()) {
                $reportInterface = $this->reportInterface->listStaffAttendanceReport($request);
                return DataTables::of($reportInterface)
                    ->addIndexColumn()
                    ->addColumn('branch_id', function ($row) {
                        return $row->branch_id;
                    })
                    ->addColumn('branch_name', function ($row) {
                        return $row->branch_name;
                    })
                    ->addColumn('present', function ($row) use ($request) {
                        $presentCount = Attendance::where('branch_id', $row->id)->whereDate('date', $request->date)->where('attendance', 1)->count();
                        return $presentCount;
                    })
                    ->addColumn('absent', function ($row) use ($request) {
                        $absentCount = Attendance::where('branch_id', $row->id)->whereDate('date', $request->date)->where('attendance', 0)->count();
                        return $absentCount;
                    })
                    ->addColumn('late', function ($row) use ($request) {
                        $lateCount = Attendance::where('branch_id', $row->id)->whereDate('date', $request->date)->where('late', 1)->count();
                        return $lateCount;
                    })
                    ->addColumn('attendence_details', function ($row) use ($request) {
                        return '<a class="edit btn btn-info btn-floating btn-sm" href="' . url('admin/reports/staff-attendance-details/'. $row->id.'/'.$request->date) .'"><i class="la la-eye"></i></a>'; 
                      })
                    ->rawColumns([ 'attendence_details'])
                    ->make(true);
            }
            $branches = Branch::get();
            return view('backend.reports.staff-attendance-report', compact('branches'));
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }
    public function showStaffAttendence($branch,$date)
    {
        $attendances = Attendance::where(['branch_id' => $branch])->whereDate('date',$date)->get();
        $branch = Branch::find($branch);
        return view('backend.reports.show-attendence-details', compact('attendances', 'branch'));
    }
}
