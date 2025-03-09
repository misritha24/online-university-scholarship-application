<?php
include 'db_config.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $stmt = $pdo->prepare("SELECT * FROM applications WHERE user_id = ?");
        $stmt->execute([$user['id']]);
        $application = $stmt->fetch();

        if ($application) {
            echo "Status: " . $application['status'];
        } else {
            echo "No application found.";
        }
    } else {
        echo "Invalid credentials.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Check Status</title>
</head>
<body>
    <h1>Check Application Status</h1>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <button type="submit">Check Status</button>
    </form>
</body>
</html>
