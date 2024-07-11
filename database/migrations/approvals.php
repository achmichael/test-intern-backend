<?php 
namespace Database\Migration;

use Database\Connection;

class Approvals {
    protected $db;

    public function __construct() {
        $connection = new Connection();

        $this->db = $connection->getDb();
    }
    public function migrate() {
        $this->createApprovalsTable();
    }
    public function createApprovalsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS approvals(
            id INT AUTO_INCREMENT PRIMARY KEY,
            purchase_request_id INT UNSIGNED NOT NULL,
            approved_by INT NOT NULL,
            role ENUM('admin', 'direktur') NOT NULL,
            approval_status ENUM('approved_by_admin','approved_by_direktur' ,'rejected') NOT NULL,
            FOREIGN KEY (purchase_request_id) REFERENCES purchase_requests(id),
            FOREIGN KEY (approved_by) REFERENCES users(id)
        )";

        $exec = $this->db->query($sql);

        if($exec === true){
            echo "Approvals table created successfully\n";
        }else{
            echo "Error creating Approvals table: ". $this->db->error;
        }
    }
}

?>