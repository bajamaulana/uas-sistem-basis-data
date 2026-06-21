<?php
/**
 * Authentication Helper - Ngopidea Coffee
 * Handles session management and auth helper functions
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

/**
 * Check if user is currently logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get the logged-in user's name
 */
function getUserName() {
    return $_SESSION['user_name'] ?? null;
}

/**
 * Get the logged-in user's ID
 */
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

function isStaff() {
    return isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2;
}