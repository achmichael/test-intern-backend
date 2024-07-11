<?php

namespace App\Models;

class Karyawan{
    private $karyawan_id;
    private $username;
    private $password;
    private $role;

    public function __construct($karyawan_id ,$username, $password ,$role){
        $this->karyawan_id = $karyawan_id;
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
    }
    
    public function getUsername():String
    {
        return $this->username;
    }

    public function getPassword():String
    {
        return $this->password;
    }

    public function getRole():String
    {
        return $this->role;
    }

    //penggunaan keyword self digunakan agar bisa melakukan chaining method
    public function setPassword(String $newPassword): self{
        $this->password = $newPassword;
        return $this;
    }

    public function setUsername(String $newUsername): self{
        $this->username = $newUsername;
        return $this;
    }    
    
    public function setRole(String $newRole): self{
        $this->role = $newRole;
        return $this;
    }

    public function setId(int $newId):self{
        $this->karyawan_id = $newId;
        return $this;
    }

    public function getId():int{
        return $this->karyawan_id;
    }

    public function toArray() {
        return [
            'id' => $this->karyawan_id,  
            'username' => $this->username,
            'password' => $this->password,
            'role' => $this->role,
        ];
    }
}