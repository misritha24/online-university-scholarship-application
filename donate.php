<?php  
include 'db.php'; // Include database connection file

// Fetch only approved need-based scholarship applications
$query = "SELECT * FROM need_based_scholarships WHERE status='Approved'";
$result = $conn->query($query);

// Check if query was successful
if (!$result) {
    die("Query Failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Donate to Approved Scholarship Applications</title>
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

  <h1>Donate to Approved Scholarship Applications</h1>

  <!-- Approved Applications Table -->
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
        
        <!-- Instead of directly viewing the scanner file, link to enter_details.html -->
        <td>
        <a href="enter_details.php?id=<?= $row['id']; ?>">Donate</a>



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
                      if (in_array($fileExtension, ['pdf', 'jpg', 'jpeg', 'png'])): ?>
                        <a href="<?= $supportPath ?>" target="_blank"><?= htmlspecialchars($originalNames[$index]) ?></a><br>
                      <?php else: ?>
                        <a href="<?= $supportPath ?>" target="_blank" download><?= htmlspecialchars($originalNames[$index]) ?> (Download)</a><br>
                      <?php endif; 
                  else: ?>
                    <span style="color: red;">File not found</span><br>
                  <?php endif; 
              endforeach; 
          else: ?>
            No file uploaded
          <?php endif; ?>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

</body>
</html>
