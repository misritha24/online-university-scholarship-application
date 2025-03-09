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

// Check if registration form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    // Registration logic
    $new_user = $_POST['new_username'];
    $new_pass = $_POST['new_password'];
    
    $branch = $_POST['branch'];

    // Prevent SQL injection
    $new_user = $conn->real_escape_string($new_user);
    $new_pass = $conn->real_escape_string($new_pass);
    
    $branch = $conn->real_escape_string($branch);

    // Check if username already exists
    $sql_check = "SELECT * FROM studentss WHERE username = '$new_user'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        // Username already exists
        $register_message = "Username already exists. Please choose a different one.";
    } else {
        // Insert new user into the database
        $sql_insert = "INSERT INTO studentss (username, password, branch) VALUES ('$new_user', '$new_pass', '$branch')";
        if ($conn->query($sql_insert) === TRUE) {
            $register_message = "Registration successful. You can now log in.";
        } else {
            $register_message = "Error: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('bg_reg.png') no-repeat center center/cover;
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
            box-shadow: 0 0 20px rgba(42, 28, 236, 0.8); /* Light orange glow */
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
        input[type="text"], input[type="password"], input[type="email"], select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            transition: box-shadow 0.3s;
        }
        input[type="text"]:focus, input[type="password"]:focus, input[type="email"]:focus, select:focus {
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.8);
            border-color: #007bff;
        }
        input[type="submit"] {
            background: linear-gradient(90deg, rgb(76, 84, 175), rgb(0, 204, 255));
            background-size: 200% 100%;
            color: white;
            border: none;
            padding: 14px;
            cursor: pointer;
            width: 100%;
            border-radius: 6px;
            font-size: 16px;
            transition: background-position 0.5s;
        }
        input[type="submit"]:hover {
            background-position: -100% 0;
        }
        .message {
            color: red;
            text-align: center;
            font-size: 14px;
        }
        .login-link {
            margin-top: 10px;
            text-align: center;
            font-size: 14px;
        }
        .login-link a {
            color: #007bff;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <?php
        if (isset($register_message)) {
            echo "<p class='message'>$register_message</p>";
        }
        ?>
        <form method="post" action="">
            <label for="new_username">Username:</label><br>
            <input type="text" id="new_username" name="new_username" required><br><br>
            
            <label for="new_password">Password:</label><br>
            <input type="password" id="new_password" name="new_password" required><br><br>
    
            <label for="branch">Branch:</label><br>
            <input type="text" id="branch" name="branch" required><br><br>
            
            <input type="submit" name="register" value="Register">
        </form>
        <div class="login-link">
            Already registered? <a href="userlogin.php">Log in</a>
        </div>
    </div>
</body>
</html>
