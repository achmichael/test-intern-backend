<?php

namespace App\Middlewares;

class LoginMiddleware
{

    public function validateLoginInput($request)
    {
        if (empty($request['username'])) {
            return Response::error('Please enter a username');
        }

        if (empty($request['password'])) {
            return Response::error('Please enter a password');
        } else if (!$this->validatePassword($request['password'])) {
            return Response::error("Password harus terdiri dari 8 karakter, mengandung minimal satu huruf besar, huruf kecil, angka, dan karakter spesial.");
        }

        $valid_roles = ['karyawan', 'admin', 'direktur'];
        if (empty($request['role'])) {
            return Response::error('Please enter a role');
        } else if (!in_array($request['role'], $valid_roles)) {
            return Response::error('Invalid role. Valid roles: karyawan, admin, direktur');
        }
        return true;
    }
    
    function validatePassword($password)
    {
        $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/';
        return preg_match($pattern, $password);
    }
}
