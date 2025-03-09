<?php
include 'db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize the donation amount input
    $donation = floatval($_POST['donation_amount'] ?? 0);
    
    // Get the application ID from the URL (GET parameter)
    $applicationId = intval($_GET['id'] ?? 0);
    
    if ($applicationId <= 0) {
        $message = "Invalid application id.";
    } elseif ($donation <= 0) {
        $message = "Donation amount must be greater than zero.";
    } else {
        // Retrieve the current required amount for the specified application
        $query = "SELECT amount_required FROM need_based_scholarships WHERE id = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            die("Preparation failed: " . $conn->error);
        }
        $stmt->bind_param("i", $applicationId);
        $stmt->execute();
        $stmt->bind_result($amountRequired);
        
        if ($stmt->fetch()) {
            $stmt->close();
            
            // Simulate a successful payment (scanner process)
            if ($donation >= $amountRequired) {
                // Donation fully funds the required amount; delete the record.
                $queryDelete = "DELETE FROM need_based_scholarships WHERE id = ?";
                $stmtDel = $conn->prepare($queryDelete);
                if (!$stmtDel) {
                    die("Delete preparation failed: " . $conn->error);
                }
                $stmtDel->bind_param("i", $applicationId);
                $stmtDel->execute();
                $stmtDel->close();
                $message = "Thank you for your full donation! The scholarship is now fully funded.";
            } else {
                // Partial donation: update the remaining required amount.
                $newAmount = $amountRequired - $donation;
                $queryUpdate = "UPDATE need_based_scholarships SET amount_required = ? WHERE id = ?";
                $stmtUp = $conn->prepare($queryUpdate);
                if (!$stmtUp) {
                    die("Update preparation failed: " . $conn->error);
                }
                $stmtUp->bind_param("di", $newAmount, $applicationId);
                $stmtUp->execute();
                $stmtUp->close();
                $message = "Thank you for your donation! The remaining amount required is: " . $newAmount;
            }
        } else {
            $stmt->close();
            $message = "Application not found for ID: " . $applicationId;
        }
    }
    $conn->close();
} else {
    $message = "Invalid request.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Donation Status</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }
    .message {
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
      text-align: center;
    }
    a {
      text-decoration: none;
      color: #007BFF;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="message">
    <p><?php echo $message; ?></p>
    <a href="donate.php">Return to Donation Page</a>
  </div>
</body>
</html>
