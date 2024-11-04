<?php
session_start();
date_default_timezone_set('Asia/Colombo');

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

include 'config.php';

// Retrieve user information from the session
$user = $_SESSION['user'];
$staffName = $user['name'];

// Handle form submission at the top to prevent headers already sent errors
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $status = ($action === 'approve') ? "Approved" : "Declined";
    $comment = isset($_POST['comment']) ? $_POST['comment'] : "Approved";
    $currentDate = date("Y-m-d H:i:s");

    // Insert into approvalStatus table
    $insert_sql = "INSERT INTO approvalstatus (name, dateTime, status, comment) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("ssss", $staffName, $currentDate, $status, $comment);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Status updated to $status successfully!";
    } else {
        $_SESSION['message'] = "Failed to update status: " . $stmt->error;
    }
    $stmt->close();

    // Redirect to avoid form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

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

        .comment-section {
            margin-top: 20px;
            background-color: #f1f1f1;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .comment {
            padding: 10px;
            margin-bottom: 10px;
            border-left: 3px solid #007bff;
            background-color: #fff;
            border-radius: 5px;
        }

        .comment-date {
            font-size: 0.9rem;
            color: #555;
        }

        .comment-text {
            font-size: 1rem;
            margin-top: 5px;
            color: #333;
        }

    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Examination Claim System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav me-auto mb-5 mb-lg-0"></ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
            <h2>Payment Details for Setting and Moderating</h2>
        </div>

        <?php
            $grandTotal = 0;
            $staff_details_sql = "SELECT name, nic, designation FROM userdetails WHERE name = ?";
            $staff_stmt = $conn->prepare($staff_details_sql);
            $staff_stmt->bind_param("s", $staffName);
            $staff_stmt->execute();
            $staff_details = $staff_stmt->get_result()->fetch_assoc();
            echo '<div class="staff-details">' . htmlspecialchars($staff_details['name']) . " | NIC: " . htmlspecialchars($staff_details['nic']) . " | Designation: " . htmlspecialchars($staff_details['designation']) . '</div>';
            $staff_stmt->close();

            $stmt = $conn->prepare("SELECT courseCode, examType, preparationType, essayDuration, essayAmount, mcqCount, mcqAmount, pageCount, typingAmount, totalAmount FROM form1 WHERE staffName = ?");
            $stmt->bind_param("s", $staffName);
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
                echo "<p>No records found.</p>";
            }
            $stmt->close();
        ?>
        
        <?php if (isset($_SESSION['message'])): ?>
            <p class="alert alert-success"><?php echo $_SESSION['message']; ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <div class="d-flex justify-content-end mt-3">
            <!-- Approve Form -->
            <form action="staffView.php" method="POST">
                <input type="hidden" name="action" value="approve">
                <button type="submit" class="btn btn-success me-2">Approve</button> 
            </form>

            <!-- Decline Form with Comment Box -->
            <form action="staffView.php" method="POST" id="declineForm">
                <input type="hidden" name="action" value="decline">
                <button type="button" class="btn btn-danger" onclick="showCommentBox()">Decline</button>
                
                <!-- Comment box for Decline action -->
                <div id="comment-box" style="display: none; margin-top: 10px;">
                    <label for="comment">Please provide the corrections to be made as comments here:</label>
                    <textarea name="comment" id="comment" rows="3" class="form-control mt-2" oninput="toggleOkButton()" required></textarea>
                    <button type="submit" class="btn btn-primary mt-2" id="ok-button" style="display: none;">OK</button>
                </div>
            </form>
        </div>

        <script>
            function showCommentBox() {
                document.getElementById('comment-box').style.display = 'block';
            }

            function toggleOkButton() {
                const commentBox = document.getElementById('comment');
                const okButton = document.getElementById('ok-button');
                okButton.style.display = commentBox.value.trim() ? 'block' : 'none';
            }
        </script>

        <br><br><br>
        <div class="comment-section">
            <h5>Added Comments:</h5>
            <?php
                $comments_sql = "SELECT comment, DATE_FORMAT(dateTime, '%Y-%m-%d %H:%i:%s') AS formatted_date FROM approvalstatus WHERE name = ? ORDER BY dateTime DESC";
                $comments_stmt = $conn->prepare($comments_sql);
                $comments_stmt->bind_param("s", $staffName);
                $comments_stmt->execute();
                $comments_result = $comments_stmt->get_result();

                if ($comments_result->num_rows > 0) {
                    while ($row = $comments_result->fetch_assoc()) {
                        echo "<div class='comment'>";
                        echo "<div class='comment-date'>{$row['formatted_date']}</div>";
                        echo "<div class='comment-text'>{$row['comment']}</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No comments found.</p>";
                }
                $comments_stmt->close();
            ?>
        </div>

    </div>
</body>
</html>
