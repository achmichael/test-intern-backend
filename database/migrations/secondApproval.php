<?php

namespace Database\Migration;

use Database\Connection;

class SecondApprovals {
    
    protected $db;

    public function __construct() {
        $connection = new Connection();

        $this->db = $connection->getDb();
    }
    public function migrate() {
        $this->createSecondApprovalsTable();
    }
    public function createSecondApprovalsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS second_approvals(
            id INT AUTO_INCREMENT PRIMARY KEY,
            approval_id INT NOT NULL,
            approved_by INT NOT NULL,
            role ENUM('admin', 'direktur') NOT NULL,
            approval_status ENUM('approved_by_direktur', 'rejected') NOT NULL,
            approved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (approval_id) REFERENCES approvals(id),
            FOREIGN KEY (approved_by) REFERENCES users(id)
        )";

        $exec = $this->db->query($sql);

        if($exec === true){
            echo "Second Approvals table created successfully\n";
        }else{
            echo "Error creating Approvals table: ". $this->db->error;
        }
    }
}