<?php
namespace App\Repositories;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../database/Connection.php';

use Database\Connection;
use App\Models\Karyawan;

class KaryawanRepository
{
    protected $db;

    public function __construct()
    {
        $this->db = new Connection();
    }

    public function add($username, $password, $role): bool
    {
        try {
            $sql = "INSERT INTO `users` (`username`, `password`, `role`) VALUES (?, ?, ?);";
            $stmt = $this->db->getDb()->prepare($sql);

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt->bind_param('sss', $username, $hashedPassword, $role);

            
            $result = $stmt->execute();

            $stmt->close();

            return $result;
        } catch (\mysqli_sql_exception $e) {
            $errorCode = $e->getCode();
            if ($errorCode === 1062) {
                throw new \Exception('Duplicate entry found.');
            } else {
                throw new \Exception('Database error occurred: ' . $e->getMessage());
            }
        }
    }
    

    public function findUser($username) {
        try{
            $sql = "SELECT * FROM users WHERE username = ?;";

            $stmt = $this->db->getDb()->prepare($sql);

            if($stmt){
                $stmt->bind_param('s', $username);
                $stmt->execute();
                $result = $stmt->get_result();
                if($row = $result->fetch_assoc()){
                    $karyawan = new Karyawan(
                        $row['id'],
                        $row['username'],
                        $row['password'] != null ? $row['password'] : "-",
                        $row['role']
                    );
                    return $karyawan->toArray();
                }
                $stmt->close();
            }
            return null;    
        }catch(\mysqli_sql_exception $e){
            $errorCode = $e->getCode();
            if($errorCode === 1064){
                throw new \Exception('Invalid SQL syntax.');
            }else{
                throw new \Exception('Database error occurred: '. $e->getMessage());
            }
        }
    }

    public function findUserById($id) {
        try{
            $sql = "SELECT * FROM users WHERE id = ?;";

            $stmt = $this->db->getDb()->prepare($sql);

            if($stmt){
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $result = $stmt->get_result();
                if($row = $result->fetch_assoc()){
                    $karyawan = new Karyawan(
                        $row['id'],
                        $row['username'],
                        $row['password'] != null ? $row['password'] : "-",
                        $row['role']
                    );
                    return $karyawan->toArray();
                }
                $stmt->close();
            }
            return null;    
        }catch(\mysqli_sql_exception $e){
            $errorCode = $e->getCode();
            if($errorCode === 1064){
                throw new \Exception('Invalid SQL syntax.');
            }else{
                throw new \Exception('Database error occurred: '. $e->getMessage());
            }
        }
    }

    public function updatePassword($username, $newPassword): bool
    {
        try{
            $sql = "UPDATE users SET password = ? WHERE username = ?;";

            $stmt = $this->db->getDb()->prepare($sql);

            if($stmt){
                $stmt->bind_param('ss', $newPassword, $username);
                $stmt->execute();

                $stmt->close();
                return true;
            }
            return false;
        }catch(\mysqli_sql_exception $e){
            throw new \Exception($e->getMessage());
        }
    }
}
