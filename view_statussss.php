<?php
session_start();
include 'db.php'; // Ensure this file correctly establishes the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reg_no = trim($_POST['reg_no']);
    

    // Check if the database connection is successful
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Prepare the SQL query
    $sql = "SELECT * FROM min_form WHERE reg_no = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error preparing the query: " . $conn->error); // Debugging step
    }

    $stmt->bind_param("s", $reg_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        echo "<h1>Application Details</h1>";
        echo "<p><strong>Status:</strong> " . htmlspecialchars($row['status']) . "</p>";
        echo "<p><strong>Full Name:</strong> " . htmlspecialchars($row['name']) .  "</p>";
    } else {
        echo "<p style='color:red;'>Invalid registration number. Please try again.</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
