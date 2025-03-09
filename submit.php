<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "your_password"; // Change with your database password
$dbname = "scholarship_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = $_POST['userid'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $branch = $_POST['branch'];
    $year = $_POST['year'];
    $feesReceipt = $_FILES['feesReceipt']['name'];
    $admissionForm = $_FILES['admissionForm']['name'];
    $bankXerox = $_FILES['bankXerox']['name'];
    $bankAccNo = $_POST['bankAccNo'];
    $bankName = $_POST['bankName'];
    $bankBranch = $_POST['bankBranch'];
    $accHolderName = $_POST['accHolderName'];
    $ifsc = $_POST['ifsc'];
    $mobile = $_POST['mobile'];

    // Move uploaded files to a designated folder
    $uploadDir = 'uploads/';
    move_uploaded_file($_FILES['feesReceipt']['tmp_name'], $uploadDir . $feesReceipt);
    move_uploaded_file($_FILES['admissionForm']['tmp_name'], $uploadDir . $admissionForm);
    move_uploaded_file($_FILES['bankXerox']['tmp_name'], $uploadDir . $bankXerox);

    // SQL query to insert data
    $sql = "INSERT INTO applications (userid, password, name, branch, year, feesReceipt, admissionForm, bankXerox, bankAccNo, bankName, bankBranch, accHolderName, ifsc, mobile, status) 
            VALUES ('$userid', '$password', '$name', '$branch', '$year', '$feesReceipt', '$admissionForm', '$bankXerox', '$bankAccNo', '$bankName', '$bankBranch', '$accHolderName', '$ifsc', '$mobile', 'Pending')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "Pending", "message" => "Application submitted successfully"]);
    } else {
        echo json_encode(["status" => "Error", "message" => "Error: " . $conn->error]);
    }
}

$conn->close();
?>
