<?php
include 'db.php'; // Include database connection file

// Fetch all applications
$query = "SELECT * FROM applications";
$result = $conn->query($query);

// Fetch count of different statuses
$totalQuery = "SELECT COUNT(*) as total FROM applications";
$approvedQuery = "SELECT COUNT(*) as approved FROM applications WHERE status='Approved'";
$pendingQuery = "SELECT COUNT(*) as pending FROM applications WHERE status='Pending'";
$rejectedQuery = "SELECT COUNT(*) as rejected FROM applications WHERE status='Rejected'";

$totalResult = $conn->query($totalQuery)->fetch_assoc();
$approvedResult = $conn->query($approvedQuery)->fetch_assoc();
$pendingResult = $conn->query($pendingQuery)->fetch_assoc();
$rejectedResult = $conn->query($rejectedQuery)->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholarship Applications</title>
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
    </style>
</head>
<body>

    <h1>Scholarship Applications Dashboard</h1>

    <!-- Dashboard Overview -->
    <div class="dashboard">
        <div class="card">
            <h2>Total Applications</h2>
            <p><?= $totalResult['total']; ?></p>
        </div>
        <div class="card" style="background: #28a745; color: white;">
            <h2>Approved</h2>
            <p><?= $approvedResult['approved']; ?></p>
        </div>
        <div class="card" style="background: #ffc107; color: white;">
            <h2>Pending</h2>
            <p><?= $pendingResult['pending']; ?></p>
        </div>
        <div class="card" style="background: #dc3545; color: white;">
            <h2>Rejected</h2>
            <p><?= $rejectedResult['rejected']; ?></p>
        </div>
    </div>

    <!-- Applications Table -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Full Name</th>
                <th>Branch</th>
                <th>Year</th>
                <th>Fees Receipt</th>
                <th>Admission Form</th>
                <th>Bank Xerox</th>
                <th>Bank Account No</th>
                <th>Bank Name</th>
                <th>Bank Branch</th>
                <th>Account Holder Name</th>
                <th>IFSC</th>
                <th>Mobile Number</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']); ?></td>
                    <td><?= htmlspecialchars($row['user_id']); ?></td>
                    <td><?= htmlspecialchars($row['full_name']); ?></td>
                    <td><?= htmlspecialchars($row['branch']); ?></td>
                    <td><?= htmlspecialchars($row['year']); ?></td>
                    <td><a href="uploads/<?= htmlspecialchars($row['feesReceipt']); ?>" target="_blank">View</a></td>
                    <td><a href="uploads/<?= htmlspecialchars($row['admissionForm']); ?>" target="_blank">View</a></td>
                    <td><a href="uploads/<?= htmlspecialchars($row['bankXerox']); ?>" target="_blank">View</a></td>
                    <td><?= htmlspecialchars($row['bankAccNo']); ?></td>
                    <td><?= htmlspecialchars($row['bankName']); ?></td>
                    <td><?= htmlspecialchars($row['bankBranch']); ?></td>
                    <td><?= htmlspecialchars($row['accHolderName']); ?></td>
                    <td><?= htmlspecialchars($row['ifsc']); ?></td>
                    <td><?= htmlspecialchars($row['mobile_number']); ?></td>
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

    <script>
        $(document).ready(function() {
            $(".status-dropdown").change(function() {
                var id = $(this).data("id");
                var newStatus = $(this).val();

                $.ajax({
                    url: "update_status.php",
                    type: "POST",
                    data: { id: id, status: newStatus },
                    success: function(response) {
                        alert("Status updated successfully!");
                        location.reload();
                    }
                });
            });
        });
    </script>

</body>
</html>
