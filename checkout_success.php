<?php
require_once 'includes/auth.php';

// Redirect to signin if not logged in
if (!isLoggedIn()) {
    header("Location: signin.php");
    exit();
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id === 0) {
    header("Location: menu.php");
    exit();
}
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Order Success | Ngopidea</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Merriweather:wght@400;700&family=Plus+Jakarta+Sans:wght@400;600&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              surface: "#fff8f5",
              "surface-container-low": "#fff1e9",
              "on-surface": "#231a13",
              "on-surface-variant": "#584236",
              primary: "#9a4600",
              "on-primary": "#ffffff",
              "primary-container": "#ff790b",
              "outline-variant": "#e0c0b0"
            },
            fontFamily: {
              "body-md": ["Merriweather"],
              "label-md": ["Plus Jakarta Sans"],
              "headline-sm": ["Playfair Display"],
              "headline-md": ["Playfair Display"]
            }
          }
        }
      }
    </script>
    <style>
      .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        display: inline-block;
        vertical-align: middle;
      }
    </style>
</head>
<body class="bg-surface text-on-surface font-body-md min-h-screen flex flex-col items-center justify-center p-6">
    
    <div class="max-w-md w-full bg-white rounded-3xl p-10 text-center shadow-[0_20px_50px_rgba(154,70,0,0.1)] border border-outline-variant/30">
        <div class="w-24 h-24 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6">
            <span class="material-symbols-outlined text-[48px] text-primary">check_circle</span>
        </div>
        
        <h1 class="font-headline-md text-3xl mb-2">Order Confirmed!</h1>
        <p class="text-on-surface-variant mb-8">Thank you for your order, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Customer') ?>. We're preparing your artisanal coffee right now.</p>
        
        <div class="bg-surface-container-low rounded-xl p-6 mb-8 border border-outline-variant/50 text-left">
            <p class="font-label-md text-on-surface-variant uppercase tracking-wider text-xs mb-1">Order Number</p>
            <p class="font-headline-sm text-primary mb-4">#<?= str_pad($order_id, 5, '0', STR_PAD_LEFT) ?></p>
            
            <p class="font-label-md text-on-surface-variant uppercase tracking-wider text-xs mb-1">Estimated Pickup</p>
            <p class="font-headline-sm text-on-surface text-xl">In 15 - 20 Mins</p>
        </div>
        
        <a href="menu.php" class="inline-flex w-full items-center justify-center py-4 bg-primary text-on-primary rounded-full font-label-md shadow-[0_10px_20px_rgba(154,70,0,0.2)] hover:-translate-y-1 transition-transform">
            Back to Menu
        </a>
    </div>

</body>
</html>
