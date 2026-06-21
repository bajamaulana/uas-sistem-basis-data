<?php 
require_once 'includes/auth.php';
require_once 'includes/db.php';

$res_message = '';
$res_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reserve') {
    if (!isset($_SESSION['user_id'])) {
        header("Location: signin.php");
        exit();
    }
    
    $user_id = $_SESSION['user_id'];
    $date = $_POST['res_date'] ?? '';
    $time = $_POST['res_time'] ?? '';
    $guests = intval($_POST['guests'] ?? 2);
    
    if (empty($date) || empty($time)) {
        $res_error = "Date and time are required.";
    } else {
        // Get customer_id
        $cust_stmt = $conn->prepare("SELECT id FROM customers WHERE user_id = ?");
        $cust_stmt->bind_param("i", $user_id);
        $cust_stmt->execute();
        $cust_res = $cust_stmt->get_result();
        
        if ($cust_res->num_rows > 0) {
            $customer_id = $cust_res->fetch_assoc()['id'];
        } else {
            $full_name = $_SESSION['user_name'] ?? 'Guest';
            $conn->query("INSERT INTO customers (user_id, full_name) VALUES ($user_id, '$full_name')");
            $customer_id = $conn->insert_id;
        }

        $reservation_time = $date . ' ' . $time . ':00';
        
        // Find a table
        $table_stmt = $conn->prepare("SELECT id FROM tables WHERE capacity >= ? AND status = 'Available' LIMIT 1");
        $table_stmt->bind_param("i", $guests);
        $table_stmt->execute();
        $table_res = $table_stmt->get_result();
        
        if ($table_res->num_rows > 0) {
            $table_id = $table_res->fetch_assoc()['id'];
        } else {
            // Create a dummy table if none exist for demo purposes
            $conn->query("INSERT INTO tables (table_number, capacity, status) VALUES ('T" . rand(10,99) . "', " . max(4, $guests) . ", 'Available')");
            $table_id = $conn->insert_id;
        }
        
        $res_stmt = $conn->prepare("INSERT INTO reservations (customer_id, table_id, reservation_time, guest_count, status) VALUES (?, ?, ?, ?, 'Pending')");
        $res_stmt->bind_param("iisi", $customer_id, $table_id, $reservation_time, $guests);
        if ($res_stmt->execute()) {
            $res_message = "Reservation confirmed for $guests guests on " . date('M j, Y h:i A', strtotime($reservation_time)) . ".";
        } else {
            $res_error = "Failed to make reservation. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Locations | Ngopidea Artisanal Cafe</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&amp;family=Plus+Jakarta+Sans:wght@600&amp;family=Merriweather:wght@400;700&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
      rel="stylesheet"
    />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link
      href="https://fonts.googleapis.com/css2?family=Merriweather:wght@100..900&amp;family=Playfair+Display:wght@100..900&amp;family=Plus+Jakarta+Sans:wght@100..900&amp;display=swap"
      rel="stylesheet"
    />
    <style>
      .material-symbols-outlined {
        font-variation-settings:
          "FILL" 0,
          "wght" 400,
          "GRAD" 0,
          "opsz" 24;
      }
      .glass-nav {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
      }
      .location-card:hover .amber-glow {
        box-shadow: 0 10px 30px rgba(154, 70, 0, 0.2);
      }
      .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
      }
      .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
      }
      .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e0c0b0;
        border-radius: 10px;
      }
    </style>
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "on-surface": "#231a13",
              "inverse-surface": "#392e26",
              "secondary-container": "#ffcf99",
              "inverse-on-surface": "#ffede3",
              "on-background": "#231a13",
              "surface-container-highest": "#f2dfd3",
              "secondary-fixed": "#ffddb9",
              error: "#ba1a1a",
              "primary-fixed": "#ffdbc9",
              "surface-container-low": "#fff1e9",
              secondary: "#7b572b",
              primary: "#9a4600",
              "surface-bright": "#fff8f5",
              "on-primary-container": "#5d2700",
              "on-primary": "#ffffff",
              "on-error": "#ffffff",
              "primary-container": "#ff790b",
              "on-secondary-container": "#7a562a",
              "on-primary-fixed-variant": "#763300",
              "tertiary-fixed-dim": "#b5c9de",
              surface: "#fff8f5",
              "secondary-fixed-dim": "#edbe89",
              "outline-variant": "#e0c0b0",
              "surface-container-high": "#f7e5d9",
              "surface-container-lowest": "#ffffff",
              "surface-tint": "#9a4600",
              "surface-dim": "#e9d7cb",
              "primary-fixed-dim": "#ffb68d",
              "on-secondary-fixed": "#2b1700",
              "on-tertiary-fixed-variant": "#36495a",
              "inverse-primary": "#ffb68d",
              "error-container": "#ffdad6",
              outline: "#8c7264",
              "on-surface-variant": "#584236",
              "on-tertiary-fixed": "#081d2d",
              "tertiary-fixed": "#d1e5fb",
              tertiary: "#4d6073",
              "surface-container": "#fdeade",
              "tertiary-container": "#8ea2b6",
              "on-error-container": "#93000a",
              "on-secondary": "#ffffff",
              background: "#fff8f5",
              "on-primary-fixed": "#321200",
              "surface-variant": "#f2dfd3",
              "on-secondary-fixed-variant": "#604016",
              "on-tertiary-container": "#263849",
              "on-tertiary": "#ffffff",
            },
            borderRadius: {
              DEFAULT: "0.25rem",
              lg: "0.5rem",
              xl: "0.75rem",
              full: "9999px",
            },
            spacing: {
              unit: "8px",
              "section-md": "80px",
              "section-sm": "40px",
              "container-max-md": "1100px",
              gutter: "20px",
              "container-max-lg": "1200px",
              "section-lg": "100px",
            },
            fontFamily: {
              "headline-lg": ["Playfair Display"],
              "display-hero-mobile": ["Playfair Display"],
              "label-md": ["Plus Jakarta Sans"],
              "body-md": ["Merriweather"],
              "headline-md": ["Playfair Display"],
              "display-hero": ["Playfair Display"],
              "headline-lg-mobile": ["Playfair Display"],
              "body-lg": ["Merriweather"],
              "headline-sm": ["Playfair Display"],
            },
            fontSize: {
              "headline-lg": ["56px", { lineHeight: "1.2", letterSpacing: "-1px", fontWeight: "700" }],
              "display-hero-mobile": ["35px", { lineHeight: "1.2", letterSpacing: "1px", fontWeight: "700" }],
              "label-md": ["14px", { lineHeight: "1.2", fontWeight: "600" }],
              "body-md": ["16px", { lineHeight: "1.6", fontWeight: "400" }],
              "headline-md": ["40px", { lineHeight: "1.3", fontWeight: "700" }],
              "display-hero": ["72px", { lineHeight: "1.2", letterSpacing: "2px", fontWeight: "700" }],
              "headline-lg-mobile": ["32px", { lineHeight: "1.2", letterSpacing: "0px", fontWeight: "700" }],
              "body-lg": ["18px", { lineHeight: "1.8", fontWeight: "400" }],
              "headline-sm": ["24px", { lineHeight: "1.4", fontWeight: "700" }],
            },
          },
        },
      };
    </script>
  </head>
  <body class="bg-background text-on-surface font-body-md selection:bg-primary-container selection:text-on-primary-container">
    <!-- Top Navigation Bar -->
    <?php include 'includes/navbar.php'; ?>
    <main class="pt-28 pb-section-lg max-w-container-max-lg mx-auto px-gutter">
      
      <?php if (!empty($res_message)): ?>
          <div class="bg-primary/20 text-on-surface border border-primary/30 p-4 rounded-xl mb-6 flex items-center gap-3">
              <span class="material-symbols-outlined text-primary">check_circle</span>
              <?= htmlspecialchars($res_message) ?>
          </div>
      <?php endif; ?>
      <?php if (!empty($res_error)): ?>
          <div class="bg-error-container text-on-error-container p-4 rounded-xl mb-6 flex items-center gap-3">
              <span class="material-symbols-outlined">error</span>
              <?= htmlspecialchars($res_error) ?>
          </div>
      <?php endif; ?>
      
      <!-- Hero Section: Map (Container Width) -->
      <section class="relative h-[450px] w-full mb-16">
        <div class="h-full w-full rounded-2xl overflow-hidden shadow-md bg-surface-container border border-outline-variant relative">
          <!-- Map Placeholder -->
          <div
            class="w-full h-full grayscale hover:grayscale-0 transition-all duration-700 bg-cover bg-center"
            data-alt="A sophisticated, minimalist map view of a metropolitan city center with custom markers in amber and gold. The map style is clean, editorial, with soft beige landmasses and muted gray streets, perfectly matching a luxury coffee brand's aesthetic. Small glowing icons indicate cafe locations within a green urban landscape."
            data-location="Jakarta, Indonesia"
            style="
              background-image: url(&quot;https://lh3.googleusercontent.com/aida-public/AB6AXuClM5yPIXa8O6DWC8SuLMkoxX5Y3NyP2o__p1tUB8L5GvANa3kft5aqfSRg2S6WArwkGsyVylKmkCsmi8cc03ETFwTtridobpdLgs_zOsa88pilLzDmWppVepQjwRH-vgsfOVXiFxqvXN6itnER54LJkXRmLduYEIOTjo_weRuGe0CwPmhH3ZUKnOYZ_c6X4vmYXqG3l17FWArlDfWf-rZL3N9984VtEsnB62eeSkhOTf9RBStQK_YTI_8JCZ5orPwKcxhAidOaXdA&quot;);
            "
          ></div>
          <!-- Floating Info -->
          <div class="absolute top-8 left-8 p-8 bg-surface/95 glass-nav border border-white/20 rounded-2xl shadow-lg max-w-sm animate-fade-in">
            <h1 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-4">Find Your Sanctuary</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">Our flagship sanctuary in Kemang. A restored colonial-era attic meticulously crafted for creative focus, artisanal espresso, and quiet contemplation.</p>
          </div>
        </div>
      </section>
    <!-- Main Locations Gallery -->
    <section class="py-section-lg px-gutter max-w-container-max-lg mx-auto">
      <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-12">
        <div class="max-w-2xl">
          <span class="font-label-md text-label-md text-secondary uppercase tracking-[4px] mb-4 block">Curated Spaces</span>
          <h2 class="font-headline-lg text-headline-lg text-on-surface">Experience Ngopidea in Every Neighborhood</h2>
        </div>
        <button class="group flex items-center gap-2 text-primary font-label-md">
          View All Details
          <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
        </button>
      </div>
      <!-- Location Detailed Cards -->
      <!-- Single Location Detailed Card -->
      <div class="flex flex-col md:flex-row gap-12 mt-8">
        <!-- Card Left: Image -->
        <div class="w-full md:w-1/2 rounded-2xl overflow-hidden shadow-lg border border-surface-container-high h-[400px]">
            <div
              class="w-full h-full bg-cover bg-center hover:scale-105 transition-transform duration-700"
              style="
                background-image: url(&quot;https://lh3.googleusercontent.com/aida-public/AB6AXuAt22iz_V-50W-0okXRSMu30O_vDM4LyOAtmh6qmcPpZYXmSIxcOjz_7jx0elITSxKRDTSOsjIcxkl_m4vTDrBD2TK5IdjCyO2L_K27smkPVoJ-CdryKTXxqiyaZ9z9mEBv-JgaTnQAjlArKLegm8-kjjf1GNnhJSlYz4Zv3zxhzqteCgOj6f5w5xn_ic-StCk9HtmsmuXSKhZi0CMVuXAGzUr6ehd4uWemgC-IP8Fyh1gqfAO0RdjIut95E0ksXN0dUJQPsum65IU&quot;);
              "
            ></div>
        </div>
        <!-- Card Right: Info -->
        <div class="w-full md:w-1/2 flex flex-col justify-center">
            <h3 class="font-headline-sm md:text-4xl text-3xl text-on-surface mb-4">The Heritage Attic</h3>
            <p class="font-body-md text-on-surface-variant mb-2">Jl. Kemang Raya No. 42, South Jakarta</p>
            <button class="flex items-center gap-2 text-primary font-label-md text-sm mb-10 hover:underline"><span class="material-symbols-outlined text-[16px]">content_copy</span> Copy Address</button>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-10">
                <div>
                    <h4 class="font-label-md text-on-surface uppercase tracking-widest text-xs mb-3">OPENING HOURS</h4>
                    <p class="font-body-md text-on-surface-variant text-sm mb-1">Weekday: 7:00 AM - 9:00 PM</p>
                    <p class="font-body-md text-on-surface-variant text-sm">Weekend: 8:00 AM - 10:00 PM</p>
                </div>
                <div>
                    <h4 class="font-label-md text-on-surface uppercase tracking-widest text-xs mb-3">CONTACT</h4>
                    <p class="font-body-md text-on-surface-variant text-sm mb-1">+62 21 555 0123</p>
                    <p class="font-body-md text-on-surface-variant text-sm mb-1">hello@ngopidea.com</p>
                    <p class="font-body-md text-on-surface-variant text-sm">@ngopidea.kemang</p>
                </div>
            </div>

            <div class="flex flex-col xl:flex-row gap-4 mt-6">
                <button
                  class="flex-1 py-4 border-2 border-primary text-primary rounded-full font-label-md hover:bg-primary hover:text-on-primary transition-all duration-300 flex justify-center items-center gap-2 uppercase tracking-wider"
                >
                  <span class="material-symbols-outlined">directions</span>
                  Get Directions
                </button>
                <button
                  onclick="openReservationModal()"
                  class="flex-1 py-4 bg-primary text-on-primary rounded-full font-label-md hover:bg-primary-container hover:text-on-primary-container transition-all duration-300 shadow-lg hover:shadow-xl flex justify-center items-center gap-2 uppercase tracking-wider"
                >
                  <span class="material-symbols-outlined">event_seat</span>
                  Reserve a Table
                </button>
            </div>
        </div>
      </div>
    </section>
    <!-- Cafe Features Section -->
    <section class="bg-surface-container-low py-section-lg">
      <div class="max-w-container-max-lg mx-auto px-gutter">
        <div class="text-center max-w-3xl mx-auto mb-16">
          <h2 class="font-headline-lg text-headline-lg text-on-surface mb-6">Built for Creators</h2>
          <p class="font-body-lg text-body-lg text-on-surface-variant">Every Ngopidea location is intentionally designed to facilitate deep work and meaningful connection.</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
          <!-- Feature 1 -->
          <div class="flex flex-col items-center text-center p-8 bg-surface rounded-2xl shadow-sm border border-outline-variant/30 hover:shadow-md transition-all">
            <div class="w-16 h-16 rounded-full bg-primary-fixed flex items-center justify-center mb-6">
              <span class="material-symbols-outlined text-primary text-3xl">wifi</span>
            </div>
            <h4 class="font-label-md text-label-md text-on-surface uppercase tracking-wider mb-2">Gigabit Wi-Fi</h4>
            <p class="text-sm text-on-surface-variant">Uninterrupted high-speed connectivity for all your creative needs.</p>
          </div>
          <!-- Feature 2 -->
          <div class="flex flex-col items-center text-center p-8 bg-surface rounded-2xl shadow-sm border border-outline-variant/30 hover:shadow-md transition-all">
            <div class="w-16 h-16 rounded-full bg-primary-fixed flex items-center justify-center mb-6">
              <span class="material-symbols-outlined text-primary text-3xl">deck</span>
            </div>
            <h4 class="font-label-md text-label-md text-on-surface uppercase tracking-wider mb-2">Outdoor Space</h4>
            <p class="text-sm text-on-surface-variant">Breathable terraces and lush garden areas for fresh ideation.</p>
          </div>
          <!-- Feature 3 -->
          <div class="flex flex-col items-center text-center p-8 bg-surface rounded-2xl shadow-sm border border-outline-variant/30 hover:shadow-md transition-all">
            <div class="w-16 h-16 rounded-full bg-primary-fixed flex items-center justify-center mb-6">
              <span class="material-symbols-outlined text-primary text-3xl">pets</span>
            </div>
            <h4 class="font-label-md text-label-md text-on-surface uppercase tracking-wider mb-2">Pet Friendly</h4>
            <p class="text-sm text-on-surface-variant">Your creative companions are always welcome in our spaces.</p>
          </div>
          <!-- Feature 4 -->
          <div class="flex flex-col items-center text-center p-8 bg-surface rounded-2xl shadow-sm border border-outline-variant/30 hover:shadow-md transition-all">
            <div class="w-16 h-16 rounded-full bg-primary-fixed flex items-center justify-center mb-6">
              <span class="material-symbols-outlined text-primary text-3xl">power</span>
            </div>
            <h4 class="font-label-md text-label-md text-on-surface uppercase tracking-wider mb-2">Power Hubs</h4>
            <p class="text-sm text-on-surface-variant">Charging ports integrated into every table for long sessions.</p>
          </div>
        </div>
      </div>
    </section>
    <!-- Call to Action / Workshop Promo -->
    <section class="py-section-lg px-gutter max-w-container-max-lg mx-auto">
      <div class="relative bg-inverse-surface text-inverse-on-surface rounded-[2rem] overflow-hidden p-12 md:p-24">
        <div class="absolute right-0 top-0 w-1/2 h-full opacity-30 pointer-events-none hidden md:block"></div>
        <div class="relative z-10 max-w-xl">
          <h2 class="font-headline-lg text-headline-lg mb-8">Join the Community</h2>
          <p class="font-body-lg text-body-lg mb-10 opacity-80">Beyond coffee, we host weekly workshops on design, technology, and artisanal crafts. Check our local calendars.</p>
          <div class="flex flex-col sm:flex-row gap-4">
            <button class="bg-primary-container text-on-primary-container px-10 py-4 rounded-full font-label-md hover:scale-105 transition-all">View Workshop Calendar</button>
            <button class="border-2 border-white/30 text-white px-10 py-4 rounded-full font-label-md hover:bg-white/10 transition-all">Contact Event Staff</button>
          </div>
        </div>
      </div>
    </section>
    <!-- Footer -->
    <footer class="w-full bg-surface-container-low dark:bg-surface-container-lowest py-section-sm">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-unit px-gutter max-w-container-max-lg mx-auto">
        <div class="flex flex-col gap-4">
          <a class="font-headline-sm text-headline-sm text-primary dark:text-primary-fixed" href="#">Ngopidea</a>
          <p class="font-body-md text-body-md text-on-surface-variant max-w-xs">Artisanal coffee and collaborative workspaces designed for the modern intellectual.</p>
        </div>
        <div class="flex flex-col gap-4 md:items-center">
          <h4 class="font-label-md text-label-md text-on-surface uppercase tracking-widest">Navigation</h4>
          <div class="flex flex-col gap-2">
            <a class="font-label-md text-label-md text-on-surface-variant hover:text-primary transition-all duration-300 hover:translate-x-1" href="#">Our Story</a>
            <a class="font-label-md text-label-md text-on-surface-variant hover:text-primary transition-all duration-300 hover:translate-x-1" href="#">Workshops</a>
            <a class="font-label-md text-label-md text-on-surface-variant hover:text-primary transition-all duration-300 hover:translate-x-1" href="#">Menu</a>
          </div>
        </div>
        <div class="flex flex-col gap-4 md:items-end">
          <div class="flex gap-4 mb-4">
            <a class="text-secondary hover:text-primary transition-colors" href="#"><span class="material-symbols-outlined">public</span></a>
            <a class="text-secondary hover:text-primary transition-colors" href="#"><span class="material-symbols-outlined">share</span></a>
            <a class="text-secondary hover:text-primary transition-colors" href="#"><span class="material-symbols-outlined">alternate_email</span></a>
          </div>
          <div class="flex flex-wrap gap-x-6 gap-y-2 md:justify-end">
            <a class="font-label-md text-label-md text-on-surface-variant hover:text-secondary" href="#">Privacy Policy</a>
            <a class="font-label-md text-label-md text-on-surface-variant hover:text-secondary" href="#">Terms of Service</a>
            <a class="font-label-md text-label-md text-on-surface-variant hover:text-secondary" href="#">Contact Us</a>
            <a class="font-label-md text-label-md text-on-surface-variant hover:text-secondary" href="#">Careers</a>
          </div>
          <p class="font-body-md text-body-md text-on-surface-variant opacity-60 mt-8">© 2024 Ngopidea Artisanal Cafe. All rights reserved.</p>
        </div>
      </div>
    </footer>
    <!-- Reservation Modal -->
    <div id="reservationModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="bg-surface rounded-3xl p-8 md:p-10 max-w-md w-full shadow-2xl scale-95 transition-transform duration-300" id="reservationModalContent">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-headline-sm text-2xl text-on-surface">Reserve a Table</h3>
                <button onclick="closeReservationModal()" class="text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <form method="POST" action="location.php" class="space-y-6">
                <input type="hidden" name="action" value="reserve">
                
                <div>
                    <label class="block font-label-md text-on-surface-variant mb-2">Date</label>
                    <input type="date" name="res_date" required class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary text-on-surface outline-none" min="<?= date('Y-m-d') ?>">
                </div>
                
                <div>
                    <label class="block font-label-md text-on-surface-variant mb-2">Time</label>
                    <input type="time" name="res_time" required class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary text-on-surface outline-none">
                </div>
                
                <div>
                    <label class="block font-label-md text-on-surface-variant mb-2">Guests</label>
                    <select name="guests" required class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary text-on-surface appearance-none outline-none">
                        <option value="1">1 Person</option>
                        <option value="2" selected>2 People</option>
                        <option value="3">3 People</option>
                        <option value="4">4 People</option>
                        <option value="5">5 People</option>
                        <option value="6">6+ People</option>
                    </select>
                </div>
                
                <button type="submit" class="w-full bg-primary text-on-primary py-4 rounded-full font-label-md uppercase tracking-wider hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-md mt-4">
                    Confirm Reservation
                </button>
                
                <?php if (!isset($_SESSION['user_id'])): ?>
                <p class="text-xs text-error mt-2 text-center">You must be logged in to make a reservation.</p>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <script>
      // Reservation Modal Interactions
      function openReservationModal() {
        <?php if (!isset($_SESSION['user_id'])): ?>
            window.location.href = 'signin.php';
            return;
        <?php endif; ?>
        const modal = document.getElementById('reservationModal');
        const content = document.getElementById('reservationModalContent');
        modal.classList.remove('opacity-0', 'pointer-events-none');
        content.classList.remove('scale-95');
      }

      function closeReservationModal() {
        const modal = document.getElementById('reservationModal');
        const content = document.getElementById('reservationModalContent');
        modal.classList.add('opacity-0', 'pointer-events-none');
        content.classList.add('scale-95');
      }

      // Simple interaction for the map/location list
      function focusLocation(id) {
        console.log("Focusing location:", id);
        // In a real app, this would pan the map to the specific cafe coordinates
      }

      // Header scroll effect
      window.addEventListener("scroll", () => {
        const header = document.querySelector("header");
        if (window.scrollY > 50) {
          header.classList.add("shadow-md");
        } else {
          header.classList.remove("shadow-md");
        }
      });
    </script>
  </body>
</html>

