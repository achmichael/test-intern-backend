<?php
namespace App\Services;

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Repositories\KaryawanRepository;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService
{

    private $karyawanRepository;

    public function __construct()
    {
        $this->karyawanRepository = new KaryawanRepository();
    }

    public function authenticate($username, $password)
    {
        try {
            $karyawan = $this->karyawanRepository->findUser($username);
            if ($karyawan !== null && ($karyawan['password'] != '' || $karyawan['password'] != null) && password_verify($password, $karyawan['password'])) {
                $domain = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];

                $payload = [
                    "iss" => $domain,
                    "aud" => $domain,
                    "iat" => time(),
                    "exp" => time() + (60 * 60),
                    "data" => [
                        "id" => $karyawan['id'],
                        "username" => $karyawan['username'],
                    ],
                ];

                $jwt = @JWT::encode($payload, '123', 'HS256');
                return $jwt;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function validateToken($jwt)
    {
        try {
            $decoded = JWT::decode($jwt, new Key('123', 'HS256'));
            return $decoded->data;//mengembalikan data payload dari token jwt
        } catch (\Exception $e) {
            throw new \Exception('Token Is Expired');
        }
    }

    public function getUserRole($username) {
        $user = $this->karyawanRepository->findUser($username);
        return $user['role'];//mereturn key role dari array asosiatif
    }

    public function getUserRoleById($id){
        $user = $this->karyawanRepository->findUserById($id);
        return $user['role'];
    }
    
}
