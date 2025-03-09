<?php
session_start();

// Database connection
$servername = "localhost"; // Replace with your server name
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "scholarship_portal"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    $admin_username = $_POST['username'];
    $admin_password = $_POST['password'];

    // Prevent SQL injection
    $admin_username = $conn->real_escape_string($admin_username);
    $admin_password = $conn->real_escape_string($admin_password);

    // Query to check if admin exists
    $sql = "SELECT * FROM admins WHERE username = '$admin_username' AND password = '$admin_password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Successful login
        $_SESSION['admin_username'] = $admin_username;
        header("Location: http://localhost/zoo/admindashboard.php"); // Redirect to admin dashboard
        exit();
    } else {
        // Incorrect credentials
        $error_message = "Incorrect Username or Password.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('bg_adm.png') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(231, 9, 50, 0.8); /* Light orange glow */
            width: 380px; /* Increased width */
            transition: box-shadow 0.3s ease-in-out;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-size: 14px;
            color: #555;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            width: 100%;
            border-radius: 4px;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .message {
            color: red;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Admin Login</h2>
        <?php
        if (isset($error_message)) {
            echo "<p class='message'>$error_message</p>";
        }
        ?>
        <form method="post" action="">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br><br>
            
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            
            <input type="submit" value="Login">
        </form>
    </div>

</body>
</html>
