<?php
// Include database connection file
include('db_connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status</title>
    <style>
        /* Set Background Image */
        body {
            background: url('fin.png') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            color: #fff;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        /* Dark Overlay for Better Readability */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: -1;
        }

        /* Main Container */
        .container {
            max-width: 700px;
            margin: 50px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            backdrop-filter: blur(10px);
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.3);
            animation: fadeIn 1s ease-in-out;
        }

        /* Fade In Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Heading */
        h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        /* Application Details */
        p {
            font-size: 18px;
            line-height: 1.6;
            margin: 10px 0;
        }

        /* Bold Labels */
        strong {
            color: #ffd700;
        }

        /* Links */
        a {
            color: #ffcc00;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <?php
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the user ID and password from the form
        $userid = trim($_POST['userid']);
        $password = trim($_POST['password']);

        // Check if the fields are not empty
        if (!empty($userid) && !empty($password)) {
            // Prepare the SQL query to prevent SQL injection
            $sql = "SELECT * FROM applications WHERE user_id = ? AND password = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                // Bind parameters
                $stmt->bind_param('ss', $userid, $password);
                $stmt->execute();
                $result = $stmt->get_result();

                // Check if a matching record is found
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();

                    echo "<h1>Application Details</h1>";
                    echo "<p><strong>Status:</strong> " . htmlspecialchars($row['status']) . "</p>";
                    echo "<p><strong>Full Name:</strong> " . htmlspecialchars($row['full_name']) . "</p>";
                    echo "<p><strong>Branch:</strong> " . htmlspecialchars($row['branch']) . "</p>";
                    echo "<p><strong>Year:</strong> " . htmlspecialchars($row['year']) . "</p>";
                    echo "<p><strong>Bank Account Number:</strong> " . htmlspecialchars($row['bankAccNo']) . "</p>";
                    echo "<p><strong>Bank Name:</strong> " . htmlspecialchars($row['bankName']) . "</p>";
                    echo "<p><strong>Bank Branch:</strong> " . htmlspecialchars($row['bankBranch']) . "</p>";
                    echo "<p><strong>Account Holder Name:</strong> " . htmlspecialchars($row['accHolderName']) . "</p>";
                    echo "<p><strong>IFSC Code:</strong> " . htmlspecialchars($row['ifsc']) . "</p>";
                    echo "<p><strong>Mobile Number:</strong> " . htmlspecialchars($row['mobile_number']) . "</p>";
                    echo "<p><strong>Fees Receipt:</strong> <a href='uploads/" . htmlspecialchars($row['feesReceipt']) . "' target='_blank'>View</a></p>";
                    echo "<p><strong>Admission Form:</strong> <a href='uploads/" . htmlspecialchars($row['admissionForm']) . "' target='_blank'>View</a></p>";
                    echo "<p><strong>Bank Xerox:</strong> <a href='uploads/" . htmlspecialchars($row['bankXerox']) . "' target='_blank'>View</a></p>";
                } else {
                    echo "<h1>No application found with that User ID and Password.</h1>";
                }

                $stmt->close();
            } else {
                echo "<h1>Error preparing the query: " . $conn->error . "</h1>";
            }
        } else {
            echo "<h1>User ID and Password cannot be empty.</h1>";
        }

        $conn->close();
    } else {
        echo "<h1>Invalid request method.</h1>";
    }
    ?>
</div>

</body>
</html>
