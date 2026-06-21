<!DOCTYPE html><html lang="en" style=""><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Ngopidea Admin Dashboard</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&amp;family=Merriweather:wght@300;400;700&amp;family=Plus+Jakarta+Sans:wght@400;600;700&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@100..900&amp;family=Playfair+Display:wght@100..900&amp;family=Plus+Jakarta+Sans:wght@100..900&amp;display=swap" rel="stylesheet">
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #fff8f5;
        }
        ::-webkit-scrollbar-thumb {
            background: #e0c0b0;
            border-radius: 10px;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(224, 192, 176, 0.2);
        }
    </style>
<script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              "colors": {
                      "inverse-surface": "#392e26",
                      "on-primary-container": "#5d2700",
                      "on-tertiary": "#ffffff",
                      "surface-container-lowest": "#ffffff",
                      "on-error": "#ffffff",
                      "on-error-container": "#93000a",
                      "surface-container-highest": "#f2dfd3",
                      "primary-fixed": "#ffdbc9",
                      "on-surface": "#231a13",
                      "on-secondary-fixed-variant": "#604016",
                      "tertiary": "#4d6073",
                      "surface-tint": "#9a4600",
                      "on-tertiary-fixed-variant": "#36495a",
                      "secondary-fixed-dim": "#edbe89",
                      "secondary-fixed": "#ffddb9",
                      "on-surface-variant": "#584236",
                      "background": "#fff8f5",
                      "surface-container-low": "#fff1e9",
                      "outline-variant": "#e0c0b0",
                      "surface": "#fff8f5",
                      "surface-container-high": "#f7e5d9",
                      "surface-container": "#fdeade",
                      "on-primary-fixed": "#321200",
                      "on-primary": "#ffffff",
                      "error": "#ba1a1a",
                      "primary": "#9a4600",
                      "tertiary-fixed": "#d1e5fb",
                      "primary-fixed-dim": "#ffb68d",
                      "on-tertiary-container": "#263849",
                      "surface-bright": "#fff8f5",
                      "surface-dim": "#e9d7cb",
                      "inverse-primary": "#ffb68d",
                      "on-secondary": "#ffffff",
                      "tertiary-container": "#8ea2b6",
                      "on-background": "#231a13",
                      "surface-variant": "#f2dfd3",
                      "outline": "#8c7264",
                      "on-secondary-fixed": "#2b1700",
                      "on-tertiary-fixed": "#081d2d",
                      "on-secondary-container": "#7a562a",
                      "tertiary-fixed-dim": "#b5c9de",
                      "error-container": "#ffdad6",
                      "secondary": "#7b572b",
                      "inverse-on-surface": "#ffede3",
                      "primary-container": "#ff790b",
                      "secondary-container": "#ffcf99",
                      "on-primary-fixed-variant": "#763300"
              },
              "borderRadius": {
                      "DEFAULT": "0.25rem",
                      "lg": "0.5rem",
                      "xl": "0.75rem",
                      "full": "9999px"
              },
              "spacing": {
                      "section-sm": "40px",
                      "container-max-lg": "1200px",
                      "gutter": "20px",
                      "unit": "8px",
                      "section-md": "80px",
                      "container-max-md": "1100px",
                      "section-lg": "100px"
              },
              "fontFamily": {
                      "display-hero": ["Playfair Display"],
                      "headline-lg": ["Playfair Display"],
                      "headline-md": ["Playfair Display"],
                      "headline-sm": ["Playfair Display"],
                      "body-lg": ["Merriweather"],
                      "body-md": ["Merriweather"],
                      "label-md": ["Plus Jakarta Sans"]
              },
              "fontSize": {
                      "headline-lg": ["56px", {"lineHeight": "1.2", "letterSpacing": "-1px", "fontWeight": "700"}],
                      "headline-md": ["40px", {"lineHeight": "1.3", "fontWeight": "700"}],
                      "headline-sm": ["24px", {"lineHeight": "1.4", "fontWeight": "700"}],
                      "body-lg": ["18px", {"lineHeight": "1.8", "fontWeight": "400"}],
                      "body-md": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}],
                      "label-md": ["14px", {"lineHeight": "1.2", "fontWeight": "600"}]
              }
            },
          },
        }
      </script>
