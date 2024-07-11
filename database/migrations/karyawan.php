<?php 
namespace Database\Migration;
use Database\Connection;

class Karyawan {
    
    protected $db;

    public function __construct() {
        $connection = new Connection();

        $this->db = $connection->getDb();
    }

    public function migrate() {
        $this->createUsersTable();
    }
    public function createUsersTable() {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(100) NOT NULL,
            role ENUM('karyawan', 'admin', 'direktur') NOT NULL
        )";

        $exec = $this->db->query($sql);

        if($exec === true){
            echo "Users table created successfully\n";
        }else{
            echo "Error creating Users table: ". $this->db->error;
        }
    }
}
?>