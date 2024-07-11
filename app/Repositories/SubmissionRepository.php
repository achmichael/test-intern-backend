<?php 
namespace App\Repositories;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../database/Connection.php';

use Database\Connection;
use App\Models\Submission;

class SubmissionRepository
{
    protected $db;

    public function __construct(){
        $this->db = new Connection();
    }

    public function addSubmission($karyawan_id, $equipment_type, $quantity, $reason): bool 
    {  
        try{
            $sql = "INSERT INTO `purchase_requests` (`karyawan_id`,`jenis_alat_berat`, `jumlah`, `alasan`) VALUES (? , ?, ?, ?);";

            $stmt = $this->db->getDb()->prepare($sql);

            $stmt->bind_param('isis', $karyawan_id , $equipment_type, $quantity, $reason);

            $result = $stmt->execute();

            $stmt->close();

            return $result;
            
        }catch(\mysqli_sql_exception $e){
            throw new \Exception($e->getMessage());            
        }
    }

    public function findPurchaseId($id) {
        try{
            $sql = "SELECT * FROM `purchase_requests` WHERE `id` = ?";

            $stmt = $this->db->getDb()->prepare($sql);

            if($stmt){
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $result = $stmt->get_result();
                if($row = $result->fetch_assoc()){
                    $submission = new Submission(
                        $row['id'],
                        $row['karyawan_id'],
                        $row['jenis_alat_berat'],
                        $row['jumlah'],
                        $row['alasan'],
                        $row['status']
                    );
                    return $submission->toArray();
                }
                $stmt->close();
            }
            return null;
        }catch(\mysqli_sql_exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function getAllPurchase() {
        try{
            $sql = "SELECT * FROM `purchase_requests`";

            $stmt = $this->db->getDb()->prepare($sql);

            if($stmt){

                $stmt->execute();
                $result = $stmt->get_result();

                $requests = [];

                while($row = $result->fetch_assoc()){
                    $submission = new Submission(
                        $row['id'],
                        $row['karyawan_id'],
                        $row['jenis_alat_berat'],
                        $row['jumlah'],
                        $row['alasan'],
                        $row['status']
                    );
                    $requests[] = $submission->toArray();
                }
                $stmt->close();
                return $requests;
            }
        }catch(\mysqli_sql_exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function updateStatus($id, $status){
        try{
            $sql = "UPDATE `purchase_requests` SET status = ? WHERE id = ?";

            $stmt = $this->db->getDb()->prepare($sql);

            $stmt->bind_param('si', $status, $id);

            $result = $stmt->execute();

            $stmt->close();

            return $result;
        }catch(\mysqli_sql_exception $e){
            throw new \Exception($e->getMessage());
        }
    }
}
?>