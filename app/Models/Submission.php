<?php

namespace App\Models;

class Submission {

    private $purchase_id;
    private $karyawan_id;
    private $equipmentType;
    private $quantity;
    private $reason;
    private $status;

    public function __construct($purchase_id ,$karyawan_id, $equipmentType, $quantity, $reason, $status){
        $this->purchase_id = $purchase_id;
        $this->karyawan_id = $karyawan_id;
        $this->equipmentType = $equipmentType;
        $this->quantity = $quantity;
        $this->reason = $reason;
        $this->status = $status;
    }

    public function setKaryawanId($karyawan_id):self{
        $this->karyawan_id = $karyawan_id;
        return $this;
    } 
    public function getKaryawanId():string
    {
        return $this->karyawan_id;
    }
    public function setEquipmentType($equipmentType):self{
        $this->equipmentType = $equipmentType;
        return $this;
    }
    public function getEquimentType():string{
        return $this->equimentType;
    }
    public function setQuantity($quantity):self{
        $this->quantity = $quantity;
        return $this;
    }
    public function getQuantity():string{
        return $this->quantity;
    }
    public function setReason($reason):self{
        $this->reason= $reason;
        return $this;
    }
    public function getReason():string{
        return $this->reason;
    }
    public function toArray () {
        return [
            'id' => $this->purchase_id,  
            'karyawan_id' => $this->karyawan_id,
            'jenis_alat_berat' => $this->equipmentType,
            'jumlah' => $this->quantity,
            'alasan' => $this->reason,
            'status' => $this->status
        ];
    }
    
}