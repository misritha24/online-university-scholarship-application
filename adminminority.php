<?php
session_start();
$conn = new mysqli("localhost", "root", "", "scholarship_db");

// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

// Function to get application counts
function getCount($conn, $status = "") {
    $query = $status ? "SELECT COUNT(*) as count FROM min_form WHERE status=?" : "SELECT COUNT(*) as count FROM min_form";
    $stmt = $conn->prepare($query);
    if ($status) {
        $stmt->bind_param("s", $status);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'];
    $stmt->close();
    return $count;
}

// Handle AJAX request for updating status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['status'])) {
    $id = intval($_POST['id']); // Ensure ID is an integer
    $status = $_POST['status'];

    // Validate status values
    $validStatuses = ["Pending", "Approved", "Rejected"];
    if (!in_array($status, $validStatuses)) {
        echo json_encode(["success" => false, "message" => "Invalid status"]);
        exit();
    }

    // Update the status in the database
    $stmt = $conn->prepare("UPDATE min_form SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    $affectedRows = $stmt->affected_rows;
    $stmt->close();

    if ($affectedRows > 0) { // Only update counts if the status change was successful
        echo json_encode([
            "success" => true,
            "total" => getCount($conn),
            "approved" => getCount($conn, "Approved"),
            "pending" => getCount($conn, "Pending"),
            "rejected" => getCount($conn, "Rejected")
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "No changes made, status might be the same"]);
    }
    exit();
}

// Fetch all applications
$applicationsResult = $conn->query("SELECT * FROM min_form");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minority Scholarship Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; text-align: center; }
        .container { width: 90%; margin: auto; background: white; padding: 20px; border-radius: 10px; }
        h2 { margin-bottom: 20px; }
        .dashboard { display: flex; justify-content: center; gap: 20px; margin-bottom: 20px; }
        .card {
            padding: 20px; border-radius: 10px; color: white; font-size: 18px;
            font-weight: bold; width: 200px; box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
        }
        .total { background: #ddd; color: black; }
        .approved { background: #28a745; }
        .pending { background: #ffc107; color: black; }
        .rejected { background: #dc3545; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background: #007BFF; color: white; }
        select { padding: 5px; border-radius: 5px; border: 1px solid #ccc; cursor: pointer; }
    </style>
</head>
<body>

<div class="container">
    <h2>Minority Scholarship Dashboard</h2>

    <div class="dashboard">
        <div class="card total">Total Applications <br><span id="total"><?php echo getCount($conn); ?></span></div>
        <div class="card approved">Approved <br><span id="approved"><?php echo getCount($conn, "Approved"); ?></span></div>
        <div class="card pending">Pending <br><span id="pending"><?php echo getCount($conn, "Pending"); ?></span></div>
        <div class="card rejected">Rejected <br><span id="rejected"><?php echo getCount($conn, "Rejected"); ?></span></div>
    </div>

    <table>
        <tr>
            <th>Name</th>
            <th>Registration No</th>
            <th>University</th>
            <th>Course</th>
            <th>Father's Income</th>
            <th>Status</th>
        </tr>
        <?php while ($row = $applicationsResult->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['reg_no']; ?></td>
                <td><?php echo $row['university']; ?></td>
                <td><?php echo $row['course']; ?></td>
                <td>₹<?php echo number_format($row['father_income']); ?></td>
                <td>
                    <select class="status-dropdown" data-id="<?php echo $row['id']; ?>">
                        <option value="Pending" <?php if ($row['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                        <option value="Approved" <?php if ($row['status'] == 'Approved') echo 'selected'; ?>>Approved</option>
                        <option value="Rejected" <?php if ($row['status'] == 'Rejected') echo 'selected'; ?>>Rejected</option>
                    </select>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>

<script>
$(document).ready(function () {
    $(".status-dropdown").change(function () {
        var status = $(this).val();
        var id = $(this).data("id");

        $.ajax({
            url: "adminminority.php",
            type: "POST",
            data: { id: id, status: status },
            success: function (response) {
                try {
                    var result = JSON.parse(response);
                    if (result.success) {
                        $("#total").text(result.total);
                        $("#approved").text(result.approved);
                        $("#pending").text(result.pending);
                        $("#rejected").text(result.rejected);
                    } else {
                        alert("Error: " + result.message);
                        location.reload(); // Reload if update fails
                    }
                } catch (e) {
                    alert("Invalid response from server.");
                    console.log("Server Response:", response);
                    location.reload();
                }
            },
            error: function (xhr, status, error) {
                alert("AJAX Error: " + error);
                location.reload();
            }
        });
    });
});
</script>

</body>
</html>

<?php $conn->close(); ?>