</head>
<body class="bg-background text-on-surface font-body-md min-h-screen">
<!-- Sidebar Navigation Shell -->
<aside class="h-screen w-64 fixed left-0 top-0 bg-surface-container dark:bg-surface-container-low shadow-sm flex flex-col p-4 space-y-2 z-50">
<div class="mb-8 px-4 py-2">
<h1 class="font-headline-sm text-headline-sm font-bold text-primary dark:text-primary-fixed-dim">Ngopidea</h1>
<p class="font-label-md text-label-md text-on-surface-variant opacity-70">Artisanal Cafe Admin</p>
</div>
<nav class="flex-grow space-y-1">
<!-- Active: Dashboard -->
<a class="flex items-center gap-3 px-4 py-3 bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary rounded-lg transition-all scale-95 duration-150 active:scale-90" href="#">
<span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
<span class="font-label-md">Dashboard</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant dark:text-on-surface hover:bg-surface-container-high dark:hover:bg-surface-container-highest rounded-lg transition-all hover:text-primary" href="#">
<span class="material-symbols-outlined" data-icon="shopping_bag">shopping_bag</span>
<span class="font-label-md">Orders</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant dark:text-on-surface hover:bg-surface-container-high dark:hover:bg-surface-container-highest rounded-lg transition-all hover:text-primary" href="#">
<span class="material-symbols-outlined" data-icon="restaurant_menu">restaurant_menu</span>
<span class="font-label-md">Menu Management</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant dark:text-on-surface hover:bg-surface-container-high dark:hover:bg-surface-container-highest rounded-lg transition-all hover:text-primary" href="#">
<span class="material-symbols-outlined" data-icon="inventory_2">inventory_2</span>
<span class="font-label-md">Inventory</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant dark:text-on-surface hover:bg-surface-container-high dark:hover:bg-surface-container-highest rounded-lg transition-all hover:text-primary" href="#">
<span class="material-symbols-outlined" data-icon="group">group</span>
<span class="font-label-md">Staff</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant dark:text-on-surface hover:bg-surface-container-high dark:hover:bg-surface-container-highest rounded-lg transition-all hover:text-primary" href="#">
<span class="material-symbols-outlined" data-icon="event_seat">event_seat</span>
<span class="font-label-md">Table Reservations</span>
</a></nav>
<div class="mt-auto border-t border-outline-variant/20 pt-4 space-y-1">

<a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant dark:text-on-surface hover:bg-surface-container-high dark:hover:bg-surface-container-highest rounded-lg transition-all hover:text-primary" href="#">
<span class="material-symbols-outlined" data-icon="settings">settings</span>
<span class="font-label-md">Settings</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant dark:text-on-surface hover:bg-surface-container-high dark:hover:bg-surface-container-highest rounded-lg transition-all hover:text-primary" href="logout.php">
<span class="material-symbols-outlined" data-icon="logout">logout</span>
<span class="font-label-md">Logout</span>
</a>
</div>
</aside>
<!-- Main Wrapper -->
<div class="ml-64 min-h-screen flex flex-col">
<!-- Top Navigation Bar -->
<header class="fixed top-0 right-0 w-[calc(100%-16rem)] z-40 bg-surface/95 dark:bg-surface-dim/95 backdrop-blur-md flex items-center h-16 px-8 shadow-sm border-b border-outline-variant/30 justify-end">

