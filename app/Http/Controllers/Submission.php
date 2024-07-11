<?php

namespace App\Http\Controllers;

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../Repositories/SubmissionRepository.php';
require_once __DIR__ . '/../Auth/AuthService.php';

use App\Repositories\SubmissionRepository;
use App\Services\AuthService;
use Config\Response;

class SubmissionController extends Controller
{

    private $submissionRepository;
    private $authService;

    public function __construct(SubmissionRepository $submissionRepository, AuthService $authService)
    {
        $this->submissionRepository = $submissionRepository;
        $this->authService = $authService;
    }

    public function showSubmissionForm()
    {
        return view('submission', ['title' => 'Submission', 'username' => $_SESSION['username']]);
    }
    public function addSubmission()
    {
        $jwt = null;
        if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
            $authHeader = trim($_SERVER['HTTP_AUTHORIZATION']);
            if (strpos($authHeader, 'Bearer') === 0) {
                $jwt = trim(substr($authHeader, 7));
            }
        } else {
            return response()->json([
               'message' => 'Unauthorized' 
            ], 400);
        }
        if ($jwt) {
            try {
                $tokenData = $this->authService->validateToken($jwt);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage(),
                ], 401);
            }

            if (!$tokenData) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Token',
                ], 401);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';

                if ($contentType === 'application/json') {
                    //mengambil data dari request body json
                    $content = trim(file_get_contents('php://input'));
                    $decoded = json_decode($content, true);

                    $equipmentType = $decoded['equipment_type'] ?? null;
                    $quantity = $decoded['quantity'] ?? null;
                    $reason = $decoded['reason'] ?? null;
                } else {
                    $equipmentType = $_POST['equipment_type'] ?? null;
                    $quantity = $_POST['quantity'] ?? null;
                    $reason = $_POST['reason'] ?? null;
                }

                if ($equipmentType && $quantity && $reason) {
                    try {
                        $karyawan_id = $tokenData->id;
                        $result = $this->submissionRepository->addSubmission($karyawan_id, $equipmentType, $quantity, $reason, 'pending');

                        if ($result) {
                            return response()->json([
                                'status' => true,
                                'data' => [
                                    'equipmentType' => $equipmentType,
                                    'quantity' => $quantity,
                                    'reason' => $reason,
                                ],
                                'message' => 'Submission Successfully'
                            ]);
                        } else {
                            return response()->json([
                                'status' => false,
                                'message' => 'Error adding Submission',
                            ], 400);
                        }
                    } catch (\Exception $e) {
                        return response()->json([
                            'status' => false,
                            'message' => $e->getMessage(),
                        ], 500);
                    }
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data tidak lengkap',
                    ], 400);
                }
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Header Autorisasi tidak ditemukan atau formatnya salah',
        ], 401);
    }


    public function getAllPurchaseRequests() {
        try{
            $requests = $this->submissionRepository->getAllPurchase();
            return view('dashboard', ['requests' => $requests, 'title' => 'Dashboard']);
            // return $requests;
        }catch(\Exception $e){
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
