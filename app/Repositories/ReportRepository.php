<?php

namespace App\Repositories;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../database/Connection.php';

use App\Models\SecondApprovals;
use Database\Connection;

class ReportRepository{

    protected $db;

    public function __construct() {
        $this->db = new Connection();
    }
    public function getWeeklyReports($startDate, $endDate)
    {
        try {
            $sql = "SELECT * FROM second_approvals
                WHERE approved_at BETWEEN ? AND ?";

            $stmt = $this->db->getDb()->prepare($sql);
            if ($stmt) {
                $stmt->bind_param('ss', $startDate, $endDate);
                $reports = [];
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $secondApproval = new SecondApprovals(
                        $row['id'],
                        $row['approval_id'],
                        $row['approved_by'],
                        $row['role'],
                        $row['approval_status'],
                        $row['approved_at']
                    );
                    $reports[] = $secondApproval->toArray();
                }
                $stmt->close();

                return $reports;
            }
            return null;
        } catch (\mysqli_exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getMonthlyReports($startDate, $endDate)
    {
        try {
            $sql = "SELECT * FROM second_approvals
                WHERE approved_at BETWEEN ? AND ?";

            $stmt = $this->db->getDb()->prepare($sql);

            if ($stmt) {
                $reports = [];
                $stmt->bind_param('ss', $startDate, $endDate);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $secondApproval = new SecondApprovals(
                        $row['id'],
                        $row['approval_id'],
                        $row['approved_by'],
                        $row['role'],
                        $row['approval_status'],
                        $row['approved_at']
                    );
                    $reports[] = $secondApproval->toArray();
                }
                $stmt->close();

                return $reports;
            }
            return null;
        } catch (\mysqli_exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}