<div class="flex items-center gap-6">
<button class="relative text-on-surface-variant hover:text-primary transition-colors">
<span class="material-symbols-outlined">notifications</span>
<span class="absolute top-0 right-0 w-2 h-2 bg-primary rounded-full border-2 border-surface"></span>
</button>
<div class="flex items-center gap-3 pl-4 border-l border-outline-variant/30">
<div class="text-right">
<p class="font-label-md text-on-surface">Staff Profile</p>
<p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Store Manager</p>
</div>
<img class="w-10 h-10 rounded-full object-cover border-2 border-primary-fixed shadow-sm" data-alt="A professional studio portrait of a cafe manager with a warm and welcoming expression. The background is a blurred high-end coffee shop interior with soft golden lighting and rich wooden textures. The aesthetic is clean and modern, matching a luxury artisanal brand." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAZlVDS0zWYeeHCBEkQZTED4pbtTPxw7ePQtTG8Aq14g3AS60CX3HF7cM4AB7VXvphm6CTEpgKniazy5LA9ZtAbMJrnAw-pSC5hyHUeUgm0hUg3DI7cB6O3lHRcXG9beBTcC4DgmsQfFlnp7HrQA9lirbTtbsxf1TDk1O1dJNfutcoBibCxvK1gZT-PDR12fzJYqrRR_MD0LDNbt4gUa75sUcMwbU12YF9bLHfbQSwjSsrwkciXjIToInPeI68thC97oVstk9uO_g8">
</div>
</div>
</header>
<!-- Content Canvas -->
<main class="mt-16 p-8 flex-grow">
<!-- Header Section -->
<section class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
<div>
<h2 class="font-headline-md text-headline-md text-on-surface">Dashboard Overview</h2>
<p class="text-on-surface-variant opacity-70 font-body-md">Your artisanal performance at a glance.</p>
</div>

</section>
<!-- Metrics Grid -->
<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
<!-- Total Revenue -->
<div class="glass-card p-6 rounded-2xl shadow-sm hover:shadow-lg transition-all group" style="transform: translateY(0px);">
<div class="flex justify-between items-start mb-4">
<div class="p-3 bg-primary/10 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-colors">
<span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1;">payments</span>
</div>
<span class="flex items-center text-green-600 font-label-md bg-green-50 px-2 py-1 rounded-lg">
<span class="material-symbols-outlined text-sm">arrow_upward</span>
                            12%
                        </span>
</div>
<p class="text-on-surface-variant font-label-md mb-1">Total Revenue</p>
<h3 class="text-headline-sm font-headline-sm">$12,840</h3>
</div>
<!-- Total Transactions -->
<div class="glass-card p-6 rounded-2xl shadow-sm hover:shadow-lg transition-all group" style="transform: translateY(0px);">
<div class="flex justify-between items-start mb-4">
<div class="p-3 bg-secondary/10 rounded-xl text-secondary group-hover:bg-secondary group-hover:text-white transition-colors">
<span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1;">receipt_long</span>
</div>
<span class="flex items-center text-green-600 font-label-md bg-green-50 px-2 py-1 rounded-lg">
<span class="material-symbols-outlined text-sm">arrow_upward</span>
                            5%
                        </span>
