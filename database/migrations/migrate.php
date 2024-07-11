<?php
require_once __DIR__ . '/../Connection.php';
require_once __DIR__ . '/karyawan.php';
require_once __DIR__ . '/purchaseRequest.php';
require_once __DIR__ . '/approvals.php';
require_once __DIR__ . '/reports.php';
require_once __DIR__ . '/secondApproval.php';

use Database\Connection;
use Database\Migration\Karyawan;
use Database\Migration\PurchaseRequest;
use Database\Migration\Approvals;
use Database\Migration\Report;
use Database\Migration\SecondApprovals;

$migrationKaryawan = new Karyawan();
$migrationKaryawan->migrate(); 

$migrationPurchaseRequest = new PurchaseRequest();
$migrationPurchaseRequest->migrate(); 

$migrationApprovals = new Approvals();
$migrationApprovals->migrate();

$migrationSecondApprovals = new SecondApprovals();
$migrationSecondApprovals->migrate();

$migrationReport = new Report();
$migrationReport->migrate(); 


?>