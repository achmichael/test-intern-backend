<?php

namespace App\Http\Controllers\Auth;

require_once __DIR__ . '/../../Repositories/KaryawanRepositories.php';
require_once __DIR__ . '/../Middleware/ValidateRegisterInput.php';

use App\Http\Controllers\Controller;
use App\Middlewares\RegisterMiddleware;
use App\Repositories\KaryawanRepository;

class RegisterController extends Controller
{
    protected $karyawanRepository;
    protected $registerMiddleware;
    public function __construct(KaryawanRepository $karyawanRepository)
    {
        $this->registerMiddleware = new RegisterMiddleware();
        $this->karyawanRepository = $karyawanRepository;
    }

    public function showRegistrasiForm()
    {
        return view('register', ['title' => 'Register']);
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';

            if ($contentType === 'application/json') {
                $content = trim(file_get_contents('php://input')); //membaca data yang tidak di kodekan seperti json yang dikirim melalui dalam request HTTP
                $decoded = json_decode($content, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    $request = [
                        'username' => $decoded['username'] ?? null,
                        'password' => $decoded['password'] ?? null,
                        'repassword' => $decoded['repassword'] ?? null,
                        'role' => $decoded['role'] ?? null,
                    ];
                }
            } else {
                $request = [
                    'username' => $_POST['username'] ?? null,
                    'password' => $_POST['password'] ?? null,
                    'repassword' => $_POST['repassword'] ?? null,
                    'role' => $_POST['role'] ?? null,
                ];
            }

            $validationResult = $this->registerMiddleware->validateRegisterInput($request);

            if($validationResult !== true){
                return $validationResult;
                exit;
            }
            try {
                $result = $this->karyawanRepository->add($request['username'], $request['password'], $request['role']);

                if ($result) {
                    return response()->json(['message' => 'Registrasi berhasil', 'data' => $result], 200);
                } else {
                    return response()->json(['message' => 'Registrasi Gagal', 'data' => $result], 500);
                }
            } catch (\Exception $e) {
                if ($e->getMessage() === 'Duplicate entry found.') {
                    return response()->json(['message' => 'Username sudah digunakan. Harap menggunakan username lain.'], 409); // Conflict status code
                } else {
                    return response()->json(['message' => 'Terjadi kesalahan dalam menyimpan data', 'error' => $e->getMessage()], 500);
                }
            }
        } else {
            return response()->json(['message' => 'Invalid request method'], 405);
        }
    }
}
