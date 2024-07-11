<?php

namespace App\Models;

class Approvals{

    private $id;
    private $purchase_request_id;
    private $approved_by;
    private $role;
    private $approval_status;

    public function __construct($id, $purchase_request_id, $approved_by, $role, $approval_status) {
        $this->id = $id;
        $this->purchase_request_id = $purchase_request_id;
        $this->approved_by = $approved_by;
        $this->role = $role;
        $this->approval_status = $approval_status;
    }

    public function getId():int 
    {
        return $this->id;
    }

    public function getPurchaseRequestId():int{
        return $this->purchase_request_id;
    }
    
    public function getApprovedBy():int{
        return $this->approved_by;
    }
    
    public function getRole():string{
        return $this->role;
    }
    
    public function getApprovalStatus():string{
        return $this->approval_status;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'purchase_request_id' => $this->purchase_request_id,
            'approved_by' => $this->approved_by,
            'role' => $this->role,
            'approval_status' => $this->approval_status,
        ];
    }
}