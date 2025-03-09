<?php
// Start the session (if needed)
session_start();

// Database connection (replace with your actual database connection)
$host = 'localhost';
$dbname = 'scholarship_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data safely using null coalescing operator
    $user_id = $_POST['user_id'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $full_name = $_POST['name'] ?? ''; // Corrected field name
    $branch = $_POST['branch'] ?? '';
    $year = $_POST['year'] ?? '';
    $mobile_number = $_POST['mobile_number'] ?? '';
    $account_number = $_POST['bank_account_number'] ?? '';
    $bank_name = $_POST['bank_name'] ?? '';
    $bank_branch = $_POST['bank_branch'] ?? '';
    $account_holder_name = $_POST['account_holder_name'] ?? '';
    $ifsc_code = $_POST['ifsc_code'] ?? '';
    $personal_statement = $_POST['personal_statement'] ?? '';

    // Check if password and confirm password match
    if ($password !== $confirm_password) {
        echo "Password and Confirm Password do not match.";
        exit;
    }

    // Handle file uploads
    $uploads_dir = 'uploads/';
    if (!is_dir($uploads_dir)) {
        mkdir($uploads_dir, 0777, true);
    }

    $fees_receipt_path = '';
    $admission_form_path = '';
    $bank_xerox_path = '';
    $transcript_path = '';

    // File upload function
    function handleFileUpload($fileKey, $uploads_dir) {
        if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === 0) {
            $file_type = mime_content_type($_FILES[$fileKey]['tmp_name']);
            $allowed_types = ['application/pdf'];
            if (in_array($file_type, $allowed_types)) {
                $file_name = basename($_FILES[$fileKey]['name']);
                $file_path = $uploads_dir . $file_name;
                if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $file_path)) {
                    return $file_path;
                } else {
                    echo "Error uploading $fileKey.";
                    exit;
                }
            } else {
                echo "Only PDF files are allowed for $fileKey.";
                exit;
            }
        }
        return '';
    }

    $fees_receipt_path = handleFileUpload('fees_receipt', $uploads_dir);
    $admission_form_path = handleFileUpload('admission_form', $uploads_dir);
    $bank_xerox_path = handleFileUpload('bank_xerox', $uploads_dir);
    $transcript_path = handleFileUpload('transcript', $uploads_dir);

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert data into the database
    $sql = "INSERT INTO applications (
                user_id, password, full_name, branch, year, 
                mobile_number, account_number, bank_name, bank_branch, 
                account_holder_name, ifsc_code, personal_statement, 
                fees_receipt_path, admission_form_path, bank_xerox_path, transcript_path, 
                status, created_at
            ) VALUES (
                :user_id, :password, :full_name, :branch, :year, 
                :mobile_number, :account_number, :bank_name, :bank_branch, 
                :account_holder_name, :ifsc_code, :personal_statement, 
                :fees_receipt_path, :admission_form_path, :bank_xerox_path, :transcript_path, 
                'Pending', NOW()
            )";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':password', $hashed_password); // Use the hashed password
    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':branch', $branch);
    $stmt->bindParam(':year', $year);
    $stmt->bindParam(':mobile_number', $mobile_number);
    $stmt->bindParam(':account_number', $account_number);
    $stmt->bindParam(':bank_name', $bank_name);
    $stmt->bindParam(':bank_branch', $bank_branch);
    $stmt->bindParam(':account_holder_name', $account_holder_name);
    $stmt->bindParam(':ifsc_code', $ifsc_code);
    $stmt->bindParam(':personal_statement', $personal_statement);
    $stmt->bindParam(':fees_receipt_path', $fees_receipt_path);
    $stmt->bindParam(':admission_form_path', $admission_form_path);
    $stmt->bindParam(':bank_xerox_path', $bank_xerox_path);
    $stmt->bindParam(':transcript_path', $transcript_path);

    if ($stmt->execute()) {
        echo "Application submitted successfully.";
    } else {
        echo "Error saving application data.";
    }
}
?>
