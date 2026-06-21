<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $promo_code = isset($_POST['promo_code']) ? trim($_POST['promo_code']) : '';
    $subtotal = isset($_POST['subtotal']) ? floatval($_POST['subtotal']) : 0;
    
    if (empty($promo_code)) {
        echo json_encode(['status' => 'error', 'message' => 'Please enter a promo code']);
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM promotions WHERE promo_code = ? AND start_date <= CURDATE() AND end_date >= CURDATE()");
    $stmt->bind_param("s", $promo_code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $promo = $result->fetch_assoc();
        
        if ($subtotal >= $promo['min_purchase']) {
            $discount_percentage = floatval($promo['discount_percentage']);
            $discount_amount = $subtotal * ($discount_percentage / 100);
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Promo applied successfully!',
                'discount_percentage' => $discount_percentage,
                'discount_amount' => $discount_amount,
                'promotion_id' => $promo['id']
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Minimum purchase of $' . number_format($promo['min_purchase'], 2) . ' required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid or expired promo code']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
