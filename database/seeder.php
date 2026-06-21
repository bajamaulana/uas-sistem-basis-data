<?php
/**
 * Database Seeder for Ngopidea
 * Generates dummy data to fulfill the "Minimal 100 data" requirement
 */
require_once '../includes/db.php';

echo "Starting data seeding...\n";

// 1. Seed Customers (10 rows)
$names = ['Budi Santoso', 'Siti Aminah', 'Andi Darmawan', 'Rina Marlina', 'Dewi Lestari', 'Agus Setiawan', 'Ayu Wandira', 'Fajar Sidik', 'Eko Prasetyo', 'Nurul Hidayah'];
for ($i = 0; $i < 10; $i++) {
    $email = strtolower(str_replace(' ', '.', $names[$i])) . rand(1,99) . "@example.com";
    $phone = '0812' . rand(10000000, 99999999);
    $hash = password_hash('password123', PASSWORD_DEFAULT);
    
    // Check if email exists
    $chk = $conn->query("SELECT id FROM users WHERE email = '$email'");
    if($chk->num_rows == 0){
        $conn->query("INSERT INTO users (email, password, role_id) VALUES ('$email', '$hash', 1)");
        $user_id = $conn->insert_id;
        $conn->query("INSERT INTO customers (user_id, full_name, phone, address, total_points) VALUES ($user_id, '{$names[$i]}', '$phone', 'Jl. Sudirman No. $i', ".rand(0, 500).")");
        echo "Inserted Customer: {$names[$i]}\n";
    }
}

// 2. Seed Orders & Order Details (40 orders, approx 80 details = 120 rows)
$customers = $conn->query("SELECT id FROM customers")->fetch_all(MYSQLI_ASSOC);
$products = $conn->query("SELECT id, price FROM products")->fetch_all(MYSQLI_ASSOC);
$employees = $conn->query("SELECT id FROM employees")->fetch_all(MYSQLI_ASSOC);
$payment_methods = $conn->query("SELECT id FROM payment_methods")->fetch_all(MYSQLI_ASSOC);

if (count($customers) > 0 && count($products) > 0 && count($payment_methods) > 0 && count($employees) > 0) {
    for ($i = 0; $i < 40; $i++) {
        $cust_id = $customers[array_rand($customers)]['id'];
        $emp_id = $employees[array_rand($employees)]['id'];
        $pay_id = $payment_methods[array_rand($payment_methods)]['id'];
        $status_arr = ['Completed', 'Completed', 'Completed', 'Pending', 'Cancelled'];
        $status = $status_arr[array_rand($status_arr)];
        
        // Random date within the last 30 days
        $days_ago = rand(1, 30);
        $order_date = date('Y-m-d H:i:s', strtotime("-$days_ago days"));
        
        // Random total amount for now
        $total_amount = 0;
        
        $conn->query("INSERT INTO orders (customer_id, employee_id, payment_method_id, order_date, total_amount, status) 
                      VALUES ($cust_id, $emp_id, $pay_id, '$order_date', 0, '$status')");
        $order_id = $conn->insert_id;
        
        $num_items = rand(1, 4);
        for ($j = 0; $j < $num_items; $j++) {
            $prod = $products[array_rand($products)];
            $qty = rand(1, 3);
            $sub = $prod['price'] * $qty;
            $total_amount += $sub;
            
            $conn->query("INSERT INTO order_details (order_id, product_id, quantity, unit_price, subtotal) 
                          VALUES ($order_id, {$prod['id']}, $qty, {$prod['price']}, $sub)");
        }
        
        $tax = $total_amount * 0.10;
        $grand_total = $total_amount + $tax;
        
        $conn->query("UPDATE orders SET total_amount = $grand_total WHERE id = $order_id");
    }
    echo "Inserted 40 Orders and their details.\n";
}

// 3. Seed Inventory Transactions (20 rows)
$ingredients = $conn->query("SELECT id FROM ingredients")->fetch_all(MYSQLI_ASSOC);
if (count($ingredients) > 0) {
    for ($i = 0; $i < 20; $i++) {
        $ing_id = $ingredients[array_rand($ingredients)]['id'];
        $qty = rand(5, 20);
        $types = ['In', 'Out'];
        $type = $types[array_rand($types)];
        $conn->query("INSERT INTO inventory_transactions (ingredient_id, transaction_type, quantity, remarks) VALUES ($ing_id, '$type', $qty, 'Seeder $type')");
    }
    echo "Inserted 20 Inventory Transactions.\n";
}

echo "Seeding completed successfully!\n";
?>
