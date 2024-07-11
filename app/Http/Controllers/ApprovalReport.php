<?php

namespace App\Http\Controllers;

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../Repositories/ReportRepository.php';
require_once __DIR__ . '/../../Exports/ReportExport.php';

use App\Exports\WeeklyReportsExport;
use App\Exports\MonthlyReportsExport;
use App\Repositories\ReportRepository;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Config\Response;
use Illuminate\Support\Collection;

class ApprovalReportController extends Controller
{
    protected $reportRepository;

    public function __construct()
    {
        $this->reportRepository = new ReportRepository();
    }
    
    public function generateReport(Request $request)
    {
        \Log::info('generateReport called', $request->all());
        
        $periodType = $request->input('period_type');
        $startDate = Carbon::parse($request->input('start_date'));

        if ($periodType === 'weekly') {
            $endDate = $startDate->copy()->addDays(7);
            $approvals = collect($this->reportRepository->getWeeklyReports($startDate, $endDate));
        } elseif ($periodType === 'monthly') {
            $endDate = $startDate->copy()->addMonth();
            $approvals = collect($this->reportRepository->getMonthlyReports($startDate, $endDate));
        } else {
            return back()->with('error', 'Invalid period type');
        }
        // return Response::error($approvals);
        return view('reports', compact('approvals', 'periodType', 'startDate', 'endDate'), ['title' => 'Report']);
    }

    public function exportExcel(Request $request)
    {
        $periodType = $request->input('period_type');
        $startDate = Carbon::parse($request->input('start_date'));

        if ($periodType === 'weekly') {
            $endDate = $startDate->copy()->addDays(7);
            return Excel::download(new WeeklyReportsExport($this->reportRepository, $startDate, $endDate), 'weekly_reports.xlsx');
        } elseif ($periodType === 'monthly') {
            $endDate = $startDate->copy()->addMonth();
            return Excel::download(new MonthlyReportsExport($this->reportRepository, $startDate, $endDate), 'monthly_reports.xlsx');
        } else {
            return Response::error('Invalid Periode Type');
        }
    }
}
