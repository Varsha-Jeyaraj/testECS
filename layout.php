<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Dashboard'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            overflow-x: hidden; /* Prevent horizontal scroll */
        }
        .offcanvas-header {
            background-color: #343a40; /* Dark background for sidebar */
            color: #fff; /* Text color */
        }
        .offcanvas-body {
            background-color: #f8f9fa; /* Light background for sidebar */
        }
        .offcanvas-body a {
            color: #343a40; /* Link color */
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .offcanvas-body a:hover {
            background-color: #e2e6ea; /* Light hover effect */
        }
        .header {
            background-color: #007bff; /* Bootstrap primary color */
            color: #fff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }
        .content {
            padding: 20px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="offcanvas offcanvas-start show" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel" style="visibility: visible;">
        <div class="offcanvas-header">
            <h5 id="sidebarLabel">Welcome, <?php echo htmlspecialchars($_SESSION['user']['role']); ?></h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <a href="form.php" class="list-group-item list-group-item-action">Fill Form</a>
            <a href="payment.php" class="list-group-item list-group-item-action">View Payment Details</a>
            <a href="summary.php" class="list-group-item list-group-item-action">View Summary</a>
            <a href="approved.php" class="list-group-item list-group-item-action">Check Approved Sheet</a>
            <a href="logout.php" class="list-group-item list-group-item-action">Logout</a>
        </div>
    </div>

    <div class="content">
        <?php include $content; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
