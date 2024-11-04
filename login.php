<?php
session_start();
include 'config.php';

$error_message = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("
        SELECT users.username, users.password, users.usertype, userdetails.name 
        FROM users
        LEFT JOIN userdetails ON users.username = userdetails.username 
        WHERE users.username = ?
    ");

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        
        if ($password == $user['password']) {
            $_SESSION['user'] = $user;

           
            if ($user['usertype'] == "Management Assistant") {
                header('Location: approval.php');
                exit; 
            }
            if ($user['usertype'] == "Staff") {
                header('Location: staffView.php');
                exit;
            }
            if ($user['usertype'] == "Head") {
                header('Location: dashboardDCSHead.php');
                exit;
            }
        } else {
            $_SESSION['error_message'] = 'Incorrect password. Please try again.';
            header('Location: index.php');
            exit;
        }
    } else {
        $_SESSION['error_message'] = 'Username not found. Please check your username.';
        header('Location: index.php');
        exit;
    }
}
?>
