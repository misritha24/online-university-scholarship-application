<?php
include 'db.php';

// Fetch count of different statuses
$totalQuery = "SELECT COUNT(*) as total FROM international_scholarships";
$approvedQuery = "SELECT COUNT(*) as approved FROM international_scholarships WHERE status='Approved'";
$pendingQuery = "SELECT COUNT(*) as pending FROM international_scholarships WHERE status='Pending'";
$rejectedQuery = "SELECT COUNT(*) as rejected FROM international_scholarships WHERE status='Rejected'";

$totalResult = $conn->query($totalQuery);
$approvedResult = $conn->query($approvedQuery);
$pendingResult = $conn->query($pendingQuery);
$rejectedResult = $conn->query($rejectedQuery);

$totalData = $totalResult->fetch_assoc();
$approvedData = $approvedResult->fetch_assoc();
$pendingData = $pendingResult->fetch_assoc();
$rejectedData = $rejectedResult->fetch_assoc();

$response = [
    "total" => $totalData['total'],
    "approved" => $approvedData['approved'],
    "pending" => $pendingData['pending'],
    "rejected" => $rejectedData['rejected']
];

echo json_encode($response);
?>
