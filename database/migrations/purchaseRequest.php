<?php 
namespace Database\Migration;

use Database\Connection;

class PurchaseRequest {

    protected $db;

    public function __construct() {
        $connection = new Connection();

        $this->db = $connection->getDb();
    }

    public function migrate() {
        $this->createPurchaseRequestTable();
    }

    public function createPurchaseRequestTable () {
        $sql = "CREATE TABLE IF NOT EXISTS purchase_requests (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            karyawan_id INT NOT NULL, 
            jenis_alat_berat VARCHAR(255) NOT NULL,
            jumlah INT NOT NULL,
            alasan TEXT NOT NULL,
            status ENUM('pending', 'approved_by_admin', 'approved_by_direktur', 'rejected') DEFAULT 'pending',
            FOREIGN KEY (karyawan_id) REFERENCES users(id)
        )";

        $exec = $this->db->query($sql);

        if($exec === true){
            echo "PurchaseRequest table created successfully\n";
        }else{
            echo "Error creating PurchaseRequest table: ". $this->db->error;
        }
    }
}

?>