</div>
<p class="text-on-surface-variant font-label-md mb-1">Total Transactions</p>
<h3 class="text-headline-sm font-headline-sm">1,420</h3>
</div>
<!-- Avg Order Value -->
<div class="glass-card p-6 rounded-2xl shadow-sm hover:shadow-lg transition-all group" style="transform: translateY(0px);">
<div class="flex justify-between items-start mb-4">
<div class="p-3 bg-tertiary/10 rounded-xl text-tertiary group-hover:bg-tertiary group-hover:text-white transition-colors">
<span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1;">analytics</span>
</div>
</div>
<p class="text-on-surface-variant font-label-md mb-1">Avg. Order Value</p>
<h3 class="text-headline-sm font-headline-sm">$9.04</h3>
</div>
<!-- Active Subscriptions -->
<div class="glass-card p-6 rounded-2xl shadow-sm hover:shadow-lg transition-all group" style="transform: translateY(0px);">
<div class="flex justify-between items-start mb-4">
<div class="p-3 bg-primary-fixed/30 rounded-xl text-on-primary-fixed-variant group-hover:bg-on-primary-fixed-variant group-hover:text-white transition-colors">
<span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1;">loyalty</span>
</div>
</div>
<p class="text-on-surface-variant font-label-md mb-1">Active Subscriptions</p>
<h3 class="text-headline-sm font-headline-sm">156</h3>
</div>
</section>
<!-- Main Data Sections: Bento Style -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
<!-- Daily Revenue Chart Placeholder -->
<div class="lg:col-span-2 glass-card rounded-2xl p-8 flex flex-col relative overflow-hidden" style="transform: translateY(0px);">
<div class="flex justify-between items-center mb-8">
<h4 class="font-headline-sm text-on-surface">Daily Revenue Trend</h4>
<div class="flex gap-2">
<button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-surface-container transition-colors"><span class="material-symbols-outlined text-sm">more_horiz</span></button>
</div>
</div>
<div class="flex-grow flex items-end justify-between gap-2 h-64">
<!-- Abstract "Chart" using CSS Bars -->
<div class="w-full bg-primary-container/20 rounded-t-lg transition-all hover:bg-primary-container h-[40%]"></div>
<div class="w-full bg-primary-container/20 rounded-t-lg transition-all hover:bg-primary-container h-[65%]"></div>
<div class="w-full bg-primary-container/20 rounded-t-lg transition-all hover:bg-primary-container h-[55%]"></div>
<div class="w-full bg-primary-container/20 rounded-t-lg transition-all hover:bg-primary-container h-[85%]"></div>
<div class="w-full bg-primary-container/20 rounded-t-lg transition-all hover:bg-primary-container h-[60%]"></div>
<div class="w-full bg-primary-container/20 rounded-t-lg transition-all hover:bg-primary-container h-[70%]"></div>
<div class="w-full bg-primary-container/30 border-t-2 border-primary rounded-t-lg transition-all hover:bg-primary h-[90%]"></div>
</div>
<div class="flex justify-between mt-4 text-[12px] uppercase tracking-widest text-on-surface-variant/50 font-label-md">
<span class="">Mon</span><span class="">Tue</span><span class="">Wed</span><span class="">Thu</span><span class="">Fri</span><span class="">Sat</span><span class="">Sun</span>
</div>
<!-- Decorative Element -->

