<?php
require_once 'includes/auth.php'; // auth.php handles session_start() and requires db.php

// Determine the page to redirect to
$redirect_to = $_POST['redirect_to'] ?? $_SERVER['HTTP_REFERER'] ?? 'index.php';
// Remove any existing query parameters to avoid stacking ?subscribed=...
$redirect_to = strtok($redirect_to, '?');

// Basic fallback if empty
if (empty(trim($redirect_to))) {
    $redirect_to = 'index.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $user_id = getUserId(); // Returns user ID if logged in, or null
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Insert or update user_id if already subscribed
        $stmt = $conn->prepare("INSERT INTO subscriptions (email, user_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE user_id = VALUES(user_id)");
        $stmt->bind_param("si", $email, $user_id);
        
        if ($stmt->execute()) {
            // Success
            header("Location: {$redirect_to}?subscribed=1#newsletter");
            exit;
        } else {
            // Error
            header("Location: {$redirect_to}?subscribed=0#newsletter");
            exit;
        }
    }
}

// Invalid email or direct access
header("Location: {$redirect_to}?subscribed=0#newsletter");
exit;
