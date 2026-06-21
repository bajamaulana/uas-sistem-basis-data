<?php require_once 'includes/auth.php'; ?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Journal | Ngopidea Artisanal Cafe</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&amp;family=Plus+Jakarta+Sans:wght@600&amp;family=Merriweather:ital,wght@0,400;0,700;1,400&amp;display=swap"
      rel="stylesheet"
    />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
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
    <style>
      .material-symbols-outlined {
        font-variation-settings:
          "FILL" 0,
          "wght" 400,
          "GRAD" 0,
          "opsz" 24;
        vertical-align: middle;
      }
      .glass-nav {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
      }
      .amber-glow:hover {
        box-shadow: 0 10px 30px rgba(154, 70, 0, 0.2);
        transform: translateY(-10px);
      }
      .pill-button {
        border-radius: 9999px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      }
      .pill-button:hover {
        transform: translateY(-2px);
      }
      .editorial-grid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
      }
      .nav-underline-hover {
        position: relative;
      }
      .nav-underline-hover::after {
        content: "";
        position: absolute;
        bottom: -4px;
        left: 0;
        width: 0;
        height: 2px;
        background-color: #9a4600;
        transition: width 0.3s ease;
      }
      .nav-underline-hover:hover::after {
        width: 100%;
      }
    </style>
  </head>
  <body class="bg-background text-on-surface font-body-md selection:bg-primary-fixed selection:text-on-primary-fixed">
    <!-- Top Navigation Bar -->
    <?php include 'includes/navbar.php'; ?>
    <main>
      <!-- Hero Section -->
      <section class="relative pt-section-lg pb-section-md overflow-hidden">
        <div class="max-w-container-max-lg mx-auto px-gutter relative z-10 text-center">
          <span class="font-label-md text-label-md text-primary uppercase tracking-[0.2em] mb-4 block">The Ritual of Craft</span>
          <h1 class="font-display-hero text-display-hero md:text-display-hero leading-tight mb-6">The Coffee Journal</h1>
          <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl mx-auto italic">
            Exploring the quiet alchemy of beans, baristas, and the timeless brewing rituals that define our house.
          </p>
        </div>
        <div class="absolute top-0 right-0 -z-0 opacity-10 blur-3xl transform translate-x-1/2 -translate-y-1/2">
          <div class="w-[600px] h-[600px] bg-primary rounded-full"></div>
        </div>
      </section>
      <!-- Featured Article -->
      <section class="pb-section-lg px-gutter max-w-container-max-lg mx-auto">
        <div class="group relative bg-surface-container-low rounded-xl overflow-hidden amber-glow transition-all duration-500 flex flex-col md:flex-row shadow-sm">
          <div class="w-full md:w-3/5 h-[400px] md:h-[600px] overflow-hidden">
            <img
              class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
              data-alt="A cinematic, top-down shot of a barista expertly pouring steamed milk into a ceramic cup, creating a perfect flat white. The setting is a moody, sun-drenched café with warm wood textures and brass accents. The lighting is soft and golden, emphasizing the smooth microfoam texture. The overall aesthetic is sophisticated, minimalist, and artisanal, dominated by warm espresso tones and creamy whites."
              src="https://lh3.googleusercontent.com/aida-public/AB6AXuDGkJjaGrpq4QFMz_yLu-6w3OtZV6-q_56SYVGz0Zf_z5-nKZ4HW5LNJJ2b3lc5c-dG1rOK_HNDaSl6hGdgBkqJVLjvo3c0p2LBYQ6fTmwfsjSASBs9YjRhKt5POMbChejurYOjY_vMb2e9Wqbpwpqt8JAHuRzo7Ly0r9X0-ip5dSEWpMIYVkTk5GuCtycwtYxrkZubyLgk-6DqE1lqNbTfGqr9BhnbvfJ1LnO_jp2UiVgGZczgrTTB3VCeRFGIkQN28u_YYU6rasQ"
            />
          </div>
          <div class="w-full md:w-2/5 p-8 md:p-16 flex flex-col justify-center">
            <div class="flex items-center gap-3 mb-6">
              <span class="bg-secondary-container text-on-secondary-container px-3 py-1 rounded-full font-label-md text-[12px] uppercase tracking-wider">Ritual</span>
              <span class="text-on-surface-variant font-label-md text-[12px]">8 Min Read</span>
            </div>
            <h2 class="font-headline-lg text-headline-md md:text-headline-lg mb-6 group-hover:text-primary transition-colors">The Secret to the Perfect Flat White</h2>
            <p class="font-body-md text-body-md text-on-surface-variant mb-8 leading-relaxed">
              Beyond the ratio of milk to espresso lies a delicate dance of temperature and texture. We delve into the micro-adjustments that transform a morning staple into a masterpiece of craft.
            </p>
            <a class="inline-flex items-center gap-2 font-label-md text-label-md text-primary font-bold group/link" href="#">
              Read the Full Story
              <span class="material-symbols-outlined transition-transform group-hover/link:translate-x-1" data-icon="arrow_forward">arrow_forward</span>
            </a>
          </div>
        </div>
      </section>
      <!-- Article Grid -->
      <section class="bg-surface-container py-section-lg">
        <div class="max-w-container-max-lg mx-auto px-gutter">
          <div class="flex justify-between items-end mb-12">
            <h3 class="font-headline-md text-headline-md">Latest Stories</h3>
            <div class="flex gap-4">
              <button class="w-12 h-12 rounded-full border border-outline/30 flex items-center justify-center hover:bg-primary hover:text-on-primary transition-colors">
                <span class="material-symbols-outlined" data-icon="chevron_left">chevron_left</span>
              </button>
              <button class="w-12 h-12 rounded-full border border-outline/30 flex items-center justify-center hover:bg-primary hover:text-on-primary transition-colors">
                <span class="material-symbols-outlined" data-icon="chevron_right">chevron_right</span>
              </button>
            </div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Card 1 -->
            <article class="flex flex-col bg-surface rounded-lg overflow-hidden amber-glow shadow-sm transition-all duration-300">
              <div class="h-64 overflow-hidden">
                <img
                  class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                  data-alt="A lush, close-up photograph of vibrant green coffee cherries ripening on a branch in a misty, high-altitude Ethiopian coffee farm. The lighting is diffused and atmospheric, highlighting the organic textures of the leaves and fruit. The style is editorial and natural, using a palette of deep greens and earthy browns to evoke a sense of origin and sustainability."
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuAiackaZO4weRA4lXKrUl0kniyp23qNAn6zUzgNp77ivQ7NFFzF5AEgriP5uhnCzpNx3in9JUibq17H0HeuYsDuk_I-QTe1xZTQ87kXxnctjEBAKoNSeF0e62DBIvQakJVoS6DIbOV0jSBzEepQwvT-IZFVtHLNqKkMuab1wjIXmZaeN8-7qfxAithyzOY3DR3IOX0v2oCp2eWzY8XTN0_Sq9jEIm7WX3HyX60u3UnqiLsN7WnoSUQ9DV7lDD773z4ugJyx0Z_9594"
                />
              </div>
              <div class="p-8 flex flex-col flex-grow">
                <span class="text-primary font-label-md text-[12px] uppercase tracking-widest mb-3">Origins</span>
                <h4 class="font-headline-sm text-headline-sm mb-4">Sourcing Our Seasonal Microlots</h4>
                <p class="font-body-md text-body-md text-on-surface-variant mb-6 line-clamp-3">
                  Journey with us to the high-altitude farms of Ethiopia where we partner directly with growers to bring you the season's most unique flavor profiles.
                </p>
                <div class="mt-auto pt-4 border-t border-outline-variant">
                  <a class="text-on-surface font-bold font-label-md flex items-center gap-2 hover:text-primary transition-colors" href="#">
                    Read More
                    <span class="material-symbols-outlined text-[18px]" data-icon="north_east">north_east</span>
                  </a>
                </div>
              </div>
            </article>
            <!-- Card 2 -->
            <article class="flex flex-col bg-surface rounded-lg overflow-hidden amber-glow shadow-sm transition-all duration-300">
              <div class="h-64 overflow-hidden">
                <img
                  class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                  data-alt="A candid, artistic black and white portrait of a barista deeply focused on tamping coffee grounds. The scene is filled with steam from the espresso machine, creating a sense of dynamic movement and atmosphere. Soft light catches the metallic surfaces of the coffee equipment. The style is gritty yet elegant, focusing on the human element and the precision of the barista's craft."
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuBTzbuHLmoZhEVFLBj0h2B_udN6PNbvqpf3BiWJ6NxmsMx-qb9GiqyB5aKtH4QvMf2eyu5gEW45dUvuWuZuz68FiKsQFmcdkMVlGZrdmpKMbS4jw5AqNiU2Sg1gTduGo-A6BgFrhT_HHM-Pv1WsxajVRSnph28DxWoXO0R8mLCABmmn6AlPsrehS5vLux-YL0ZPN360Az0eWhG-bSsckCJeHfSR3vi_omO4xV1JPDOpZ3OBaQjGJVb2eu8A94PyB5geGDwtMV1Fy6A"
                />
              </div>
              <div class="p-8 flex flex-col flex-grow">
                <span class="text-primary font-label-md text-[12px] uppercase tracking-widest mb-3">Community</span>
                <h4 class="font-headline-sm text-headline-sm mb-4">A Day in the Life of a Barista</h4>
                <p class="font-body-md text-body-md text-on-surface-variant mb-6 line-clamp-3">
                  From the first grind of the morning to the closing clean-up, discover the artistry and obsession that goes into every single cup we serve.
                </p>
                <div class="mt-auto pt-4 border-t border-outline-variant">
                  <a class="text-on-surface font-bold font-label-md flex items-center gap-2 hover:text-primary transition-colors" href="#">
                    Read More
                    <span class="material-symbols-outlined text-[18px]" data-icon="north_east">north_east</span>
                  </a>
                </div>
              </div>
            </article>
            <!-- Card 3 -->
            <article class="flex flex-col bg-surface rounded-lg overflow-hidden amber-glow shadow-sm transition-all duration-300">
              <div class="h-64 overflow-hidden">
                <img
                  class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                  data-alt="A minimalist, high-contrast still life of a Chemex pour-over brewer sitting on a clean, marble countertop. A stream of water is caught in mid-air pouring into the grounds. The color palette is very neutral, featuring soft grays, whites, and the rich amber of the coffee. The mood is tranquil and intellectual, emphasizing a slow, deliberate brewing process in a modern, light-filled space."
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuDYedzXHQAsQ1WtV40UIIJ1DL8yo0Hy9ILLctpU-iOn_Q5Y4wrOyGmrim98YDaPahDb07kOSyhD1FmFO5_bre-rPKxstwUNVaVzs_Acq4L0580WqA-hZZpuR_Tm_t5lc1VdF4c6ax9EVTOn6Mrgp3ZfSGL067WokWd0q8SInqqDRoFXfNuWSwmePE9unduACo-BysSZHHdGTHWgVZSPipxtUZgz583BTCZXssN6TuS0PK4k9yXsaaJ021bIHD2MYbTXi0Uafl_jPDU"
                />
              </div>
              <div class="p-8 flex flex-col flex-grow">
                <span class="text-primary font-label-md text-[12px] uppercase tracking-widest mb-3">Knowledge</span>
                <h4 class="font-headline-sm text-headline-sm mb-4">Mastering the Pour-Over at Home</h4>
                <p class="font-body-md text-body-md text-on-surface-variant mb-6 line-clamp-3">
                  You don't need a professional setup to enjoy cafe-quality coffee. We share our foolproof guide to mastering the Chemex and Hario V60.
                </p>
                <div class="mt-auto pt-4 border-t border-outline-variant">
                  <a class="text-on-surface font-bold font-label-md flex items-center gap-2 hover:text-primary transition-colors" href="#">
                    Read More
                    <span class="material-symbols-outlined text-[18px]" data-icon="north_east">north_east</span>
                  </a>
                </div>
              </div>
            </article>
          </div>
          <div class="mt-16 text-center">
            <button class="pill-button border-2 border-primary text-primary px-10 py-4 font-label-md text-label-md hover:bg-primary hover:text-on-primary font-bold">View All Stories</button>
          </div>
        </div>
      </section>
      <!-- Newsletter Subscription -->
      <section class="py-section-lg px-gutter">
        <div class="max-w-container-max-md mx-auto bg-primary-container rounded-3xl p-10 md:p-20 relative overflow-hidden text-on-primary-container shadow-xl">
          <!-- Decorative Elements -->
          <div class="absolute top-0 right-0 w-64 h-64 bg-on-primary-container/10 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
          <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full blur-2xl transform -translate-x-1/2 translate-y-1/2"></div>
          <div class="relative z-10 max-w-xl">
            <h2 class="font-headline-md text-headline-md mb-6 leading-tight">Join the Morning Ritual</h2>
            <p class="font-body-lg text-body-lg mb-8 opacity-90">Subscribe to our newsletter for exclusive brewing guides, seasonal bean drops, and invitations to our intimate coffee workshops.</p>
            <form
              class="flex flex-col sm:flex-row gap-4"
              onsubmit="
                event.preventDefault();
                alert('Welcome to the Ritual!');
              "
            >
              <input
                class="flex-grow bg-white/20 border-white/30 text-white placeholder:text-white/70 rounded-full px-6 py-4 focus:ring-2 focus:ring-white/50 focus:border-transparent outline-none font-label-md"
                placeholder="Your email address"
                required=""
                type="email"
              />
              <button class="pill-button bg-on-primary-container text-white px-10 py-4 font-bold font-label-md hover:bg-black transition-colors whitespace-nowrap" type="submit">Subscribe Now</button>
            </form>
            <p class="mt-6 text-[12px] opacity-70 font-label-md">We respect your privacy. Unsubscribe at any time.</p>
          </div>
        </div>
      </section>
    </main>
    <!-- Footer -->
    <footer class="bg-surface-container-low dark:bg-surface-container-lowest py-section-sm border-t border-outline-variant/30">
      <div class="max-w-container-max-lg mx-auto px-gutter">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-unit mb-12">
          <div class="space-y-6">
            <div class="font-headline-sm text-headline-sm text-primary dark:text-primary-fixed">Ngopidea</div>
            <p class="font-body-md text-body-md text-on-surface-variant max-w-xs">A sanctuary for the modern creative, serving artisanal blends and thoughtful moments since 2018.</p>
            <div class="flex gap-4">
              <a class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center hover:text-primary transition-colors" href="#">
                <span class="material-symbols-outlined" data-icon="coffee">coffee</span>
              </a>
              <a class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center hover:text-primary transition-colors" href="#">
                <span class="material-symbols-outlined" data-icon="alternate_email">alternate_email</span>
              </a>
              <a class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center hover:text-primary transition-colors" href="#">
                <span class="material-symbols-outlined" data-icon="location_on">location_on</span>
              </a>
            </div>
          </div>
          <div class="space-y-6 md:pl-20">
            <h5 class="font-label-md text-label-md text-on-surface uppercase tracking-widest font-bold">Explore</h5>
            <ul class="space-y-4">
              <li><a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors duration-300" href="#">Workshops</a></li>
              <li><a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors duration-300" href="#">Journal</a></li>
              <li><a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors duration-300" href="#">Wholesale</a></li>
              <li><a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors duration-300" href="#">Careers</a></li>
            </ul>
          </div>
          <div class="space-y-6">
            <h5 class="font-label-md text-label-md text-on-surface uppercase tracking-widest font-bold">Contact</h5>
            <ul class="space-y-4">
              <li class="font-body-md text-body-md text-on-surface-variant">123 Brew Avenue, Creative District</li>
              <li class="font-body-md text-body-md text-on-surface-variant">hello@ngopidea.cafe</li>
              <li class="font-body-md text-body-md text-on-surface-variant">+1 (555) 234-5678</li>
            </ul>
          </div>
        </div>
        <div class="flex flex-col md:flex-row justify-between items-center pt-8 border-t border-outline-variant/30 gap-6">
          <p class="font-label-md text-label-md text-on-surface-variant opacity-70">© 2024 Ngopidea Artisanal Cafe. All rights reserved.</p>
          <div class="flex gap-8">
            <a class="font-label-md text-label-md text-on-surface-variant hover:text-secondary transition-colors duration-300" href="#">Privacy Policy</a>
            <a class="font-label-md text-label-md text-on-surface-variant hover:text-secondary transition-colors duration-300" href="#">Terms of Service</a>
          </div>
        </div>
      </div>
    </footer>
    <script>
      // Simple parallax effect for images on scroll
      window.addEventListener("scroll", () => {
        const scroll = window.pageYOffset;
        const heroImages = document.querySelectorAll(".group-hover\\:scale-105, .hover\\:scale-110");
        heroImages.forEach((img) => {
          const speed = 0.05;
          img.style.transform = `translateY(${scroll * speed}px)`;
        });
      });

      // Sticky Header effect
      const header = document.querySelector("header");
      window.addEventListener("scroll", () => {
        if (window.scrollY > 50) {
          header.classList.add("shadow-md");
          header.classList.remove("shadow-sm");
        } else {
          header.classList.remove("shadow-md");
          header.classList.add("shadow-sm");
        }
      });
    </script>
  </body>
</html>

