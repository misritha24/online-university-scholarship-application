<?php  
include 'db.php'; // Include database connection file

// Fetch all need-based scholarship applications
$query = "SELECT * FROM need_based_scholarships";
$result = $conn->query($query);

// Check if query was successful
if (!$result) {
    die("Query Failed: " . $conn->error);
}

// Fetch count of different statuses
$totalQuery = "SELECT COUNT(*) as total FROM need_based_scholarships";
$approvedQuery = "SELECT COUNT(*) as approved FROM need_based_scholarships WHERE status='Approved'";
$pendingQuery = "SELECT COUNT(*) as pending FROM need_based_scholarships WHERE status='Pending'";
$rejectedQuery = "SELECT COUNT(*) as rejected FROM need_based_scholarships WHERE status='Rejected'";

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
    <title>Need-Based Scholarship Applications</title>
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
        a {
            text-decoration: none;
            color: #007BFF;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h1>Need-Based Scholarship Dashboard</h1>

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
                <th>Full Name</th>
                <th>Student ID</th>
                <th>Mobile Number</th>
                <th>Branch</th>
                <th>Year</th>
                <th>Reason</th>
                <th>Description</th>
                <th>Amount Required</th>
                <th>PhonePe Scanner</th>
                <th>Bank Acc No</th>
                <th>Bank Name</th>
                <th>Acc Holder Name</th>
                <th>IFSC</th>
                <th>Supporting Documents</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']); ?></td>
                    <td><?= htmlspecialchars($row['full_name']); ?></td>
                    <td><?= htmlspecialchars($row['student_id']); ?></td>
                    <td><?= htmlspecialchars($row['mobile_number']); ?></td>
                    <td><?= htmlspecialchars($row['branch']); ?></td>
                    <td><?= htmlspecialchars($row['year_of_study']); ?></td>
                    <td><?= htmlspecialchars($row['reason']); ?></td>
                    <td><?= htmlspecialchars($row['description']); ?></td>
                    <td><?= htmlspecialchars($row['amount_required']); ?></td>
                    
                    <!-- PhonePe Scanner -->
                    <td>
                        <?php 
                        $phonepePath = "uploads/" . $row['phonepe_scanner'];
                        if (!empty($row['phonepe_scanner']) && file_exists($phonepePath)): ?>
                            <a href="<?= $phonepePath ?>" target="_blank">View</a>
                        <?php else: ?>
                            No file uploaded
                        <?php endif; ?>
                    </td>

                    <td><?= htmlspecialchars($row['bank_acc_no']); ?></td>
                    <td><?= htmlspecialchars($row['bank_name']); ?></td>
                    <td><?= htmlspecialchars($row['acc_holder_name']); ?></td>
                    <td><?= htmlspecialchars($row['ifsc']); ?></td>

                    <!-- Supporting Documents -->
                    <td>
                        <?php 
                        $supportDocs = explode(",", $row['support_docs']);
                        $originalNames = explode(",", $row['original_filenames']);
                        
                        if (!empty($row['support_docs'])): 
                            foreach ($supportDocs as $index => $doc): 
                                $supportPath = "uploads/" . trim($doc);
                                $fileExtension = strtolower(pathinfo($supportPath, PATHINFO_EXTENSION));

                                if (file_exists($supportPath)): 
                                    // For PDF or image files, open in new tab; otherwise, provide a download link
                                    if (in_array($fileExtension, ['pdf', 'jpg', 'jpeg', 'png'])): ?>
                                        <a href="<?= $supportPath ?>" target="_blank"><?= htmlspecialchars($originalNames[$index]) ?></a><br>
                                    <?php else: ?>
                                        <a href="<?= $supportPath ?>" target="_blank" download><?= htmlspecialchars($originalNames[$index]) ?> (Download)</a><br>
                                    <?php endif; 
                                else: ?>
                                    <span style="color: red;">File not found</span><br>
                                <?php endif; ?>
                            <?php endforeach; 
                        else: ?>
                            No file uploaded
                        <?php endif; ?>
                    </td>

                    <!-- Status Dropdown -->
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
            var applicationId = $(this).data("id");
            var newStatus = $(this).val();
            var dropdown = $(this); // Store reference to the dropdown

            $.ajax({
                url: "update_statuss.php", // Server-side script to handle update
                type: "POST",
                data: { id: applicationId, status: newStatus },
                success: function(response) {
                    // Expecting a JSON response
                    try {
                        var data = JSON.parse(response);
                        if (data.success) {
                            alert("Status updated successfully!");
                            // Update counts dynamically if provided in response
                            if(data.approved !== undefined) {
                                $(".dashboard .card:nth-child(2) p").text(data.approved);
                            }
                            if(data.pending !== undefined) {
                                $(".dashboard .card:nth-child(3) p").text(data.pending);
                            }
                            if(data.rejected !== undefined) {
                                $(".dashboard .card:nth-child(4) p").text(data.rejected);
                            }
                        } else {
                            alert("Failed to update status.");
                            // Revert to previous value if update fails
                            dropdown.val(dropdown.data("current"));
                        }
                    } catch(e) {
                        alert("Error parsing response.");
                    }
                }
            });
        });
    });
    </script>

</body>
</html>
