<?php 
session_start();

// Database connection
$servername = "localhost"; // Replace with your server name
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "vignan_registration"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Prevent SQL injection
    $user = $conn->real_escape_string($user);
    $pass = $conn->real_escape_string($pass);

    // Query to check if user exists
    $sql = "SELECT * FROM studentss WHERE username = '$user' AND password = '$pass'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Successful login
        $_SESSION['username'] = $user;
        header("Location: viewscholarship.html"); // Redirect to home page
        exit();
    } else {
        // Incorrect credentials
        $error_message = "Incorrect username or password.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('login.png') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        }
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Black overlay with 50% transparency */
            z-index: 0;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(42, 28, 236, 0.8); /* Light orange glow */
            width: 380px; /* Increased width */
            transition: box-shadow 0.3s ease-in-out;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
        }
        label {
            font-size: 14px;
            color: #555;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 14px 20px;
            cursor: pointer;
            width: 100%;
            border-radius: 8px;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .message {
            color: red;
            text-align: center;
            font-size: 14px;
        }
        .container .description {
            text-align: center;
            font-size: 14px;
            margin-bottom: 15px;
            color: #777;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 30px rgba(232, 13, 181, 0.96); /* Light orange glow */
            width: 380px; /* Increased width */
            transition: box-shadow 0.3s ease-in-out;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Welcome Back</h2>
        <p class="description">Please log in to access your account</p>
        <?php
        if (isset($error_message)) {
            echo "<p class='message'>$error_message</p>";
        }
        ?>
        <form method="post" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            
            <input type="submit" value="Login">
        </form>
    </div>

</body>
</html>
