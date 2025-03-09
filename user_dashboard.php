<?php
include 'db.php';

// Fetch user-specific application
$user_id = $_GET['user_id'];
$query = "SELECT * FROM statuss WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$application = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
        }

        p {
            font-size: 18px;
        }
    </style>
</head>
<body>
    <h1>User Dashboard</h1>
    <?php if ($application): ?>
        <p><strong>Name:</strong> <?= $application['name']; ?></p>
        <p><strong>Email:</strong> <?= $application['email']; ?></p>
        <p><strong>Status:</strong> <?= $application['status']; ?></p>
    <?php else: ?>
        <p>No application found.</p>
    <?php endif; ?>
</body>
</html>
