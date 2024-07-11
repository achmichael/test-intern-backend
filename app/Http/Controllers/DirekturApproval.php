<?php

namespace App\Http\Controllers;

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../Repositories/ApprovalRepository.php';
require_once __DIR__ . '/../../Repositories/SecondApprovalRepository.php';
require_once __DIR__ .'/../../Repositories/SubmissionRepository.php';
require_once __DIR__ . '/../Auth/AuthService.php';

use App\Http\Controllers\Controller;
use App\Repositories\ApprovalRepository;
use App\Repositories\SecondApprovalRepository;
use App\Services\AuthService;
use App\Repositories\SubmissionRepository;
use Config\Response;
use Illuminate\Http\Request;

class DirectorApprovalController extends Controller
{
    protected $approvalRepository;
    protected $authService;
    protected $secondApprovalRepository;
    protected $submissionRepository;

    public function __construct()
    {
        $this->approvalRepository = new ApprovalRepository();
        $this->authService = new AuthService();
        $this->secondApprovalRepository = new SecondApprovalRepository();
        $this->submissionRepository = new SubmissionRepository();
    }

    public function addDirectorApproval(Request $request)
    {
        $jwt = $this->getBearerToken();
        if (!$jwt) {
            return Response::error('Unauthorized', 401);
        }

        try {
            $tokenData = $this->validateToken($jwt);

            if (!$tokenData) {
                return Response::error('Invalid token');
            }
        } catch (\Exception $e) {
            return Response::error($e->getMessage());
        }

        $purchaseRequestId = $this->getPurchaseRequestId($request);

        if (!$purchaseRequestId) {
            return Response::error('Missing Purchase ID', 400);
        }

        $userId = $tokenData->id;
        $role = $this->authService->getUserRoleById($userId);

        if ($role !== 'direktur') {
            return Response::error('You are not authorized to perform this action');
        }

        try {
            $data = $this->approvalRepository->getApproval($purchaseRequestId);

            if (!$data || !isset($data['id'])) {
                return Response::error('Permintaan Belum Disetujui Admin', 404);
            }

            $approvalId = $data['id'];
            $result = $this->secondApprovalRepository->addSecondApproval($approvalId, $userId, $role, 'approved_by_direktur');
            $updated_status = $this->approvalRepository->updateStatus($approvalId, 'approved_by_direktur');
            $updatedStatus = $this->submissionRepository->updateStatus($purchaseRequestId, 'approved_by_direktur');
            if ($result && $updated_status && $updatedStatus) {
                $now = date('d-m-y');
                return Response::success([
                    'role' => $role,
                    'message' => 'Second Approval added successfully',
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

    public function getAdminApproval(){
       try{
        $result = $this->secondApprovalRepository->getApprovedByAdmin();
        if($result){
            return view('approvalDirektur', ['requests' => $result]);
        }else{
            //tetap dikembalikan sebagai array kosong agar pada view bisa melakukan pengecekan dan menampilkan output 'no pending request for directur found' 
            return view('approvalDirektur', ['requests' => $result]);            
        }
       }catch(\Exception $e){
        return Response::error($e->getMessage());
       }         
    }
}
?>