</div>
<!-- Best Selling Brews -->
<div class="glass-card rounded-2xl p-8" style="transform: translateY(0px);">
<h4 class="font-headline-sm text-on-surface mb-6">Best Selling Brews</h4>
<ul class="space-y-6">
<li class="flex items-center gap-4">
<div class="w-12 h-12 rounded-xl bg-surface-container overflow-hidden flex-shrink-0">
<img class="w-full h-full object-cover" data-alt="A top-down view of a beautifully crafted Velvet Flat White with intricate latte art. The cup sits on a dark wooden table in a high-contrast lighting setup, reflecting the warmth and artisanal quality of the cafe." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCQq21Gq3LP6oqs6_UJWBBjPltI1S6k2BP8HoGnZRZ470SeoAXCP11PoImI5v4xY_tXoZ1zN094VHOgzAoYF2Nm4D7P5FR_ii06iWQZNsfFHu3Ygc4THR4r0jfUZWN_dwkoydWejaXRJD4G-Y0GWjZRDcoDmYiGo-DURvDdDUgeAsmbEKCiv-RnduoTrFG0A6VLbkPLMJxCKlh2fZeN2n69X1khxilF6nZnQv3e8YdhPOb_DbKLw9nUVNAYiVJde9N7qsZ1YjmUhN4">
</div>
<div class="flex-grow">
<p class="font-label-md text-on-surface">Velvet Flat White</p>
<p class="text-[12px] text-on-surface-variant">Signature Espresso</p>
</div>
<div class="text-right">
<p class="font-bold text-primary">420</p>
<p class="text-[10px] text-on-surface-variant">Sold</p>
</div>
</li>
<li class="flex items-center gap-4">
<div class="w-12 h-12 rounded-xl bg-surface-container overflow-hidden flex-shrink-0">
<img class="w-full h-full object-cover" data-alt="A crystal clear pour-over coffee presentation using an Artisanal V60 dripper. The brew is a rich amber color, lit from behind to highlight the clarity of the coffee. The setting is minimal and focused on the craft of brewing." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBHIWc_5ibzWxvxK9VGnm8moW_Y1K4xhrfu1t7MALdxcV-Bs9xiz9cL4h-zsiDZbV59qDGAuXM-ndt7lI5AglMFtxPyio42-hCMvSF2I9HtcI4qkfemIgyp4jys-_6gA2pvx0nmQPn0rBPUivAACygj-9ZUqTDoPg3NFzu8iSaAKZY27cVXEMqIBKzMwXwxVDt8k3RPuvQRPfl2GK5SbJHlBWToEDoaqwRV-to-oxI-6KZgSSGk4_cvjAUi3qoiwUUoIHcvR6KWF9c">
</div>
<div class="flex-grow">
<p class="font-label-md text-on-surface">Artisanal V60</p>
<p class="text-[12px] text-on-surface-variant">Single Origin</p>
</div>
<div class="text-right">
<p class="font-bold text-primary">315</p>
<p class="text-[10px] text-on-surface-variant">Sold</p>
</div>
</li>
<li class="flex items-center gap-4">
<div class="w-12 h-12 rounded-xl bg-surface-container overflow-hidden flex-shrink-0">
<img class="w-full h-full object-cover" data-alt="A refreshing cold brew coffee served in a sleek, condensation-covered glass bottle. The liquid is dark and intense, with a clean aesthetic that suggests premium quality and careful preparation." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBtzfBt9kLyiqAVxeUNMjgtv8pWyliEz8SeIPqLOWhTUUhvI-7itdJ_IDoTmH9K098gs2aNQgRmNQUAuKIKxpACc2f9Gh-nD_PanOrYDYnWFMHLpBDeY2e631ZAHwk_IaPBIWUHqqGomC_sjILGxCkcrWwTs4-uJHv6FlEOsMTL26fU10757n6ZwWV-JCjldhBzs95Rxtw0PoqRm5g5qlZCnrvRCBjxAplc8-XGs3TFsL2Uvptxde9p_vMEMJEzclOkmPcNgkDOHHQ">
</div>
<div class="flex-grow">
<p class="font-label-md text-on-surface">Midnight Cold Brew</p>
<p class="text-[12px] text-on-surface-variant">Slow Steeped</p>
</div>
<div class="text-right">
<p class="font-bold text-primary">289</p>
<p class="text-[10px] text-on-surface-variant">Sold</p>
</div>
</li>
</ul>

