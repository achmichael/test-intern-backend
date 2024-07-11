<?php

namespace App\Middlewares;

use Config\Response;

class RegisterMiddleware{

    public function validateRegisterInput($request){

        if(empty($request['username'])){
            return Response::error('Please enter a username');   
        }

        if(empty($request['password'])){
            return Response::error('Please enter a password');
        }else if(!$this->validatePassword($request['password']) && !$this->validatePassword($request['repassword'])){
            return Response::error('Password harus terdiri dari 8 karakter, mengandung minimal satu huruf besar, huruf kecil, angka, dan karakter spesial.');
        }else if($request['password'] !== $request['repassword']){
            return Response::error('Password dan Confirm Password harus sama');
        }

        $valid_role = ['karyawan', 'admin', 'direktur'];
        if(empty($request['role'])){
            return Response::error('Please select a role');
        }else if(!in_array($request['role'], $valid_role)){
            return Response::error('Role tidak cocok');
        }
        return true;
    }

    private function validatePassword($password)
    {
        $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/';
        return preg_match($pattern, $password);
    }
}