<?php

namespace App\Repositories;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../database/Connection.php';
use App\Models\SecondApprovals;
use Database\Connection;

class SecondApprovalRepository
{

    protected $db;

    public function __construct()
    {
        $this->db = new Connection();
    }

    public function addSecondApproval($approval_id, $approved_by, $role, $approval_status): bool
    {
        try {
            $sql = "INSERT INTO `second_approvals` (approval_id, approved_by, role, approval_status) VALUES (?,?,?,?)";

            $stmt = $this->db->getDb()->prepare($sql);

            $stmt->bind_param('iiss', $approval_id, $approved_by, $role, $approval_status);

            $result = $stmt->execute();

            $stmt->close();

            return $result;
        } catch (\mysqli_sql_exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    function getApprovedByAdmin()
    {
        try {
            $sql = "SELECT * FROM `purchase_requests` WHERE status = ?";
            $stmt = $this->db->getDb()->prepare($sql);

            $status = 'approved_by_admin';
            $stmt->bind_param('s', $status);
            $stmt->execute();

            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);

            $stmt->close();

            return $data;
        } catch (\mysqli_sql_exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

}
