<?php
session_start();
include 'db.php'; // Ensure this file correctly establishes the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = trim($_POST['userid']);
    $password = trim($_POST['password']);

    // Check if the database connection is successful
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Prepare the SQL query
    $sql = "SELECT * FROM international_scholarships WHERE userid = ? AND password = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error preparing the query: " . $conn->error); // Debugging step
    }

    $stmt->bind_param("ss", $userid, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        echo "<h1>Application Details</h1>";
        echo "<p><strong>Status:</strong> " . htmlspecialchars($row['status']) . "</p>";
        echo "<p><strong>Full Name:</strong> " . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']) . "</p>";
    } else {
        echo "<p style='color:red;'>Invalid UserID or Password. Please try again.</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
