<?php
session_start();
$error_message = '';

if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']); // Clear the error message after displaying it
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examination Claim System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Background Gradient */
        body {
            background-image: url('bg02.jpg');
            background-size: cover; 
            background-repeat: no-repeat; 
            background-position: center; 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        
        /* Card Styling */
        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            max-width: 480px;
            width: 100%;
        }
        .login-container img {
            display: block;
            margin: 0 auto 20px;
            width: 200px;
            height: auto;
        }
        /* Header Styling */
        .login-container h2 {
            color: #000DFF;
            margin-bottom: 30px;
            font-size: 27px;
            font-weight: bold;
            text-align: center;
        }

        /* Button Styling */
        .btn-primary {
            background-color: #000DFF;
            border: none;
        }

        .btn-primary:hover {
            background-color: #6B73FF;
        }

        /* Input Field Styling */
        .form-control {
            border-radius: 10px;
            border: 2px solid #6B73FF;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #000DFF;
        }
        
        /* Placeholder Styling */
        ::placeholder {
            color: #999;
        }
    </style>
</head>
<body>
    <div class="login-container">
        
        <h2>Examination Claim System</h2>
        <img src="University Logo.png" alt="University">

        <form action="login.php" method="POST">



        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
            <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

 
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block w-100">Login</button>
            <a href="#" class="d-block mt-5 text-center">Change password</a>
        
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>

