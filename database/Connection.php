<?php

namespace Database;

use mysqli;

class Connection
{
    private $db;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        $this->db = new mysqli("localhost", "root", "", "test_backend");

        if (!$this->db) {
            echo $this->db->lastErrorMsg();
        } 
        
    }

    public function getDb()
    {
        return $this->db;
    }

    public function closeConnection()
    {
        $this->db->close();
    }

}