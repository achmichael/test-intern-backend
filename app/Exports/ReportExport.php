<?php

namespace App\Exports;

require_once __DIR__ . '/../Repositories/ReportRepository.php';

use App\Models\Report;
use App\Repositories\ReportRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WeeklyReportsExport implements FromCollection, WithHeadings
{
    protected $reportRepository;
    protected $startDate;
    protected $endDate;
    public function __construct(ReportRepository $reportRepository, $startDate, $endDate)
    {
        $this->reportRepository = $reportRepository;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $reports = $this->reportRepository->getWeeklyReports($this->startDate, $this->endDate);
        \Log::info('Weekly Reports:', $reports); // Logging untuk memastikan data sudah benar
    
        $collection = collect($reports);
    
        \Log::info('Export Collection:', $collection->toArray()); // Logging
        return $collection;
    }
    

    public function headings(): array
    {
        return [
            'ID',
            'Approval ID',
            'Approved By',
            'Role',
            'Approval Status',
            'Approved At',
        ];
    }
}

class MonthlyReportsExport implements FromCollection, WithHeadings
{
    protected $reportRepository;
    protected $startDate;
    protected $endDate;

    public function __construct(ReportRepository $reportRepository, $startDate, $endDate)
    {
        $this->reportRepository = $reportRepository;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        //membuat collection dari database yang dikembalikan sebagai array
        return collect($this->reportRepository->getMonthlyReports($this->startDate, $this->endDate));
    }

    public function headings(): array
    {
        return [
            'ID',
            'Approval ID',
            'Approved By',
            'Role',
            'Approval Status',
            'Approved At',
        ];
    }
}
