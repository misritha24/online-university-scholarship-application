<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $conn = new mysqli("localhost", "root", "", "scholarship_db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize input
    $name = htmlspecialchars($_POST["name"]);
    $reg_no = htmlspecialchars($_POST["registration_number"]);
    $dob = htmlspecialchars($_POST["dob"]);
    $university = trim(strtolower($_POST["university_name"]));
    $course = htmlspecialchars($_POST["course"]);
    $address = htmlspecialchars($_POST["address"]);
    
    $father_income = floatval(str_replace(",", "", $_POST["father_income"])); // Convert to number
    $father_occupation = htmlspecialchars($_POST["father_occupation"]);

    // Validation
    $errors = [];

    // Check for duplicate registration
    $checkQuery = $conn->prepare("SELECT * FROM min_form WHERE name = ? AND reg_no = ?");
    $checkQuery->bind_param("ss", $name, $reg_no);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows > 0) {
        echo "<div class='container error-container'>";
        echo "<p class='already-submitted'>You have already submitted the form!</p>";
        echo "<a class='btn glow' href='min_dashboard.php'>Back to Home</a>";
        echo "</div>";
        exit();
    }

    // Validate university name
    if ($university !== "vignan university") {
        $errors[] = "Only 'Vignan University' is allowed.";
    }

    // Validate father's income
    if ($father_income > 800000) {
        $errors[] = "Father's income must not exceed ₹8,00,000.";
    }

    // Display validation errors
    if (!empty($errors)) {
        echo "<div class='container error-container'>";
        foreach ($errors as $error) {
            echo "<p class='error'>$error</p>";
        }
        echo "<a class='btn glow' href='javascript:history.back()'>Go Back</a></div>";
        exit();
    }

    // File upload handling
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    function uploadFile($fileInputName, $required = true) {
        global $uploadDir;
        if (!isset($_FILES[$fileInputName]) || $_FILES[$fileInputName]['error'] != UPLOAD_ERR_OK) {
            return $required ? "Error: $fileInputName is required!" : null;
        }

        $fileTmpPath = $_FILES[$fileInputName]['tmp_name'];
        $fileName = basename($_FILES[$fileInputName]['name']);
        $destination = $uploadDir . time() . "_" . $fileName;

        if (move_uploaded_file($fileTmpPath, $destination)) {
            return $destination;
        } else {
            return "Error uploading $fileInputName!";
        }
    }

    $income_cert = uploadFile("income_certificate");
    $father_pan = uploadFile("father_pan_card");
    $aadhar = uploadFile("aadhar_card");
    $student_pan = uploadFile("student_pan_card", false);
    $mother_pan = uploadFile("mother_pan_card", false);

    // Handle errors in file upload
    $fileErrors = array_filter([$income_cert, $father_pan, $aadhar, $student_pan, $mother_pan], function($file) {
        return strpos($file, "Error") !== false;
    });

    if (!empty($fileErrors)) {
        echo "<div class='container error-container'>";
        foreach ($fileErrors as $error) {
            echo "<p class='error'>$error</p>";
        }
        echo "<a class='btn glow' href='javascript:history.back()'>Go Back</a></div>";
        exit();
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO min_form 
        (name, reg_no, dob, university, course, address, father_income, father_occupation, 
        income_cert, father_pan, aadhar, student_pan, mother_pan) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("sssssssssssss", 
        $name, $reg_no, $dob, $_POST["university_name"], $course, $address,  
        $father_income, $father_occupation, 
        $income_cert, $father_pan, $aadhar, $student_pan, $mother_pan
    );

    echo "<div class='container'>";
    if ($stmt->execute()) {
        echo "<p class='success'>Application submitted successfully!</p>";
    } else {
        echo "<p class='error'>Error submitting application: " . $conn->error . "</p>";
    }
    echo "<a class='btn glow' href='min_dashboard.php'>Back to Home</a></div>";

    $stmt->close();
    $conn->close();
}
?>

<style>
/* Background and Layout */
body {
    background: url('form.png') no-repeat center center fixed;
    background-size: cover;
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

/* Container for Messages */
.container {
    width: 50%;
    padding: 20px;
    text-align: center;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
}

/* Success and Error Messages */
.success {
    color: green;
    font-size: 20px;
    font-weight: bold;
}

.error {
    color: red;
    font-size: 18px;
    font-weight: bold;
}

/* Already Submitted Message */
.already-submitted {
    font-size: 22px;
    font-weight: bold;
    color: #d9534f;
    background: rgba(255, 0, 0, 0.1);
    padding: 15px;
    border-radius: 5px;
    border-left: 5px solid red;
}

/* Error Container */
.error-container {
    background: rgba(255, 0, 0, 0.1);
    padding: 20px;
    border-left: 5px solid red;
    border-radius: 5px;
}

/* Button Styling */
.btn {
    display: inline-block;
    padding: 12px 24px;
    font-size: 18px;
    color: white;
    background: #007BFF;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    margin-top: 20px;
    transition: background 0.3s ease;
}

/* Glowing Button Animation */
.btn.glow {
    animation: glowing 1.5s infinite alternate;
}

@keyframes glowing {
    0% { box-shadow: 0 0 5px #007BFF; }
    100% { box-shadow: 0 0 20px #007BFF; }
}

.btn:hover {
    background: #0056b3;
}
</style>
