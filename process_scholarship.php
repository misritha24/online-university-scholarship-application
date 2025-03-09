<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scholarship_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collecting form data
$userid = $_POST['userid'];
$password = $_POST['password'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$date_of_birth = $_POST['date_of_birth'];
$nationality = $_POST['nationality'];
$email = $_POST['email'];
$phone_number = $_POST['phone_number'];
$highest_qualification = $_POST['highest_qualification'];
$previous_institution = $_POST['previous_institution'];
$grade_or_percentage = $_POST['grade_or_percentage'];
$language_proficiency = $_POST['language_proficiency'];
$proficiency_score = $_POST['proficiency_score'];
$scholarship_type = $_POST['scholarship_type'];
$personal_statement = $_POST['personal_statement'];

// File uploads
$target_dir = "uploads/";
$transcript_upload = $target_dir . basename($_FILES["transcript_upload"]["name"]);
$language_certificate = $target_dir . basename($_FILES["language_certificate"]["name"]);
$recommendation_letter = $target_dir . basename($_FILES["recommendation_letter"]["name"]);

move_uploaded_file($_FILES["transcript_upload"]["tmp_name"], $transcript_upload);
move_uploaded_file($_FILES["language_certificate"]["tmp_name"], $language_certificate);
move_uploaded_file($_FILES["recommendation_letter"]["tmp_name"], $recommendation_letter);

// Insert into database
$sql = "INSERT INTO international_scholarships 
    (userid, password, first_name, last_name, date_of_birth, nationality, email, phone_number, highest_qualification, previous_institution, grade_or_percentage, language_proficiency, proficiency_score, scholarship_type, personal_statement, transcript_upload, language_certificate, recommendation_letter) 
    VALUES ('$userid', '$password', '$first_name', '$last_name', '$date_of_birth', '$nationality', '$email', '$phone_number', '$highest_qualification', '$previous_institution', '$grade_or_percentage', '$language_proficiency', '$proficiency_score', '$scholarship_type', '$personal_statement', '$transcript_upload', '$language_certificate', '$recommendation_letter')";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background: url('fin.png') no-repeat center center/cover;
            background-color: #f4f4f9;
            padding: 300px;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 255, 0, 0.7);
            max-width: 600px;
            margin: auto;
            animation: glow 1.5s infinite alternate ease-in-out;
        }
        
        /* Glow effect animation */
        @keyframes glow {
            0% {
                box-shadow: 0 0 15px rgba(0, 255, 0, 0.7);
            }
            100% {
                box-shadow: 0 0 25px rgba(0, 255, 0, 1);
            }
        }

        .message {
            font-size: 18px;
            color: green;
            margin-bottom: 20px;
        }
        .error {
            font-size: 18px;
            color: red;
            margin-bottom: 20px;
        }
        .back-button {
            background-color:rgb(209, 23, 215);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            transition: background 0.3s ease-in-out;
        }
        .back-button:hover {
            background-color:rgb(218, 19, 142);
        }
    </style>
</head>
<body>

    <div class="container">
        <?php
        if ($conn->query($sql) === TRUE) {
            echo "<p class='message'>Application submitted successfully!</p>";
        } else {
            echo "<p class='error'>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
        $conn->close();
        ?>
        <a href="scholarshiptype.html" class="back-button">Back to Home</a>
    </div>

</body>
</html>
