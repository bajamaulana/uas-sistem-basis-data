<?php
require_once 'includes/auth.php';

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    
    if ($product_id > 0) {
        if ($action === 'add') {
            $qty = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id] += $qty;
            } else {
                $_SESSION['cart'][$product_id] = $qty;
            }
        } elseif ($action === 'update') {
            $qty = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
            if ($qty > 0) {
                $_SESSION['cart'][$product_id] = $qty;
            } else {
                unset($_SESSION['cart'][$product_id]);
            }
        } elseif ($action === 'remove') {
            if (isset($_SESSION['cart'][$product_id])) {
                unset($_SESSION['cart'][$product_id]);
            }
        }
    }

    if (isset($_POST['ajax']) && $_POST['ajax'] === '1') {
        $cart_count = 0;
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $qty) {
                $cart_count += $qty;
            }
        }
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'cart_count' => $cart_count]);
        exit();
    }
}

// Redirect back to the referring page, default to menu.php
$redirect = $_SERVER['HTTP_REFERER'] ?? 'menu.php';
header("Location: " . $redirect);
exit();
?>
