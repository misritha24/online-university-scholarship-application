<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholarship Application</title>
    <style>
        /* Background styling */
        body {
            font-family: Arial, sans-serif;
            background: url('merit1.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Form container */
        .form-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 75%;
            max-width: 800px;
            height: 80vh;
            overflow-y: auto; /* Enables scrolling if form is too long */
        }

        h1 {
            text-align: center;
            color: #333;
        }

        /* Label styling */
        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }

        /* Input styling */
        input[type="text"], 
        input[type="password"], 
        input[type="number"], 
        input[type="file"], 
        button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 2px solid #ccc;
            border-radius: 5px;
            transition: 0.3s ease-in-out;
            display: block;
        }

        /* Glowing effect when focusing on input fields */
        input:focus, input:hover {
            border-color: #00bcd4;
            box-shadow: 0 0 10px #00bcd4;
            outline: none;
        }

        /* Button styling */
        button {
            background-color: #00bcd4;
            color: white;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: 0.3s ease;
        }

        button:hover {
            background-color: #0097a7;
            box-shadow: 0 0 10px #00bcd4;
        }

        /* Go Back button */
        .go-back-btn {
            background-color: #ff4081;
            color: white;
            font-size: 16px;
            border: none;
            padding: 10px;
            cursor: pointer;
            width: 100%;
            text-align: center;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-container {
                width: 90%;
                height: 90vh; /* Increased height for better mobile experience */
            }
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h1>Scholarship Application</h1>

        <?php
        // Database connection
        $host = '127.0.0.1';
        $db = 'scholarship_db';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $pdo = new PDO($dsn, $user, $pass, $options);

        // Check if Update button was clicked
        if (isset($_POST['update'])) {
            $id = $_POST['id'];
            $stmt = $pdo->prepare("SELECT * FROM applications WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $application = $stmt->fetch();
        }

        // Handle form submission
        if (isset($_POST['submit'])) {
            // Retrieve form data
            $id = $_POST['id'] ?? null;
            $user_id = $_POST['user_id'];
            $password = $_POST['password'];
            $full_name = $_POST['full_name'];
            $branch = $_POST['branch'];
            $year = $_POST['year'];
            $bankAccNo = $_POST['bankAccNo'];
            $bankName = $_POST['bankName'];
            $bankBranch = $_POST['bankBranch'];
            $accHolderName = $_POST['accHolderName'];
            $ifsc = $_POST['ifsc'];
            $mobile_number = $_POST['mobile_number'];

            // Handle file uploads
            $feesReceipt = $_FILES['feesReceipt']['name'] ?? $application['feesReceipt'];
            $admissionForm = $_FILES['admissionForm']['name'] ?? $application['admissionForm'];
            $bankXerox = $_FILES['bankXerox']['name'] ?? $application['bankXerox'];

            $uploadDir = "uploads/";
            if (!empty($_FILES['feesReceipt']['tmp_name'])) {
                move_uploaded_file($_FILES['feesReceipt']['tmp_name'], $uploadDir . $feesReceipt);
            }
            if (!empty($_FILES['admissionForm']['tmp_name'])) {
                move_uploaded_file($_FILES['admissionForm']['tmp_name'], $uploadDir . $admissionForm);
            }
            if (!empty($_FILES['bankXerox']['tmp_name'])) {
                move_uploaded_file($_FILES['bankXerox']['tmp_name'], $uploadDir . $bankXerox);
            }

            // Update or Insert data into the database
            if ($id) {
                $sql = "UPDATE applications SET 
                            user_id = :user_id, 
                            password = :password, 
                            full_name = :full_name, 
                            branch = :branch, 
                            year = :year, 
                            feesReceipt = :feesReceipt, 
                            admissionForm = :admissionForm, 
                            bankXerox = :bankXerox, 
                            bankAccNo = :bankAccNo, 
                            bankName = :bankName, 
                            bankBranch = :bankBranch, 
                            accHolderName = :accHolderName, 
                            ifsc = :ifsc, 
                            mobile_number = :mobile_number 
                        WHERE id = :id";

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':id' => $id,
                    ':user_id' => $user_id,
                    ':password' => $password,
                    ':full_name' => $full_name,
                    ':branch' => $branch,
                    ':year' => $year,
                    ':feesReceipt' => $feesReceipt,
                    ':admissionForm' => $admissionForm,
                    ':bankXerox' => $bankXerox,
                    ':bankAccNo' => $bankAccNo,
                    ':bankName' => $bankName,
                    ':bankBranch' => $bankBranch,
                    ':accHolderName' => $accHolderName,
                    ':ifsc' => $ifsc,
                    ':mobile_number' => $mobile_number
                ]);

                echo "<p>Application updated successfully!</p>";
            } else {
                $sql = "INSERT INTO applications (
                            user_id, password, full_name, branch, year, feesReceipt, admissionForm, 
                            bankXerox, bankAccNo, bankName, bankBranch, accHolderName, ifsc, 
                            mobile_number, status
                        ) VALUES (
                            :user_id, :password, :full_name, :branch, :year, :feesReceipt, :admissionForm, 
                            :bankXerox, :bankAccNo, :bankName, :bankBranch, :accHolderName, :ifsc, 
                            :mobile_number, :status
                        )";

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':user_id' => $user_id,
                    ':password' => $password,
                    ':full_name' => $full_name,
                    ':branch' => $branch,
                    ':year' => $year,
                    ':feesReceipt' => $feesReceipt,
                    ':admissionForm' => $admissionForm,
                    ':bankXerox' => $bankXerox,
                    ':bankAccNo' => $bankAccNo,
                    ':bankName' => $bankName,
                    ':bankBranch' => $bankBranch,
                    ':accHolderName' => $accHolderName,
                    ':ifsc' => $ifsc,
                    ':mobile_number' => $mobile_number,
                    ':status' => 'Pending'
                ]);

                echo "<p>Application submitted successfully!</p>";
            }

            echo "<button class='go-back-btn' onclick='window.location.href=\"scholarshiptype.html\";'>Go Back</button>";
        } else {
            // If Update button clicked, pre-fill form data
            $application = $application ?? null;
        ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $application['id'] ?? ''; ?>">

            <label for="user_id">User ID:</label>
            <input type="text" id="user_id" name="user_id" value="<?php echo $application['user_id'] ?? ''; ?>" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value="<?php echo $application['password'] ?? ''; ?>" required>

            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo $application['full_name'] ?? ''; ?>" required>

            <label for="branch">Branch:</label>
            <input type="text" id="branch" name="branch" value="<?php echo $application['branch'] ?? ''; ?>" required>

            <label for="year">Year:</label>
            <input type="number" id="year" name="year" value="<?php echo $application['year'] ?? ''; ?>" required>

            <label for="feesReceipt">Fees Receipt:</label>
            <input type="file" id="feesReceipt" name="feesReceipt">

            <label for="admissionForm">Admission Form:</label>
            <input type="file" id="admissionForm" name="admissionForm">

            <label for="bankXerox">Bank Xerox:</label>
            <input type="file" id="bankXerox" name="bankXerox">

            <label for="bankAccNo">Bank Account Number:</label>
            <input type="text" id="bankAccNo" name="bankAccNo" value="<?php echo $application['bankAccNo'] ?? ''; ?>" required>

            <label for="bankName">Bank Name:</label>
            <input type="text" id="bankName" name="bankName" value="<?php echo $application['bankName'] ?? ''; ?>" required>

            <label for="bankBranch">Bank Branch:</label>
            <input type="text" id="bankBranch" name="bankBranch" value="<?php echo $application['bankBranch'] ?? ''; ?>" required>

            <label for="accHolderName">Account Holder Name:</label>
            <input type="text" id="accHolderName" name="accHolderName" value="<?php echo $application['accHolderName'] ?? ''; ?>" required>

            <label for="ifsc">IFSC Code:</label>
            <input type="text" id="ifsc" name="ifsc" value="<?php echo $application['ifsc'] ?? ''; ?>" required>

            <label for="mobile_number">Mobile Number:</label>
            <input type="text" id="mobile_number" name="mobile_number" value="<?php echo $application['mobile_number'] ?? ''; ?>" required>

            <button type="submit" name="submit">Submit</button>
        </form>

        <?php
        }
        ?>
    </div>

</body>
</html>
