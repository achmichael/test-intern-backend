<?php

namespace Database\Migration;

use Database\Connection;

class Report{

    protected $db;

    public function __construct() {
        $connection = new Connection();

        $this->db = $connection->getDb();
    }

    public function migrate() {
        $this->createTableReport();
    }
    public function createTableReport() {
        $sql = "CREATE TABLE IF NOT EXISTS reports(
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            generated_by INT NOT NULL,
            created_at TIMESTAMP default CURRENT_TIMESTAMP,
            FOREIGN KEY (generated_by) REFERENCES users(id)
        )";

        $exec = $this->db->query($sql);

        if($exec === true){
            echo "Reports table created successfully";
        }else{
            echo "Error creating Reports table: ". $this->db->error;
        }
    }
}