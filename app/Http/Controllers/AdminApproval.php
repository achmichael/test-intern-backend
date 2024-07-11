<?php

namespace App\Http\Controllers;

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../Repositories/ApprovalRepository.php';
require_once __DIR__ . '/../../Repositories/SubmissionRepository.php';
require_once __DIR__ . '/../Auth/AuthService.php';

use App\Http\Controllers\Controller;
use App\Repositories\ApprovalRepository;
use App\Services\AuthService;
use Config\Response;
use App\Repositories\SubmissionRepository;
use Illuminate\Http\Request;

class AdminApprovalController extends Controller
{
    protected $approvalRepository;
    protected $authService;
    protected $submissionRepository;

    public function __construct(ApprovalRepository $approvalRepository)
    {
        $this->approvalRepository = $approvalRepository;
        $this->authService = new AuthService();
        $this->submissionRepository = new SubmissionRepository();
    }

    public function addAdminApproval(Request $request)
    {
        $jwt = $this->getBearerToken();
        if (!$jwt) {
            return Response::error('Unauthorized', 401);
        }

        try{
            $tokenData = $this->validateToken($jwt);

            if (!$tokenData){
                return Response::error('Invalid token');
            }
            
        }catch(\Exception $e){
            return Response::error($e->getMessage());
        }
        

        $purchaseRequestId = $this->getPurchaseRequestId($request);

        if (!$purchaseRequestId) {
            return Response::error('Missing Purchase ID', 400);
        }

        $userId = $tokenData->id;
        $role = $this->authService->getUserRoleById($userId);

        if ($role !== 'admin') {
            return Response::error('You are not authorized to perform this action');
        }

        try {
            $result = $this->approvalRepository->addApproval($purchaseRequestId, $userId, $role, 'approved_by_admin');
            $updated = $this->submissionRepository->updateStatus($purchaseRequestId, 'approved_by_admin');
            if ($result && $updated) {
                $now = date('d-m-y');
                return Response::success([
                    'role' => $role,
                    'message' => 'Approval added successfully',
                    'addedOnDate' => $now,
                ]);
            }
        } catch (\Exception $e) {
            return Response::error($e->getMessage());
        }
    }

    private function getBearerToken()
    {
        $jwt = null;
        if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
            $authHeader = trim($_SERVER['HTTP_AUTHORIZATION']);
            if (strpos($authHeader, 'Bearer') === 0) {
                $jwt = trim(substr($authHeader, 7));
            } else {
                $jwt = $authHeader;
            }
        }
        return $jwt;
    }

    private function validateToken($jwt)
    {
        try {
            return $this->authService->validateToken($jwt);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    private function getPurchaseRequestId(Request $request)
    {
        $contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';

        if ($contentType === 'application/json') {
            $content = trim(file_get_contents('php://input'));
            $decoded = json_decode($content, true);
            return $decoded['purchase_request_id'] ?? null;
        } else {
            return $request->input('purchase_request_id');
        }
    }
}
