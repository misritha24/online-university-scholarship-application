<?php
include 'db.php'; // Database connection

// Validate and get user ID from the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request. No ID provided.");
}

$id = intval($_GET['id']); // Sanitize ID

// Fetch user details from the database
$stmt = $conn->prepare("SELECT full_name, phonepe_scanner, amount_required FROM need_based_scholarships WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Error: No record found.");
}

$row = $result->fetch_assoc();
$fullName = htmlspecialchars($row['full_name']);
$phonepeScanner = htmlspecialchars($row['phonepe_scanner']);
$amountRequired = floatval($row['amount_required']);

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate to <?= $fullName; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
            text-align: center;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: auto;
        }
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            text-align: center;
        }
        button {
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <h2>Donate to <?= $fullName; ?></h2>

    <!-- Display PhonePe Scanner QR Code with a Clickable Link -->
    <p><strong>Scan to Pay:</strong></p>
    <a href="uploads/<?= $phonepeScanner; ?>" target="_blank">
        <img src="uploads/<?= $phonepeScanner; ?>" alt="PhonePe QR Code" width="200">
    </a>

    <div class="form-container">
        <form action="process_payment.php" method="POST">
            <input type="hidden" name="id" value="<?= $id; ?>">
            <label for="amount"><strong>Enter Amount (₹):</strong></label>
            <input type="number" name="amount" id="amount" min="1" max="<?= $amountRequired; ?>" required>
            <br><br>
            <button type="submit">Proceed to Pay</button>
        </form>
    </div>

</body>
</html>
