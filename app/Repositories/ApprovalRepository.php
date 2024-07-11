<?php

namespace App\Repositories;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../database/Connection.php';

use App\Models\Approvals;
use Database\Connection;

class ApprovalRepository
{
    protected $db;

    public function __construct()
    {
        $this->db = new Connection();
    }

    public function addApproval($purchase_request_id, $approved_by, $role, $approval_status): bool
    {
        try {
            $sql = "INSERT INTO `approvals` (`purchase_request_id`, `approved_by`, `role`, `approval_status`) VALUES (?, ?, ?, ?)";

            $stmt = $this->db->getDb()->prepare($sql);

            $stmt->bind_param('iiss', $purchase_request_id, $approved_by, $role, $approval_status);

            $result = $stmt->execute();

            $stmt->close();

            return $result;
        } catch (\mysqli_sql_exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    function getPendingRequests()
    {
        try {
            $sql = "SELECT * FROM `purchase_requests` WHERE status = 'pending'";
            $result = $this->db->getDb()->query($sql);

            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (\mysqli_exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getApproval($purchase_request_id)
    {
        try {
            $sql = "SELECT * FROM `approvals` WHERE purchase_request_id = ?";

            $stmt = $this->db->getDb()->prepare($sql);

            if ($stmt === false) {
                throw new \Exception($this->db->getDb()->error);
            }

            $stmt->bind_param('i', $purchase_request_id);

            $stmt->execute();

            $result = $stmt->get_result();

            $approval = null;
            if ($row = $result->fetch_assoc()) {
                $approval = new Approvals(
                    $row['id'],
                    $row['purchase_request_id'],
                    $row['approved_by'],
                    $row['role'],
                    $row['approval_status']
                );
            }

            $stmt->close();

            if ($approval) {
                return $approval->toArray();
            } else {
                return null;
            }
        } catch (\mysqli_sql_exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
    public function updateStatus($id, $status)
    {
        try {
            $sql = "UPDATE `approvals` SET approval_status = ? WHERE id = ?";

            $stmt = $this->db->getDb()->prepare($sql);

            $stmt->bind_param('si', $status, $id);

            $result = $stmt->execute();

            $stmt->close();

            return $result;
        } catch (\mysqli_sql_exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

}
