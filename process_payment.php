<?php
include 'db.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id']) && isset($_POST['amount'])) {
    $id = intval($_POST['id']);
    $donatedAmount = floatval($_POST['amount']);

    // Fetch current amount required
    $stmt = $conn->prepare("SELECT amount_required FROM need_based_scholarships WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Error: No record found.");
    }

    $row = $result->fetch_assoc();
    $amountRequired = floatval($row['amount_required']);

    // Calculate remaining amount
    $remainingAmount = $amountRequired - $donatedAmount;

    // Ensure the amount is not negative
    if ($remainingAmount < 0) {
        die("Error: Donation amount exceeds required amount.");
    }

    // Update the new amount required in the database
    $stmt = $conn->prepare("UPDATE need_based_scholarships SET amount_required = ? WHERE id = ?");
    $stmt->bind_param("di", $remainingAmount, $id);
    
    if ($stmt->execute()) {
        echo "<h1>Payment Successful!</h1>";
        echo "<p>You have successfully donated ₹$donatedAmount.</p>";
        echo "<p>Remaining amount required: ₹$remainingAmount</p>";
        echo "<a href='donate.php'>Return to Donation Page</a>";
    } else {
        echo "<p style='color:red;'>Error updating payment details.</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p style='color:red;'>Invalid request.</p>";
}
?>
