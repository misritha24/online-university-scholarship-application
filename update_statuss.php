<?php
include 'db.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"], $_POST["status"])) {
    $id = intval($_POST["id"]);
    $status = $_POST["status"];

    // Update the status in the database
    $updateQuery = "UPDATE need_based_scholarships SET status=? WHERE id=?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $status, $id);
    $success = $stmt->execute();
    
    if ($success) {
        // Fetch updated counts
        $approvedCount = $conn->query("SELECT COUNT(*) as approved FROM need_based_scholarships WHERE status='Approved'")->fetch_assoc()['approved'];
        $pendingCount = $conn->query("SELECT COUNT(*) as pending FROM need_based_scholarships WHERE status='Pending'")->fetch_assoc()['pending'];
        $rejectedCount = $conn->query("SELECT COUNT(*) as rejected FROM need_based_scholarships WHERE status='Rejected'")->fetch_assoc()['rejected'];

        echo json_encode([
            "success" => true,
            "approved" => $approvedCount,
            "pending" => $pendingCount,
            "rejected" => $rejectedCount
        ]);
    } else {
        echo json_encode(["success" => false]);
    }
    
    $stmt->close();
}

$conn->close();
?>
