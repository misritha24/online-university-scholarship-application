<?php
include 'db.php'; // Include database connection file

// Fetch all international scholarship applications
$query = "SELECT * FROM international_scholarships";
$result = $conn->query($query);

if (!$result) {
    die("Query Failed: " . $conn->error); // Display error if query fails
}

// Fetch count of different statuses
$totalQuery = "SELECT COUNT(*) as total FROM international_scholarships";
$approvedQuery = "SELECT COUNT(*) as approved FROM international_scholarships WHERE status='Approved'";
$pendingQuery = "SELECT COUNT(*) as pending FROM international_scholarships WHERE status='Pending'";
$rejectedQuery = "SELECT COUNT(*) as rejected FROM international_scholarships WHERE status='Rejected'";

$totalResult = $conn->query($totalQuery);
$approvedResult = $conn->query($approvedQuery);
$pendingResult = $conn->query($pendingQuery);
$rejectedResult = $conn->query($rejectedQuery);

// Error checking for count queries
if ($totalResult && $approvedResult && $pendingResult && $rejectedResult) {
    $totalData = $totalResult->fetch_assoc();
    $approvedData = $approvedResult->fetch_assoc();
    $pendingData = $pendingResult->fetch_assoc();
    $rejectedData = $rejectedResult->fetch_assoc();
} else {
    echo "Error: " . $conn->error;
}

// Function to generate file links
function getDownloadLink($fileName) {
    if (!empty($fileName)) {
        // Trim unnecessary spaces
        $fileName = trim($fileName);

        // Ensure $fileName does not already contain "uploads/"
        if (strpos($fileName, "uploads/") === 0) {
            $filePath = __DIR__ . "/" . $fileName;  // Use as-is
            $fileUrl = $fileName;  // No need to prepend "uploads/"
        } else {
            $filePath = __DIR__ . "/uploads/" . $fileName; // Corrected absolute path
            $fileUrl = "uploads/" . urlencode($fileName);  // Corrected web URL
        }

        // Debugging: Uncomment to see the actual path being checked
        // echo "<pre>Checking: " . $filePath . "</pre>";

        if (file_exists($filePath) && is_readable($filePath)) {
            return "<a class='file-link' href='$fileUrl' download>Download</a>";
        } else {
            return "<span style='color: red;'>File not found (Checked: $filePath)</span>";
        }
    }
    return "No file uploaded";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>International Scholarship Applications</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .dashboard {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.2);
            text-align: center;
            width: 22%;
        }

        .card h2 {
            margin: 10px 0;
            font-size: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        .status-dropdown {
            padding: 5px;
            font-size: 14px;
        }

        .file-link {
            color: #007BFF;
            text-decoration: none;
        }

        .file-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h1>International Scholarship Applications Dashboard</h1>

    <!-- Dashboard Overview -->
    <div class="dashboard">
    <div class="card">
        <h2>Total Applications</h2>
        <p id="totalCount"><?= $totalData['total']; ?></p>
    </div>
    <div class="card" style="background: #28a745; color: white;">
        <h2>Approved</h2>
        <p id="approvedCount"><?= $approvedData['approved']; ?></p>
    </div>
    <div class="card" style="background: #ffc107; color: white;">
        <h2>Pending</h2>
        <p id="pendingCount"><?= $pendingData['pending']; ?></p>
    </div>
    <div class="card" style="background: #dc3545; color: white;">
        <h2>Rejected</h2>
        <p id="rejectedCount"><?= $rejectedData['rejected']; ?></p>
    </div>
</div>


    <!-- Applications Table -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>userid</th>
                <th>password</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Date of Birth</th>
                <th>Nationality</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Qualification</th>
                <th>Transcript</th>
                <th>Language Certificate</th>
                <th>Recommendation Letter</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']); ?></td>
                    <td><?= htmlspecialchars($row['userid']); ?></td>
                    <td><?= htmlspecialchars($row['password']); ?></td>
                    <td><?= htmlspecialchars($row['first_name']); ?></td>
                    <td><?= htmlspecialchars($row['last_name']); ?></td>
                    <td><?= htmlspecialchars($row['date_of_birth']); ?></td>
                    <td><?= htmlspecialchars($row['nationality']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td><?= htmlspecialchars($row['phone_number']); ?></td>
                    <td><?= htmlspecialchars($row['highest_qualification']); ?></td>

                    <td><?= getDownloadLink($row['transcript_upload']); ?></td>
                    <td><?= getDownloadLink($row['language_certificate']); ?></td>
                    <td><?= getDownloadLink($row['recommendation_letter']); ?></td>

                    <td>
                        <select class="status-dropdown" data-id="<?= $row['id']; ?>">
                            <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="Approved" <?= $row['status'] == 'Approved' ? 'selected' : ''; ?>>Approved</option>
                            <option value="Rejected" <?= $row['status'] == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $(".status-dropdown").change(function () {
            var status = $(this).val();
            var id = $(this).data("id");
            var dropdown = $(this);

            $.ajax({
                url: "update_statusss.php",
                type: "POST",
                data: { id: id, status: status },
                success: function (response) {
                    if (response === "success") {
                        alert("Status updated successfully!");

                        // Update counts immediately
                        fetchCounts();
                    } else {
                        alert("Error updating status. Please try again.");
                    }
                },
                error: function () {
                    alert("AJAX request failed.");
                }
            });
        });

        // Function to fetch updated counts
        function fetchCounts() {
            $.ajax({
                url: "fetch_counts.php",
                type: "GET",
                dataType: "json",
                success: function (data) {
                    $("#totalCount").text(data.total);
                    $("#approvedCount").text(data.approved);
                    $("#pendingCount").text(data.pending);
                    $("#rejectedCount").text(data.rejected);
                },
                error: function () {
                    console.log("Failed to update counts.");
                }
            });
        }
    });
</script>



</body>
</html>
