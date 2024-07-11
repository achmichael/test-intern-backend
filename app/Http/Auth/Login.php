<?php
namespace App\Http\Controllers\Auth;

session_start();

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../Repositories/KaryawanRepositories.php';
require_once __DIR__ . '/AuthService.php';
require_once __DIR__ . '/../Middleware/ValidateLoginInput.php';

use App\Http\Controllers\Controller;
use App\Middlewares\LoginMiddleware;
use App\Repositories\KaryawanRepository;
use App\Services\AuthService;
use Config\Response;

class LoginController extends Controller
{
    protected $karyawanRepository;
    protected $authService;
    protected $loginMiddleware;

    public function __construct()
    {
        $this->karyawanRepository = new KaryawanRepository();
        $this->authService = new AuthService();
        $this->loginMiddleware = new LoginMiddleware();
    }

    public function showLoginForm()
    {
        return view('login', ['title' => 'Login']);
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';

            if ($contentType === 'application/json') {
                $content = trim(file_get_contents('php://input'));
                $decoded = json_decode($content, true);

                $request = [
                    'username' => $decoded['username'] ?? null,
                    'password' => $decoded['password'] ?? null,
                    'role' => $decoded['role'] ?? null,
                ];

            } else {

                $request = [
                    'username' => $_POST['username'] ?? null,
                    'password' => $_POST['password'] ?? null,
                    'role' => $_POST['role'] ?? null,
                ];

            }

            $validationResult = $this->loginMiddleware->validateLoginInput($request);

            if ($validationResult !== true) {
                return $validationResult;
                exit;
            }
            // return Response::success(['username' => $username, 'password' => $password, 'role' => $role]);
            try {
                $authenticated = $this->authService->authenticate($request['username'], $request['password']);
                if ($authenticated) {
                    $userRole = $this->authService->getUserRole($request['username']);
                    if ($request['role'] === $userRole) {
                        $redirect_url = $this->getRedirectUrl($request['role']);
                        $_SESSION['username'] = $request['username'];
                        return Response::success([
                            'message' => 'Login Berhasil',
                            'token' => $authenticated,
                            'redirect_url' => $redirect_url,
                        ]);
                    } else {
                        return Response::error('Role tidak cocok');
                    }
                } else {
                    return Response::error('Incorrect Password');
                }
            } catch (\Exception $e) {
                return Response::error($e->getMessage());
            }
        } else {
            return Response::msg(405, 'Metode HTTP tidak valid.');
        }
    }

    private function getRedirectUrl($role)
    {
        switch ($role) {
            case 'admin':
                return route('dashboard');
            case 'direktur':
                return route('dashboard');
            case 'karyawan':
                return route('dashboard');
            default:
                return route('home');
        }
    }

    public function validatePassword($password)
    {
        $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/';
        return preg_match($pattern, $password);
    }

    public function resetPassword($username, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updated = $this->karyawanRepository->updatePassword($username, $hashedPassword);
        if ($updated) {
            return Response::success(['message' => 'Password berhasil diubah.']);
        } else {
            return Response::error('Gagal mengubah password.');
        }
    }
    public function getNewPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';

            if ($contentType === 'application/json') {
                $content = trim(file_get_contents('php://input'));
                $decoded = json_decode($content, true);

                $username = $decoded['username'] ?? null;
                $newPassword = $decoded['password'] ?? null;
            } else {
                $username = $_POST['username'] ?? null;
                $newPassword = $_POST['password'] ?? null;
            }

            $user = $this->karyawanRepository->findUser($username);

            if (!$user) {
                return Response::error('Username tidak ditemukan.');
            }

            $passwordValidated = $this->validatePassword($newPassword);

            if (!$passwordValidated) {
                return Response::error('Password harus terdiri dari 8 karakter, setidaknya 1 huruf besar, 1 huruf kecil, 1 angka, 1 karakter karakter spesial.');
            }     
            $this->resetPassword($username, $newPassword);
        }
    }
}
