<?php
require_once 'config.php';

// Handle Admin Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // Check if user exists
    if (array_key_exists($username, $admin_users)) {
        // Verify password (in a real app, this would check against database)
        if (password_verify($password, $admin_users[$username]['password'])) {
            // Set session variables
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            $_SESSION['admin_name'] = $admin_users[$username]['name'];
            $_SESSION['admin_email'] = $admin_users[$username]['email'];
            
            // Redirect to dashboard
            header('Location: ' . SITE_URL . '/admin/dashboard.php');
            exit;
        }
    }
    
    // If login fails
    $_SESSION['login_error'] = 'Invalid username or password';
    header('Location: ' . SITE_URL . '/admin/login.html');
    exit;
}

// Handle Admin Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . SITE_URL . '/admin/login.html');
    exit;
}

// Check if user is logged in for protected pages
function checkAdminLogin() {
    if (!isset($_SESSION['admin_logged_in']) {
        header('Location: ' . SITE_URL . '/admin/login.html');
        exit;
    }
}
?>