<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

include 'config.php'; // Your database configuration

// Retrieve user information from the session
$user = $_SESSION['user'];

// Handle staff selection
$selected_staff = $_POST['staff'] ?? 'all';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 70px;
            background-color: #f8f9fa;
        }
        .header {
            background-color: #007bff;
            color: #fff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .content {
            max-width: 1000px;
            margin: 0 auto;
        }
        .staff-details {
            background-color: #e9ecef;
            padding: 10px;
            margin-top: 15px;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Examination Claim System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" 
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav me-auto mb-5 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="dashboardMA.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="payment.php">Payment Details</a></li>
                    <li class="nav-item"><a class="nav-link" href="summary.php">Summary</a></li>
                    <li class="nav-item"><a class="nav-link" href="approved.php">Approved</a></li>
                    <li class="nav-item"><a class="nav-link" href="Registerform.php">Add User</a></li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo htmlspecialchars($user['usertype']." : ".$user['name']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

<div class="content">
    <div class="header">
        <h2>Payment Details for Staff</h2>
    </div>
    
    <!-- Form to Select Staff Member -->
    <form method="POST" action="payment.php" class="mb-4">
        <div class="mb-3">
            <label for="staff" class="form-label">Select Staff:</label>
            <select id="staff" name="staff" class="form-select" onchange="this.form.submit()">
                <option value="all" <?php echo ($selected_staff == 'all') ? 'selected' : ''; ?>>All</option>
                <option value="Dr. S. Mahesan" <?php echo ($selected_staff == 'Dr. S. Mahesan') ? 'selected' : ''; ?>>Dr. S. Mahesan</option>
                <option value="Dr. E. Y. A. Charles" <?php echo ($selected_staff == 'Dr. E. Y. A. Charles') ? 'selected' : ''; ?>>Dr. E. Y. A. Charles</option>
                <option value="Dr. K. Thabotharan" <?php echo ($selected_staff == 'Dr. K. Thabotharan') ? 'selected' : ''; ?>>Dr. K. Thabotharan</option>
                <option value="Prof. A. Ramanan" <?php echo ($selected_staff == 'Prof. A. Ramanan') ? 'selected' : ''; ?>>Prof. A. Ramanan</option>
                <option value="Mr. S. Suthakar" <?php echo ($selected_staff == 'Mr. S. Suthakar') ? 'selected' : ''; ?>>Mr. S. Suthakar</option>
                <option value="Dr. (Mrs.) B. Mayurathan" <?php echo ($selected_staff == 'Dr. (Mrs.) B. Mayurathan') ? 'selected' : ''; ?>>Dr. (Mrs.) B. Mayurathan</option>
                <option value="Prof. M. Siyamalan" <?php echo ($selected_staff == 'Prof. M. Siyamalan') ? 'selected' : ''; ?>>Prof. M. Siyamalan</option>
                <option value="Dr. K. Sarveswaran" <?php echo ($selected_staff == 'Dr. K. Sarveswaran') ? 'selected' : ''; ?>>Dr. K. Sarveswaran</option>
                <option value="Dr. S. Shriparen" <?php echo ($selected_staff == 'Dr. S. Shriparen') ? 'selected' : ''; ?>>Dr. S. Shriparen</option>
                <option value="Dr. T. Kokul" <?php echo ($selected_staff == 'Dr. T. Kokul') ? 'selected' : ''; ?>>Dr. T. Kokul</option>
                <option value="Dr. (Ms.) J. Samantha Tharani" <?php echo ($selected_staff == 'Dr. (Ms.) J. Samantha Tharani') ? 'selected' : ''; ?>>Dr. (Ms.) J. Samantha Tharani</option>
                <option value="Dr. (Ms.) R. Nirthika" <?php echo ($selected_staff == 'Dr. (Ms.) R. Nirthika') ? 'selected' : ''; ?>>Dr. (Ms.) R. Nirthika</option>
                <option value="Ms. M. Mayuravaani" <?php echo ($selected_staff == 'Ms. M. Mayuravaani') ? 'selected' : ''; ?>>Ms. M. Mayuravaani</option>
            </select>
        </div>
    </form>

    <?php
    // If "All" is selected, get all staff payment details grouped by staff
    if ($selected_staff == 'all') {
        $staff_sql = "SELECT claimID, staffName, courseCode, examType, preparationType, essayDuration, essayAmount, mcqCount, mcqAmount, pageCount, typingAmount, totalAmount FROM form1 ORDER BY staffName";
        $result = $conn->query($staff_sql);
        
        if ($result->num_rows > 0) {
            $current_staff = '';
            while ($row = $result->fetch_assoc()) {
                if ($current_staff != $row['staffName']) {
                    if ($current_staff != ''){
                        echo "<tr><td colspan='9' class='text-end'><strong>Grand Total:</strong></td><td><strong>" . number_format($grandTotal, 2) . "</strong></td></tr>";
                        echo '</tbody></table><br>';
                    }
                    $current_staff = $row['staffName'];
                    $grandTotal = 0;
                    
                    // Get and display specific staff details
                    $staff_details_sql = "SELECT name, nic, designation FROM userdetails WHERE name = ?";
                    $staff_stmt = $conn->prepare($staff_details_sql);
                    $staff_stmt->bind_param("s", $current_staff);
                    $staff_stmt->execute();
                    $staff_details = $staff_stmt->get_result()->fetch_assoc();
                    echo '<div class="staff-details">' . htmlspecialchars($staff_details['name']) . " | NIC: " . htmlspecialchars($staff_details['nic']) . " | Designation: " . htmlspecialchars($staff_details['designation']) . '</div>';
                    $staff_stmt->close();
                    
                    // Start new table for each staff
                    echo '<table class="table table-striped"><thead><tr><th>Course Code</th><th>Exam Type</th><th>Preparation Type</th><th>Essay Duration</th><th>Amount for Essay</th><th>MCQ Count</th><th>Amount for MCQ</th><th>Pages Count</th><th>Amount for Typing</th><th>Total Amount</th></tr></thead><tbody>';
                }

                $grandTotal += $row['totalAmount'];

                // Display staff details in rows
                echo "
                    <tr>
                        <td>{$row['courseCode']}</td>
                        <td>{$row['examType']}</td>
                        <td>{$row['preparationType']}</td>
                        <td>{$row['essayDuration']}</td>
                        <td>{$row['essayAmount']}</td>
                        <td>{$row['mcqCount']}</td>
                        <td>{$row['mcqAmount']}</td>
                        <td>{$row['pageCount']}</td>
                        <td>{$row['typingAmount']}</td>
                        <td>{$row['totalAmount']}</td>
                        <td><a href='edit.php?id={$row['claimID']}' class='btn btn-primary btn-sm me-2'>Edit</a></td>
                        <td><a href='delete.php?id={$row['claimID']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this record?\");'>Delete</a></td>
                    </tr>";
            }
            echo "<tr><td colspan='9' class='text-end'><strong>Grand Total:</strong></td><td><strong>" . number_format($grandTotal, 2) . "</strong></td></tr>";
            echo '</tbody></table>';// Close the last table
        } else {
            echo "<p>No records found.</p>";
        }
    } else {
        $grandTotal=0;
        // If specific staff is selected, show only that staff's details
        $staff_details_sql = "SELECT name, nic, designation FROM userdetails WHERE name = ?";
        $staff_stmt = $conn->prepare($staff_details_sql);
        $staff_stmt->bind_param("s", $selected_staff);
        $staff_stmt->execute();
        $staff_details = $staff_stmt->get_result()->fetch_assoc();
        echo '<div class="staff-details">' . htmlspecialchars($staff_details['name']) . " | NIC: " . htmlspecialchars($staff_details['nic']) . " | Designation: " . htmlspecialchars($staff_details['designation']) . '</div>';
        $staff_stmt->close();

        $stmt = $conn->prepare("SELECT courseCode, examType, preparationType, essayDuration, essayAmount, mcqCount, mcqAmount, pageCount, typingAmount, totalAmount FROM form1 WHERE staffName = ?");
        $stmt->bind_param("s", $selected_staff);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo '<table class="table table-striped"><thead><tr><th>Course Code</th><th>Exam Type</th><th>Preparation Type</th><th>Essay Duration</th><th>Amount for Essay</th><th>MCQ Count</th><th>Amount for MCQ</th><th>Pages Count</th><th>Amount for Typing</th><th>Total Amount</th></tr></thead><tbody>';
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>{$row['courseCode']}</td><td>{$row['examType']}</td><td>{$row['preparationType']}</td><td>{$row['essayDuration']}</td><td>{$row['essayAmount']}</td><td>{$row['mcqCount']}</td><td>{$row['mcqAmount']}</td><td>{$row['pageCount']}</td><td>{$row['typingAmount']}</td><td>{$row['totalAmount']}</td></tr>";
                $grandTotal += $row['totalAmount'];
            }
            echo "<tr><td colspan='9' class='text-end'><strong>Grand Total:</strong></td><td><strong>" . number_format($grandTotal, 2) . "</strong></td></tr>";
            echo '</tbody></table>';
        } else {
            echo "<p>No records found for the selected staff.</p>";
        }
        $stmt->close();
    }
    $conn->close();
    ?>
</div>
<!-- Bootstrap JS Bundle (Includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

