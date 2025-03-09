<?php
include 'db.php'; // Include database connection

// Function to sanitize user input
function cleanInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName       = cleanInput($_POST['fullName']);
    $studentID      = cleanInput($_POST['studentID']);
    $mobileNumber   = cleanInput($_POST['mobileNumber']);
    $branch         = cleanInput($_POST['branch']);
    $year           = cleanInput($_POST['year']);
    $reason         = cleanInput($_POST['reason']);
    $description    = cleanInput($_POST['description']);
    $amount_required= cleanInput($_POST['amount_required'] ?? '');  // Field name updated here
    $bankAccNo      = cleanInput($_POST['bankAccNo'] ?? '');
    $bankName       = cleanInput($_POST['bankName'] ?? '');
    $accHolderName  = cleanInput($_POST['accHolderName'] ?? '');
    $ifsc           = cleanInput($_POST['ifsc'] ?? '');

    $targetDir = "uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true); // Create uploads directory if not exists
    }

    // Handle multiple file uploads for supporting documents
    $supportDocs = [];
    $originalNames = [];
    if (!empty($_FILES['supportDocs']['name'][0])) {
        foreach ($_FILES['supportDocs']['name'] as $key => $name) {
            $originalFileName = basename($name); // Keep original name
            $storedFileName = time() . "_" . $originalFileName; // Add timestamp
            $targetFilePath = $targetDir . $storedFileName;

            if (move_uploaded_file($_FILES['supportDocs']['tmp_name'][$key], $targetFilePath)) {
                $supportDocs[] = $storedFileName; // Save stored file name
                $originalNames[] = $originalFileName; // Save original file name
            }
        }
    }
    $supportDocsStr = implode(",", $supportDocs);
    $originalNamesStr = implode(",", $originalNames);

    // Handle PhonePe Scanner upload
    $phonepeScanner = "";
    if (!empty($_FILES["phonepeScanner"]["name"])) {
        $originalPhonePe = basename($_FILES["phonepeScanner"]["name"]);
        $storedPhonePe = time() . "_" . $originalPhonePe;
        $targetFilePath = $targetDir . $storedPhonePe;
        
        if (move_uploaded_file($_FILES["phonepeScanner"]["tmp_name"], $targetFilePath)) {
            $phonepeScanner = $storedPhonePe;
        }
    }

    // Prepare the query to insert the form data into the database
    $query = "INSERT INTO need_based_scholarships 
        (full_name, student_id, mobile_number, branch, year_of_study, reason, description, amount_required, support_docs, original_filenames, phonepe_scanner, bank_acc_no, bank_name, acc_holder_name, ifsc, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')";

    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        die("Query Preparation Failed: " . $conn->error);
    }

    $stmt->bind_param("sssssssssssssss", 
        $fullName, 
        $studentID, 
        $mobileNumber, 
        $branch, 
        $year, 
        $reason, 
        $description, 
        $amount_required, 
        $supportDocsStr, 
        $originalNamesStr, 
        $phonepeScanner, 
        $bankAccNo, 
        $bankName, 
        $accHolderName, 
        $ifsc
    );
    
    if ($stmt->execute()) {
        $successMessage = "Application submitted successfully!";
    } else {
        $errorMessage = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Submission Status</title>
  <style>
      body {
          font-family: Arial, sans-serif;
          background: url('back.png') no-repeat center center fixed;
          background-size: cover;
          display: flex;
          justify-content: center;
          align-items: center;
          height: 100vh;
          margin: 0;
      .message-container {
          background: white;
          padding: 20px;
          border-radius: 10px;
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
          max-width: 400px;
          text-align: center;
      }
      .success {
          color: green;
          font-size: 18px;
          font-weight: bold;
      }
      .error {
          color: red;
          font-size: 18px;
          font-weight: bold;
      }
      .home-button {
          margin-top: 20px;
          padding: 12px 20px;
          font-size: 16px;
          font-weight: bold;
          color: white;
          background: #007BFF;
          border: none;
          border-radius: 8px;
          cursor: pointer;
          text-decoration: none;
          display: inline-block;
          transition: 0.3s;
          position: relative;
          overflow: hidden;
      }
      .home-button::after {
          content: '';
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: rgba(255, 255, 255, 0.2);
          opacity: 0;
          transition: opacity 0.3s;
      }
      .home-button:hover::after {
          opacity: 1;
      }
      .home-button:hover {
          background: #0056b3;
          box-shadow: 0 0 10px #007BFF, 0 0 40px #007BFF;
      }
  </style>
</head>
<body>

  <div class="message-container">
      <?php if (!empty($successMessage)) : ?>
          <p class="success"><?= $successMessage; ?></p>
      <?php elseif (!empty($errorMessage)) : ?>
          <p class="error"><?= $errorMessage; ?></p>
      <?php endif; ?>

      <!-- Back to Home Button inside message container -->
      <button class="home-button" onclick="window.location.href='scholarshiptype.html';">Back to Home</button>
  </div>

</body>
</html>
