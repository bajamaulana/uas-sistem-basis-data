<?php
require 'includes/db.php';

$default_categories = [
    'Espresso Based',
    'Manual Brew',
    'Non-Coffee',
    'Pastries',
    'Signature Drinks'
];

foreach ($default_categories as $cat) {
    $stmt = $conn->prepare("SELECT id FROM categories WHERE category_name = ?");
    $stmt->bind_param("s", $cat);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        $insert = $conn->prepare("INSERT INTO categories (category_name, description) VALUES (?, '')");
        $insert->bind_param("s", $cat);
        $insert->execute();
        echo "Inserted category: $cat\n";
    } else {
        echo "Category already exists: $cat\n";
    }
}
