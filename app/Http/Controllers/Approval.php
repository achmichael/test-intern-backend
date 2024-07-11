<?php

namespace App\Http\Controllers;

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../Repositories/ApprovalRepository.php';
require_once __DIR__ . '/../Auth/AuthService.php';

use App\Http\Controllers\Controller;
use App\Repositories\ApprovalRepository;

class ApprovalController extends Controller
{
    private $approvalRepository;

    public function __construct(ApprovalRepository $approvalRepository)
    {
        $this->approvalRepository = $approvalRepository;
    }

    public function getPendingRequest()
    {
        $pendingRequests = $this->approvalRepository->getPendingRequests();
        return view('approvalAdmin', ['requests' => $pendingRequests, 'title' => 'Approval Admin']);
        // return $pendingRequests;
    }
}
