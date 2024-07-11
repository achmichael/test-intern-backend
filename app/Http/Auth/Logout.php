<?php

namespace App\Http\Controllers\Auth;

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Http\Controllers\Controller;
use Config\Response;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // menghapus semua data sesi
        session_unset();
        session_destroy(); 

        // Menghapus cookie laravel_session jika ada
        if (isset($_COOKIE['laravel_session'])) {
            //melakukan set cookie laravel session agar kadaluwarsa
            setcookie('laravel_session', '', time() - 3600, '/');
        }

        // Mengembalikan respon JSON dengan pesan 'Logout successful'
        return Response::success(['message' => 'Logout successful', 'redirect' => route('home')]);
    }
}
