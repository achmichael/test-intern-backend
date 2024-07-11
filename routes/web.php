<?php
require_once __DIR__ .'/../app/Http/Auth/Register.php';
require_once __DIR__ .'/../app/Http/Auth/Login.php';
require_once __DIR__ .'/../app/Http/Controllers/Submission.php';
require_once __DIR__ .'/../app/Http/Auth/Logout.php';
require_once __DIR__ .'/../app/Http/Controllers/Dashboard.php';
require_once __DIR__ .'/../app/Http/Controllers/AdminApproval.php';
require_once __DIR__ .'/../app/Http/Controllers/Approval.php';
require_once __DIR__ .'/../app/Http/Controllers/ApprovalReport.php';
require_once __DIR__ .'/../app/Http/Controllers/DirekturApproval.php';

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ApprovalReportController;
use App\Http\Controllers\AdminApprovalController;
use App\Http\Controllers\DirectorApprovalController;

// Entry Point
Route::get('/', function () { return view('welcome', ['title' => 'Home Page']); })->name('home');

// Register
Route::get('/auth/register', [RegisterController::class, 'showRegistrasiForm']);
Route::post('/auth/register', [RegisterController::class, 'register'])->name('register')->middleware('web');

// Login
Route::get('/auth/login', [LoginController::class, 'showLoginForm']);
Route::post('/auth/login', [LoginController::class, 'login'])->name('login')->middleware('web');

// Dashboard
Route::get('/dashboard', [SubmissionController::class, 'getAllPurchaseRequests'])->name('dashboard');

// Submission
Route::middleware(['web'])->group(function () {
    Route::get('/submission', [SubmissionController::class, 'showSubmissionForm']);
    Route::post('/submission', [SubmissionController::class, 'addSubmission'])->name('submission');
});

// Approvals
Route::get('/approval/admin', [ApprovalController::class, 'getPendingRequest'])->name('approvals');
Route::get('/approval/direktur', [DirectorApprovalController::class, 'getAdminApproval']);
Route::post('/approval/admin', [AdminApprovalController::class, 'addAdminApproval'])->name('admin.approvals');
Route::post('/approval/director', [DirectorApprovalController::class, 'addDirectorApproval'])->name('director.approvals');

// Reports
Route::get('/reports', function() { return view('reports', ['title' => 'Report']);})->name('reports');
Route::post('/reports/generate', [ApprovalReportController::class, 'generateReport'])->name('reports.generate');
Route::post('/reports/export/excel', [ApprovalReportController::class, 'exportExcel'])->name('reports.export.excel');

//Forgot Password
Route::post('/forgot-password',[LoginController::class, 'getNewPassword'])->name('forgot-password');

// Logout
Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');
