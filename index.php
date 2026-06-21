<?php 
require_once 'includes/auth.php'; 
require_once 'includes/db.php';

// Fetch Signature Drinks (Category 3)
$signature_drinks_result = $conn->query("SELECT * FROM products WHERE category_id = 3 AND is_active = 1 ORDER BY id ASC LIMIT 4");
$signature_drinks = [];
if ($signature_drinks_result) {
    while ($prod = $signature_drinks_result->fetch_assoc()) {
        $signature_drinks[] = $prod;
    }
}
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Ngopidea | Your Urban Coffee Sanctuary</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <!-- Google Fonts -->
    <link
      href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&amp;family=Merriweather:wght@300;400;700&amp;family=Plus+Jakarta+Sans:wght@500;600;700&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
      rel="stylesheet"
    />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link
      href="https://fonts.googleapis.com/css2?family=Merriweather:wght@100..900&amp;family=Playfair+Display:wght@100..900&amp;family=Plus+Jakarta+Sans:wght@100..900&amp;display=swap"
      rel="stylesheet"
    />

    <!-- Tailwind Config -->
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "on-tertiary-fixed-variant": "#36495a",
              "surface-container-highest": "#f2dfd3",
              "surface-container": "#fdeade",
              "on-background": "#231a13",
              "secondary-fixed-dim": "#edbe89",
              "surface-variant": "#f2dfd3",
              "on-primary-fixed-variant": "#763300",
              "inverse-on-surface": "#ffede3",
              "on-primary-fixed": "#321200",
              background: "#fff8f5",
              "surface-bright": "#fff8f5",
              error: "#ba1a1a",
              "primary-fixed-dim": "#ffb68d",
              "on-primary-container": "#5d2700",
              "on-secondary-container": "#7a562a",
              primary: "#9a4600",
              "on-tertiary-container": "#263849",
              "surface-container-low": "#fff1e9",
              "on-surface": "#231a13",
              "on-tertiary-fixed": "#081d2d",
              "surface-container-lowest": "#ffffff",
              "surface-container-high": "#f7e5d9",
              secondary: "#7b572b",
              "tertiary-container": "#8ea2b6",
              "error-container": "#ffdad6",
              "outline-variant": "#e0c0b0",
              "on-secondary-fixed": "#2b1700",
              "secondary-container": "#ffcf99",
              "inverse-primary": "#ffb68d",
              "on-error-container": "#93000a",
              "surface-dim": "#e9d7cb",
              "on-secondary": "#ffffff",
              "surface-tint": "#9a4600",
              "tertiary-fixed-dim": "#b5c9de",
              tertiary: "#4d6073",
              "secondary-fixed": "#ffddb9",
              "on-secondary-fixed-variant": "#604016",
              "on-primary": "#ffffff",
              "on-surface-variant": "#584236",
              "on-error": "#ffffff",
              "on-tertiary": "#ffffff",
              "primary-fixed": "#ffdbc9",
              "primary-container": "#ff790b",
              surface: "#fff8f5",
              "tertiary-fixed": "#d1e5fb",
              outline: "#8c7264",
              "inverse-surface": "#392e26",
            },
            borderRadius: {
              DEFAULT: "0.25rem",
              lg: "0.5rem",
              xl: "0.75rem",
              full: "9999px",
            },
            spacing: {
              unit: "8px",
              "container-max-lg": "1200px",
              "section-sm": "40px",
              gutter: "20px",
              "section-md": "80px",
              "section-lg": "100px",
              "container-max-md": "1100px",
            },
            fontFamily: {
              "body-md": ["Merriweather"],
              "headline-lg": ["Playfair Display"],
              "label-md": ["Plus Jakarta Sans"],
              "display-hero": ["Playfair Display"],
              "display-hero-mobile": ["Playfair Display"],
              "headline-md": ["Playfair Display"],
              "body-lg": ["Merriweather"],
              "headline-lg-mobile": ["Playfair Display"],
              "headline-sm": ["Playfair Display"],
            },
            fontSize: {
              "body-md": ["16px", { lineHeight: "1.6", fontWeight: "400" }],
              "headline-lg": ["56px", { lineHeight: "1.2", letterSpacing: "-1px", fontWeight: "700" }],
              "label-md": ["14px", { lineHeight: "1.2", fontWeight: "600" }],
              "display-hero": ["72px", { lineHeight: "1.2", letterSpacing: "2px", fontWeight: "700" }],
              "display-hero-mobile": ["35px", { lineHeight: "1.2", letterSpacing: "1px", fontWeight: "700" }],
              "headline-md": ["40px", { lineHeight: "1.3", fontWeight: "700" }],
              "body-lg": ["18px", { lineHeight: "1.8", fontWeight: "400" }],
              "headline-lg-mobile": ["32px", { lineHeight: "1.2", letterSpacing: "0px", fontWeight: "700" }],
              "headline-sm": ["24px", { lineHeight: "1.4", fontWeight: "700" }],
            },
          },
        },
      };
    </script>

    <!-- Custom Stylesheet -->
    <link href="style.css" rel="stylesheet" />
  </head>

  <body class="bg-background text-on-surface font-body-md selection:bg-primary-fixed selection:text-on-primary-fixed">
    <!-- ==================== TopNavBar ==================== -->
    <?php include 'includes/navbar.php'; ?>

    <main>
      <!-- ==================== Hero Section ==================== -->
      <section class="relative h-screen min-h-[700px] flex items-center overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
          <img
            class="w-full h-full object-cover"
            data-alt="Cinematic interior shot of a modern minimalist cafe. Warm wooden furniture, plants, and sunlight streaming through large windows. A barista is expertly preparing a beverage behind a clean marble counter. The atmosphere is peaceful and sophisticated. Warm cream and coffee brown tones."
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuAeoVMxw4EDyXRB1lTmipJooclXPqhc9wfACpKJkxmrm6SkezLhQOFX2XHfKd-hAl7-cjOpHqTetITqDd9zLRUcaHPEfls6mPqMgkNI1w-xhVsKG1imaua-qrYjZzT2VtylY08K1lHkHVMXrzA7vzaXhKOnaHLz2pW7yqfK9aZcAQ5Ff7-lpySxoMYMPvlv2NdEBUZK_gP4UT0WbkZ0Jw8a3GUJa8Mz3gCamUmzqeeZurXXgfnDWJPWw-C36USJyxX9Js4CJZiz_g0"
          />
          <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/30 to-transparent"></div>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 max-w-container-max-lg mx-auto px-gutter w-full text-white">
          <div class="max-w-2xl">
            <span class="font-label-md text-primary-fixed uppercase tracking-widest mb-4 block animate-fade-in">Your Urban Sanctuary</span>
            <h1 class="font-display-hero-mobile md:font-display-hero text-display-hero-mobile md:text-display-hero mb-6 text-glow">Elevate Your Daily Moment</h1>
            <p class="font-body-lg text-body-lg mb-10 text-white/90 leading-relaxed">
              Discover a space where time slows down. Hand-crafted beverages prepared with artisanal precision, ready for your arrival or to be enjoyed in our sanctuary.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
              <a href="<?php echo isLoggedIn() ? 'menu.php' : 'signin.php'; ?>" class="inline-block bg-primary hover:bg-primary-container text-on-primary font-label-md text-label-md px-8 py-4 rounded-full transition-all duration-300 transform hover:scale-105 shadow-xl text-center">
                <?php echo isLoggedIn() ? 'Order Now' : 'Sign In'; ?>
              </a>
              <a href="location.php" class="border-2 border-white/30 hover:border-white text-white font-label-md text-label-md px-8 py-4 rounded-full transition-all duration-300 backdrop-blur-sm">
                Find a Cafe
              </a>
            </div>
          </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce hidden md:block">
          <span class="material-symbols-outlined text-white/60 text-4xl">keyboard_double_arrow_down</span>
        </div>
      </section>

      <!-- ==================== Our Story Section ==================== -->
      <section class="py-section-lg bg-surface relative overflow-hidden">
        <div class="max-w-container-max-lg mx-auto px-gutter grid md:grid-cols-2 gap-16 items-center">
          <!-- Image Column -->
          <div class="relative group">
            <div class="absolute -top-10 -left-10 w-40 h-40 bg-secondary-container/20 rounded-full blur-3xl"></div>
            <img
              class="rounded-2xl shadow-2xl relative z-10 transition-transform duration-700 group-hover:scale-[1.02]"
              data-alt="A portrait of a passionate barista working in a warm industrial-style cafe. The barista is carefully creating latte art on a creamy flat white. The atmosphere is filled with warmth and authenticity, featuring soft ambient lighting and organic textures of wood and stone. Warm cream and coffee brown tones."
              src="https://lh3.googleusercontent.com/aida-public/AB6AXuBKduuFi5eooW0aaozx_E6ozfT6QMorz37p1sWhBkdySxxOKJ6sZpz5XiNqIDFQtroECa2qmPE3ifSnNuRvX3W5Hjys9x_h7H8AWiXx6cEBhDaUKCSDlUkHutWeKjDvxQGS9_4aVlwsd9kTG_Psz0L9dqXv1At5OKASRj-q_76GS3WlHLQ3GGXNITaESDa7vFBNLcs7l8aauhBACSDo1qEF2BDbkQ9fTr4jNGzrlHOex7IoRUgmAKNOhE440aHa8b4KdU6OK3VFVDc"
            />
            <div class="absolute -bottom-6 -right-6 bg-white p-8 rounded-xl shadow-lg z-20 max-w-[240px]">
              <p class="font-headline-sm text-primary mb-2">Barista Craft</p>
              <p class="text-sm text-on-surface-variant italic">Every cup is a masterpiece, hand-poured with love and technical precision.</p>
            </div>
          </div>

          <!-- Text Column -->
          <div>
            <h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary mb-8">The Art of the Brew</h2>
            <div class="space-y-6 text-on-surface-variant">
              <p class="font-body-lg text-body-lg">
                At Ngopidea, we believe your beverage is more than just a drink—it's an intellectual journey. Our cafe began as a small space with a single mission: to redefine the urban coffee
                experience through artisanal craft and authentic hospitality.
              </p>
              <p class="font-body-lg text-body-lg">
                Every drink is hand-crafted by our certified baristas, ensuring that the terroir of our ethically sourced beans is perfectly expressed in every sip. From the temperature of the milk to
                the pressure of the extraction, we obsess over every detail so you don't have to.
              </p>
              <div class="pt-4 border-t border-outline-variant/30 flex items-center gap-6">
                <div class="text-center">
                  <p class="text-3xl font-display-hero-mobile text-primary">5</p>
                  <p class="text-xs uppercase tracking-widest font-label-md">Locations</p>
                </div>
                <div class="w-px h-10 bg-outline-variant"></div>
                <div class="text-center">
                  <p class="text-3xl font-display-hero-mobile text-primary">24/7</p>
                  <p class="text-xs uppercase tracking-widest font-label-md">Fresh Brew</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ==================== Signature Drinks ==================== -->
      <section class="py-section-lg bg-surface-container-low">
        <div class="max-w-container-max-lg mx-auto px-gutter">
          <!-- Section Header -->
          <div class="flex justify-between items-end mb-12">
            <div>
              <h2 class="font-headline-md text-headline-md text-on-surface mb-2">Signature Drinks</h2>
              <p class="text-on-surface-variant font-body-md">Hand-crafted by our master baristas for your perfect moment.</p>
            </div>
            <a class="text-primary font-label-md flex items-center gap-2 hover:gap-4 transition-all" href="menu.php"> Explore Full Menu <span class="material-symbols-outlined">arrow_forward</span> </a>
          </div>

          <!-- Product Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($signature_drinks as $index => $prod): ?>
            <!-- Product Card -->
            <div class="bg-surface rounded-2xl overflow-hidden product-card-hover transition-all duration-500 group flex flex-col">
              <div class="relative h-64 overflow-hidden">
                <img
                  class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                  alt="<?= htmlspecialchars($prod['product_name']) ?>"
                  src="assets/<?= htmlspecialchars($prod['image_url']) ?>"
                />
                <?php if ($index === 0): ?>
                <div class="absolute top-4 left-4 bg-primary text-white text-[10px] px-3 py-1 rounded-full font-bold uppercase tracking-widest">House Favorite</div>
                <?php elseif ($index === 1): ?>
                <div class="absolute top-4 left-4 bg-tertiary text-white text-[10px] px-3 py-1 rounded-full font-bold uppercase tracking-widest">Seasonal</div>
                <?php endif; ?>
              </div>
              <div class="p-6 flex-1 flex flex-col">
                <div class="flex justify-between items-start mb-2">
                  <h3 class="font-headline-sm text-xl"><?= htmlspecialchars($prod['product_name']) ?></h3>
                  <span class="font-bold text-primary">$<?= number_format($prod['price'], 2) ?></span>
                </div>
                <p class="text-sm text-on-surface-variant mb-6"><?= htmlspecialchars($prod['description']) ?></p>
                <form action="cart_action.php" method="POST" class="mt-auto w-full">
                  <input type="hidden" name="action" value="add">
                  <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                  <input type="hidden" name="quantity" value="1">
                  <button
                    type="submit"
                    class="w-full border border-primary text-primary hover:bg-primary hover:text-white py-3 rounded-xl font-label-md transition-colors flex items-center justify-center gap-2"
                  >
                    <span class="material-symbols-outlined text-lg">shopping_cart</span> Order for Pickup
                  </button>
                </form>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <!-- ==================== Cafe Experience / Workshops Section ==================== -->
      <section class="py-section-lg bg-inverse-surface text-white">
        <div class="max-w-container-max-lg mx-auto px-gutter">
          <div class="grid md:grid-cols-2 gap-12 items-center">
            <!-- Text Column -->
            <div>
              <span class="font-label-md text-primary-fixed uppercase tracking-widest mb-4 block">Master the Craft</span>
              <h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg mb-6">Join Our Barista Workshops</h2>
              <p class="font-body-lg text-body-lg text-white/70 mb-8 leading-relaxed">
                Go beyond the cup. Learn the secrets of sensory evaluation, milk steaming, and pour-over techniques from our master baristas in our monthly in-shop workshops.
              </p>
              <ul class="space-y-4 mb-10">
                <li class="flex items-center gap-3">
                  <span class="material-symbols-outlined text-primary-fixed">check_circle</span>
                  <span class="font-body-md">Sensory Evaluation &amp; Cupping Basics</span>
                </li>
                <li class="flex items-center gap-3">
                  <span class="material-symbols-outlined text-primary-fixed">check_circle</span>
                  <span class="font-body-md">Advanced Latte Art Masterclass</span>
                </li>
                <li class="flex items-center gap-3">
                  <span class="material-symbols-outlined text-primary-fixed">check_circle</span>
                  <span class="font-body-md">Precision Brewing: The Science of Water</span>
                </li>
              </ul>
              <button class="group flex items-center gap-3 font-label-md bg-white text-on-background px-8 py-4 rounded-full hover:bg-primary-container hover:text-on-primary-container transition-all">
                View Workshop Schedule
                <span class="material-symbols-outlined transition-transform group-hover:translate-x-2">calendar_month</span>
              </button>
            </div>

            <!-- Video / Image Column -->
            <div class="relative">
              <div class="aspect-video rounded-3xl overflow-hidden shadow-2xl relative">
                <img
                  class="w-full h-full object-cover"
                  data-alt="A group of people gathered around a large wooden cafe table, learning how to brew coffee under the guidance of a professional barista. Warm lighting, steam rising, engaged faces. Authentic community workshop atmosphere."
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuBzHdxduNKt4siefdTN6BwyyViq-300-W-fSDUYZGhUNVG4HgTJGOcyLv_tsArnYwWFl1F4JeI3WBpENT7qYaNBUa5Jwsl8hNyZ6A3aryQ6_FIMdMtumRVPTejFNqcyZDozEodXC6Ga_HijLDgbEqDWSnJExZ-clb_Dy9Xfc0THiFD_C3PPwwE7HgA6LIOrVIG7pZH9aT-46IjF71EFLy62wkYFbf_hM8HSh4HacAIkGqDcK8caGBWGu7QduxhtnsVp8GPznXJPqFA"
                />
                <div class="absolute inset-0 flex items-center justify-center bg-black/20 hover:bg-black/40 transition-colors cursor-pointer group">
                  <div class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-5xl text-white">play_arrow</span>
                  </div>
                </div>
              </div>

              <!-- Floating Badge -->
              <div class="absolute -bottom-6 -left-6 bg-primary-container p-6 rounded-2xl hidden lg:block">
                <div class="flex items-center gap-4">
                  <div class="bg-white/20 p-2 rounded-lg">
                    <span class="material-symbols-outlined text-on-primary-container">groups</span>
                  </div>
                  <div>
                    <p class="text-on-primary-container font-bold">Limited Seats Available</p>
                    <p class="text-on-primary-container/80 text-xs uppercase font-label-md">Next: Dec 15th</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ==================== Testimonials ==================== -->
      <section class="py-section-lg bg-surface relative overflow-hidden">
        <!-- Section Header -->
        <div class="max-w-container-max-lg mx-auto px-gutter text-center mb-16">
          <h2 class="font-headline-md text-headline-md text-primary mb-4">The Cafe Community</h2>
          <div class="w-24 h-1 bg-secondary-container mx-auto"></div>
        </div>

        <!-- Testimonial Cards -->
        <div class="max-w-container-max-lg mx-auto px-gutter grid md:grid-cols-3 gap-8">
          <!-- Testimonial 1 -->
          <div class="p-10 bg-white rounded-3xl shadow-sm border border-outline-variant/10 relative">
            <span class="material-symbols-outlined text-primary-fixed/30 text-6xl absolute top-6 right-6">format_quote</span>
            <div class="flex gap-1 mb-4 text-primary">
              <span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1">star</span>
              <span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1">star</span>
              <span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1">star</span>
              <span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1">star</span>
              <span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1">star</span>
            </div>
            <p class="font-body-md italic text-on-surface-variant mb-8">
              "Ngopidea has become my second office. The atmosphere is perfectly balanced for focus, and that Velvet Flat White is truly the best I've had outside of Melbourne."
            </p>
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-full overflow-hidden">
                <img
                  class="w-full h-full object-cover"
                  data-alt="Portrait of a smiling woman in her 30s with a modern professional look, holding a white ceramic coffee mug. Soft focus interior background. Warm and friendly vibe."
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuDrpOgGYKxfLKSbnhQUtiAMB1lt6Gm6OZ7-qa5Y2iB8jkqbt3SZVedr4PmKUOxvrwtX09cL63aGNuLXFabO4SMhJXnXJ7QHDodjZkQmEG33GfvB99af76_b45sRy3dQf7Lay3goXp-K77J7q5t6dZy9m-yxRpjUZoAr3EGT2BomovfcnmuFNpc-osBuwFM_qTl0v3PXz09fAZs9_kRMhMfxVQqmpE32qJd96_RC6tbwRqXtQigZ2LryKva0hDHAmx9IJh98YbcNhSQ"
                />
              </div>
              <div class="text-left">
                <p class="font-bold text-on-surface">Elena S.</p>
                <p class="text-xs text-on-surface-variant uppercase tracking-widest font-label-md">Graphic Designer</p>
              </div>
            </div>
          </div>

          <!-- Testimonial 2 -->
          <div class="p-10 bg-white rounded-3xl shadow-sm border border-outline-variant/10 relative md:scale-105 z-10">
            <span class="material-symbols-outlined text-primary-fixed/30 text-6xl absolute top-6 right-6">format_quote</span>
            <div class="flex gap-1 mb-4 text-primary">
              <span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1">star</span>
              <span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1">star</span>
              <span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1">star</span>
              <span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1">star</span>
              <span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1">star</span>
            </div>
            <p class="font-body-md italic text-on-surface-variant mb-8">
              "The attention to detail here is incredible. You can really taste the artisanal touch in every brewed beverage. It's not just coffee; it's a sensory ritual I look forward to every
              morning."
            </p>
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-full overflow-hidden">
                <img
                  class="w-full h-full object-cover"
                  data-alt="Close-up portrait of a man in his late 40s with a kind face and salt-and-pepper hair, wearing a stylish minimalist apron. Looking directly at the camera with a subtle smile. High-quality editorial style."
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuDA_yi4tW5fvjUYowHBF2pV3gekFeW-t32nx_EfMSP-XrcTf3E3O_YNAZyBFJTsx0noCmNYrJMFhxCEhm5_RyOzhm68tRG9GJcuMGGCHKAnTGgLa5kOqGXj-7de_s0vzvGvgW4UjYFseDhW7Xv_f5QJ8S4YnFKha_rgGVTVG6WDeJDqBiWD-CyIC3gxLKyR-VLN4XsH7a8Lsn6_JBe6RwJhX7MCQv5Ma6eE4byvEUaKLQccpzDaNFLmS6jDdGKE8iOlRSv6ZocDRzg"
                />
              </div>
              <div class="text-left">
                <p class="font-bold text-on-surface">Julian R.</p>
                <p class="text-xs text-on-surface-variant uppercase tracking-widest font-label-md">Tech Lead</p>
              </div>
            </div>
          </div>

          <!-- Testimonial 3 -->
          <div class="p-10 bg-white rounded-3xl shadow-sm border border-outline-variant/10 relative">
            <span class="material-symbols-outlined text-primary-fixed/30 text-6xl absolute top-6 right-6">format_quote</span>
            <div class="flex gap-1 mb-4 text-primary">
              <span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1">star</span>
              <span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1">star</span>
              <span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1">star</span>
              <span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1">star</span>
              <span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1">star</span>
            </div>
            <p class="font-body-md italic text-on-surface-variant mb-8">
              "I love their pickup service. Ordering through the app and having my Signature Pandan ready the moment I walk in is the peak of modern cafe hospitality. Simply superb."
            </p>
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-full overflow-hidden">
                <img
                  class="w-full h-full object-cover"
                  data-alt="Portrait of a young creative man with glasses and a thoughtful expression, sitting in a modern open workspace. Natural light, minimalist aesthetic. Professional photography style."
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuDtJyLHRSn5sqDwttG8q2DBCaltSd7fEnrll8S07PjfOMrB6so2xUTW0LTXHUk5WZl93UPWNCkSKS7H5cPghu4_mIzwVFgPFCSRCtyeXnp0Nuldx7aC3xGJ5iGYo3j0GHd-nd-b6dY77dg7OEl3PncmeXoM0YK5w17794329KAB1j3ocLDQsfktIICI-rGfP0pxImOFpqopXItjYIs_CNnQ31HpcvAENLLlnExoaWTGMUgpq6v-V84b4JlkUpk76TLJxQzuDSJ-dDo"
                />
              </div>
              <div class="text-left">
                <p class="font-bold text-on-surface">Markus T.</p>
                <p class="text-xs text-on-surface-variant uppercase tracking-widest font-label-md">Author</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ==================== Newsletter Section ==================== -->
      <section id="newsletter" class="py-section-lg bg-surface-container">
        <div class="max-w-container-max-md mx-auto px-gutter text-center">
          <div class="inline-block p-4 bg-white rounded-full mb-8 shadow-sm">
            <span class="material-symbols-outlined text-primary text-4xl">local_cafe</span>
          </div>
          <h2 class="font-headline-md text-headline-md text-on-surface mb-4">Join Our Community</h2>
          <p class="font-body-lg text-body-lg text-on-surface-variant mb-6 max-w-xl mx-auto">
            Get early access to seasonal drink launches, cafe events, and 15% off your first in-store order when you join.
          </p>

          <?php if (isset($_GET['subscribed']) && $_GET['subscribed'] == 1): ?>
          <div class="mb-8 p-4 bg-green-100 text-green-800 rounded-xl max-w-lg mx-auto font-label-md shadow-sm border border-green-200">
            Welcome to the Ngopidea community! You've successfully subscribed.
          </div>
          <?php elseif (isset($_GET['subscribed']) && $_GET['subscribed'] == 0): ?>
          <div class="mb-8 p-4 bg-red-100 text-red-800 rounded-xl max-w-lg mx-auto font-label-md shadow-sm border border-red-200">
            Oops! That email might already be subscribed or there was an error.
          </div>
          <?php endif; ?>

          <form
            action="subscribe.php"
            method="POST"
            class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto"
          >
            <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>" />
            <input name="email" class="flex-1 px-8 py-4 rounded-full border-none focus:ring-2 focus:ring-primary font-body-md shadow-sm" placeholder="Enter your email" required="" type="email" />
            <button class="bg-primary hover:bg-primary-container text-on-primary font-label-md px-10 py-4 rounded-full transition-all duration-300 shadow-md whitespace-nowrap" type="submit">
              Sign Me Up
            </button>
          </form>
          <p class="mt-6 text-xs text-on-surface-variant/60 font-label-md">We respect your inbox. Unsubscribe anytime.</p>
        </div>
      </section>
    </main>

    <!-- ==================== Footer ==================== -->
    <footer class="bg-surface-container-high py-section-sm">
      <div class="max-w-container-max-lg mx-auto px-gutter">
        <!-- Footer Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
          <!-- Brand Column -->
          <div class="md:col-span-1">
            <a class="font-headline-sm text-headline-sm text-primary dark:text-primary-fixed-dim" href="#">Ngopidea</a>
            <p class="mt-4 text-on-surface-variant text-sm leading-relaxed">Defining the modern cafe experience through artisanal brewing and authentic community ethics.</p>
            <div class="flex gap-4 mt-6">
              <a class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-colors shadow-sm" href="#">
                <span class="material-symbols-outlined text-xl">camera</span>
              </a>
              <a class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-colors shadow-sm" href="#">
                <span class="material-symbols-outlined text-xl">face_nod</span>
              </a>
              <a class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-colors shadow-sm" href="#">
                <span class="material-symbols-outlined text-xl">share</span>
              </a>
            </div>
          </div>

          <!-- Footer Links -->
          <div class="md:col-span-3 grid grid-cols-2 sm:grid-cols-3 gap-8">
            <!-- Explore -->
            <div>
              <p class="font-label-md text-primary mb-6 uppercase tracking-widest text-xs">Explore</p>
              <ul class="space-y-3">
                <li><a class="text-on-surface-variant hover:text-primary transition-colors font-body-md text-sm" href="#">Our Story</a></li>
                <li><a class="text-on-surface-variant hover:text-primary transition-colors font-body-md text-sm" href="#">Workshops</a></li>
                <li><a class="text-on-surface-variant hover:text-primary transition-colors font-body-md text-sm" href="#">Cafe Journal</a></li>
                <li><a class="text-on-surface-variant hover:text-primary transition-colors font-body-md text-sm" href="#">Locations</a></li>
              </ul>
            </div>

            <!-- Menu -->
            <div>
              <p class="font-label-md text-primary mb-6 uppercase tracking-widest text-xs">Menu</p>
              <ul class="space-y-3">
                <li><a class="text-on-surface-variant hover:text-primary transition-colors font-body-md text-sm" href="#">Signature Drinks</a></li>
                <li><a class="text-on-surface-variant hover:text-primary transition-colors font-body-md text-sm" href="#">Seasonal Specials</a></li>
                <li><a class="text-on-surface-variant hover:text-primary transition-colors font-body-md text-sm" href="#">Light Bites</a></li>
                <li><a class="text-on-surface-variant hover:text-primary transition-colors font-body-md text-sm" href="#">Gift Cards</a></li>
              </ul>
            </div>

            <!-- Support -->
            <div>
              <p class="font-label-md text-primary mb-6 uppercase tracking-widest text-xs">Support</p>
              <ul class="space-y-3">
                <li><a class="text-on-surface-variant hover:text-primary transition-colors font-body-md text-sm" href="#">Privacy Policy</a></li>
                <li><a class="text-on-surface-variant hover:text-primary transition-colors font-body-md text-sm" href="#">Terms of Service</a></li>
                <li><a class="text-on-surface-variant hover:text-primary transition-colors font-body-md text-sm" href="#">Pickup Info</a></li>
                <li><a class="text-on-surface-variant hover:text-primary transition-colors font-body-md text-sm" href="#">Contact Us</a></li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Footer Bottom -->
        <div class="pt-8 border-t border-outline-variant/30 flex flex-col md:flex-row justify-between items-center gap-4">
          <p class="text-on-surface-variant text-sm font-body-md">© 2024 Ngopidea Artisanal Cafe. All rights reserved.</p>
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-on-surface-variant text-xl">language</span>
            <span class="text-sm text-on-surface-variant font-label-md">English (US)</span>
          </div>
        </div>
      </div>
    </footer>

    <!-- ==================== Scripts ==================== -->
    <script>
      // Simple scroll behavior for header
      window.addEventListener("scroll", () => {
        const header = document.querySelector("header");
        if (window.scrollY > 50) {
          header.classList.add("py-2");
          header.classList.remove("py-4");
        } else {
          header.classList.add("py-4");
          header.classList.remove("py-2");
        }
      });

      // AJAX Add to Cart
      document.querySelectorAll('form[action="cart_action.php"]').forEach((form) => {
        form.addEventListener('submit', function(e) {
          e.preventDefault();
          
          const formData = new FormData(this);
          formData.append('ajax', '1');
          
          const btn = this.querySelector('button[type="submit"]');
          const originalText = btn.innerHTML;
          
          // Show loading state
          btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">sync</span> Adding...';
          btn.classList.add("bg-primary", "text-white");
          
          fetch('cart_action.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if(data.status === 'success') {
              // Show success state
              btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">check_circle</span> Added!';
              
              // Update badge
              const badge = document.getElementById('cart-badge');
              if(badge) {
                badge.innerText = data.cart_count;
                if (data.cart_count > 0) {
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
              }
              
              // Reset button after 2 seconds
              setTimeout(() => {
                btn.innerHTML = originalText;
                btn.classList.remove("bg-primary", "text-white");
              }, 2000);
            }
          })
          .catch(error => {
            console.error('Error adding to cart:', error);
            btn.innerHTML = originalText;
            btn.classList.remove("bg-primary", "text-white");
          });
        });
      });

      // Hover animation for product cards
      const cards = document.querySelectorAll(".product-card-hover");
      cards.forEach((card) => {
        card.addEventListener("mouseenter", () => {
          // Potential for more complex JS micro-interactions here
        });
      });
    </script>
  </body>
</html>