</div>
</div>
<!-- Recent Transactions Table -->
<section class="glass-card rounded-2xl overflow-hidden mb-10 shadow-sm" style="transform: translateY(0px);">
<div class="px-8 py-6 border-b border-outline-variant/20 flex justify-between items-center">
<h4 class="font-headline-sm text-on-surface">Recent Transactions</h4>
<button class="text-primary font-label-md hover:underline">View All Records</button>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left">
<thead>
<tr class="bg-surface-container-low text-on-surface-variant text-[11px] uppercase tracking-[2px] font-label-md">
<th class="px-8 py-4">Order ID</th>
<th class="px-8 py-4">Customer</th>
<th class="px-8 py-4">Date</th>
<th class="px-8 py-4">Status</th>
<th class="px-8 py-4 text-right">Amount</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/10">
<tr class="hover:bg-surface-container/50 transition-colors">
<td class="px-8 py-5 font-label-md text-on-surface">#ORD-2024-912</td>
<td class="px-8 py-5">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-secondary-container flex items-center justify-center text-[10px] font-bold text-on-secondary-container">JS</div>
<span class="text-on-surface font-label-md">James Smith</span>
</div>
</td>
<td class="px-8 py-5 text-on-surface-variant text-sm">Oct 24, 2023, 10:45 AM</td>
<td class="px-8 py-5">
<span class="status-badge bg-green-100 text-green-700">Completed</span>
</td>
<td class="px-8 py-5 text-right font-bold text-on-surface">$12.45</td>
</tr>
<tr class="hover:bg-surface-container/50 transition-colors">
<td class="px-8 py-5 font-label-md text-on-surface">#ORD-2024-911</td>
<td class="px-8 py-5">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-primary-fixed flex items-center justify-center text-[10px] font-bold text-on-primary-fixed">MW</div>
<span class="text-on-surface font-label-md">Mia Wong</span>
</div>
</td>
<td class="px-8 py-5 text-on-surface-variant text-sm">Oct 24, 2023, 10:30 AM</td>
<td class="px-8 py-5">
<span class="status-badge bg-amber-100 text-amber-700">In Progress</span>
</td>
<td class="px-8 py-5 text-right font-bold text-on-surface">$8.20</td>
</tr>
<tr class="hover:bg-surface-container/50 transition-colors">
<td class="px-8 py-5 font-label-md text-on-surface">#ORD-2024-910</td>
<td class="px-8 py-5">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-tertiary-fixed flex items-center justify-center text-[10px] font-bold text-on-tertiary-fixed">RB</div>
<span class="text-on-surface font-label-md">Robert Brown</span>
</div>
</td>
<td class="px-8 py-5 text-on-surface-variant text-sm">Oct 24, 2023, 09:15 AM</td>
<td class="px-8 py-5">
<span class="status-badge bg-red-100 text-red-700">Cancelled</span>
</td>
<td class="px-8 py-5 text-right font-bold text-on-surface">$24.50</td>
</tr>
<tr class="hover:bg-surface-container/50 transition-colors">
<td class="px-8 py-5 font-label-md text-on-surface">#ORD-2024-909</td>
<td class="px-8 py-5">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center text-[10px] font-bold text-on-surface">AL</div>
<span class="text-on-surface font-label-md">Anna Lee</span>
</div>
</td>
<td class="px-8 py-5 text-on-surface-variant text-sm">Oct 23, 2023, 05:40 PM</td>
<td class="px-8 py-5">
<span class="status-badge bg-green-100 text-green-700">Completed</span>
</td>
<td class="px-8 py-5 text-right font-bold text-on-surface">$15.00</td>
</tr>
</tbody>
</table>
</div>
</section>
</main>
<!-- Footer -->
<footer class="bg-surface-container-low p-8 border-t border-outline-variant/20">
<div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
<p class="text-on-surface-variant text-sm">© 2024 Ngopidea Artisanal Cafe. All rights reserved.</p>
<div class="flex gap-6">
<a class="text-on-surface-variant hover:text-primary transition-colors text-sm font-label-md" href="#">Privacy Policy</a>
<a class="text-on-surface-variant hover:text-primary transition-colors text-sm font-label-md" href="#">Terms of Service</a>
<a class="text-on-surface-variant hover:text-primary transition-colors text-sm font-label-md" href="#">Help Center</a>
</div>
</div>
</footer>
</div>
<script>
        // Simple micro-interactions
        document.querySelectorAll('.glass-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-4px)';
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
            });
        });

        // Search bar focus effect
        const searchInput = document.querySelector('input[type="text"]');
        searchInput.addEventListener('focus', () => {
            searchInput.parentElement.classList.add('ring-2', 'ring-primary/20');
        });
        searchInput.addEventListener('blur', () => {
            searchInput.parentElement.classList.remove('ring-2', 'ring-primary/20');
        });
    </script>


</body></html>