<?php 

namespace App\Models;

class SecondApprovals{
    private $id;
    private $approval_id;
    private $approved_by;
    private $role;
    private $approval_status;
    private $approved_at;

    public function __construct($id, $approval_id, $approved_by, $role, $approval_status, $approved_at) {
        $this->id = $id;
        $this->approval_id = $approval_id;
        $this->approved_by = $approved_by;
        $this->role = $role;
        $this->approval_status = $approval_status;
        $this->approved_at = $approved_at;
    }

    public function getId() :int 
    {
        return $this->id;
    }

    public function getApprovalId() :int{
        return $this->approval_id;
    }
    
    public function getApprovedBy() : int
    {
        return $this->approval_id;
    }

    public function getRole() : string
    {
        return $this->role;
    }

    public function getApprovalStatus() : string
    {
        return $this->approval_status;
    }

    public function getApprovedAt() : DateTime
    {
        return new DateTime($this->approved_at);
    }
    
    public function toArray () {
        return [
            'id' => $this->id,
            'approval_id' => $this->approval_id,
            'approved_by' => $this->approved_by,
            'role' => $this->role,
            'approval_status' => $this->approval_status,
            'approved_at' => $this->approved_at,
        ];
    }
}