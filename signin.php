<?php 
require_once 'includes/auth.php';
require_once 'includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Email and password are required.";
    } else {
        $stmt = $conn->prepare("
            SELECT u.id, u.password, u.role_id, c.full_name as customer_name, e.full_name as employee_name 
            FROM users u
            LEFT JOIN customers c ON u.id = c.user_id
            LEFT JOIN employees e ON u.id = e.user_id
            WHERE u.email = ?
        ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role_id'] = $user['role_id'];
                
                $name = $user['customer_name'] ?? $user['employee_name'] ?? 'User';
                $_SESSION['user_name'] = $name;
                
                if ($user['role_id'] == 1) {
                    header("Location: menu.php");
                } elseif ($user['role_id'] == 2) {
                    header("Location: admin/dashboard_staff.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html><html class="light" lang="en"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Login - Ngopidea Artisanal Coffee</title>
<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&amp;family=Merriweather:wght@400;700&amp;family=Plus+Jakarta+Sans:wght@500;600;700&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@100..900&amp;family=Playfair+Display:wght@100..900&amp;family=Plus+Jakarta+Sans:wght@100..900&amp;display=swap" rel="stylesheet">
<link href="style.css" rel="stylesheet">
<!-- Tailwind -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "surface-container-highest": "#f2dfd3",
                    "background": "#fff8f5",
                    "on-secondary-container": "#7a562a",
                    "primary-container": "#ff790b",
                    "outline": "#8c7264",
                    "inverse-primary": "#ffb68d",
                    "on-tertiary": "#ffffff",
                    "on-tertiary-fixed-variant": "#36495a",
                    "surface-container-lowest": "#ffffff",
                    "on-primary-fixed-variant": "#763300",
                    "primary": "#9a4600",
                    "surface-dim": "#e9d7cb",
                    "on-tertiary-container": "#263849",
                    "inverse-on-surface": "#ffede3",
                    "on-tertiary-fixed": "#081d2d",
                    "on-error-container": "#93000a",
                    "error": "#ba1a1a",
                    "tertiary": "#4d6073",
                    "tertiary-container": "#8ea2b6",
                    "on-primary-container": "#5d2700",
                    "surface-container-high": "#f7e5d9",
                    "surface": "#fff8f5",
                    "surface-container-low": "#fff1e9",
                    "secondary-container": "#ffcf99",
                    "on-secondary": "#ffffff",
                    "on-background": "#231a13",
                    "secondary-fixed": "#ffddb9",
                    "on-surface-variant": "#584236",
                    "outline-variant": "#e0c0b0",
                    "on-secondary-fixed": "#2b1700",
                    "tertiary-fixed": "#d1e5fb",
                    "on-primary-fixed": "#321200",
                    "secondary": "#7b572b",
                    "surface-variant": "#f2dfd3",
                    "on-secondary-fixed-variant": "#604016",
                    "on-error": "#ffffff",
                    "on-primary": "#ffffff",
                    "primary-fixed": "#ffdbc9",
                    "secondary-fixed-dim": "#edbe89",
                    "surface-container": "#fdeade",
                    "primary-fixed-dim": "#ffb68d",
                    "on-surface": "#231a13",
                    "inverse-surface": "#392e26",
                    "surface-tint": "#9a4600",
                    "surface-bright": "#fff8f5",
                    "tertiary-fixed-dim": "#b5c9de",
                    "error-container": "#ffdad6"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
            },
            "spacing": {
                    "container-max-lg": "1200px",
                    "gutter": "20px",
                    "container-max-md": "1100px",
                    "unit": "8px",
                    "section-sm": "40px",
                    "section-md": "80px",
                    "section-lg": "100px"
            },
            "fontFamily": {
                    "body-md": ["Merriweather"],
                    "label-md": ["Plus Jakarta Sans"],
                    "headline-sm": ["Playfair Display"],
                    "display-hero-mobile": ["Playfair Display"],
                    "body-lg": ["Merriweather"],
                    "headline-lg-mobile": ["Playfair Display"],
                    "headline-md": ["Playfair Display"],
                    "display-hero": ["Playfair Display"],
                    "headline-lg": ["Playfair Display"]
            },
            "fontSize": {
                    "body-md": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}],
                    "label-md": ["14px", {"lineHeight": "1.2", "fontWeight": "600"}],
                    "headline-sm": ["24px", {"lineHeight": "1.4", "fontWeight": "700"}],
                    "display-hero-mobile": ["35px", {"lineHeight": "1.2", "letterSpacing": "1px", "fontWeight": "700"}],
                    "body-lg": ["18px", {"lineHeight": "1.8", "fontWeight": "400"}],
                    "headline-lg-mobile": ["32px", {"lineHeight": "1.2", "letterSpacing": "0px", "fontWeight": "700"}],
                    "headline-md": ["40px", {"lineHeight": "1.3", "fontWeight": "700"}],
                    "display-hero": ["72px", {"lineHeight": "1.2", "letterSpacing": "2px", "fontWeight": "700"}],
                    "headline-lg": ["56px", {"lineHeight": "1.2", "letterSpacing": "-1px", "fontWeight": "700"}]
            }
          },
        },
      }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
        }
        .btn-hover-lift {
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.2s ease;
        }
        .btn-hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(138, 62, 0, 0.3);
        }
        .glass-effect {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-background text-on-background min-h-screen flex flex-col font-body-md selection:bg-primary-fixed-dim selection:text-on-primary-fixed">
<!-- TopNavBar -->
<?php include 'includes/navbar.php'; ?>
<!-- Main Content Canvas -->
<main class="flex-grow flex items-center justify-center relative overflow-hidden bg-surface-container-low px-gutter pt-32 pb-section-md">
<!-- Login Container -->
<div class="relative w-full max-w-[480px] z-10">
<div class="bg-white border border-outline/10 shadow-xl rounded-[24px] p-8 md:p-12">
<!-- Header -->
<div class="text-center mb-10">
<h1 class="font-headline-md text-headline-md text-primary mb-2">Welcome Back</h1>
<p class="text-on-surface-variant font-body-md text-body-md">The ritual awaits your return.</p>
</div>

<?php if (!empty($error)): ?>
<div class="mb-6 p-4 bg-error-container text-on-error-container rounded-lg text-sm font-label-md">
    <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<form action="signin.php" class="space-y-6" method="POST">
<!-- Email Field -->
<div class="space-y-2">
<label class="block font-label-md text-label-md text-on-surface-variant ml-1" for="email">Email Address</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline/60">mail</span>
<input class="w-full pl-12 pr-4 py-4 bg-surface-container-lowest border border-outline/30 rounded-xl focus:ring-2 focus:ring-primary-container/30 focus:border-primary-container transition-all outline-none text-on-surface" id="email" name="email" placeholder="ritual@ngopidea.com" required="" type="email">
</div>
</div>
<!-- Password Field -->
<div class="space-y-2">
<label class="block font-label-md text-label-md text-on-surface-variant ml-1" for="password">Password</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline/60">lock</span>
<input class="w-full pl-12 pr-12 py-4 bg-surface-container-lowest border border-outline/30 rounded-xl focus:ring-2 focus:ring-primary-container/30 focus:border-primary-container transition-all outline-none text-on-surface" id="password" name="password" placeholder="••••••••" required="" type="password">
<button class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline/60 hover:text-primary transition-colors" type="button">visibility</button>
</div>
</div>
<!-- Options -->
<div class="flex items-center justify-between text-label-md font-label-md">
<label class="flex items-center gap-2 cursor-pointer group">
<input class="w-5 h-5 rounded-md border-outline/30 text-primary focus:ring-primary-container transition-all cursor-pointer" type="checkbox">
<span class="text-on-surface-variant group-hover:text-primary transition-colors">Remember Me</span>
</label>
<a class="text-primary hover:text-primary-container transition-colors" href="#">Forgot Password?</a>
</div>
<!-- Submit -->
<button class="w-full py-4 bg-primary-container text-on-primary-container font-label-md text-label-md rounded-full btn-hover-lift shadow-lg shadow-primary-container/20" type="submit">Order Ahead</button>
</form>
<!-- Divider -->
<div class="relative my-8 text-center">
<div class="absolute inset-0 flex items-center">
<div class="w-full border-t border-outline/10"></div>
</div>
<span class="relative px-4 bg-surface text-label-md text-outline font-label-md">or continue with</span>
</div>
<!-- Social Login -->
<button class="w-full py-3.5 bg-surface-container-lowest border border-outline/20 text-on-surface rounded-full font-label-md text-label-md flex items-center justify-center gap-3 hover:bg-surface-bright transition-all" type="button">
<svg class="w-5 h-5" viewBox="0 0 24 24">
<path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path>
<path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path>
<path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"></path>
<path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"></path>
</svg>
                        Continue with Google
                    </button>
<!-- Register Link -->
<div class="mt-8 text-center text-on-surface-variant text-label-md font-label-md">
                    New to Ngopidea? 
                    <a class="text-primary hover:text-primary-container font-bold transition-colors ml-1" href="signup.php">Join our Community</a>
</div>
</div>
</div>
</main>
<!-- Footer -->
<footer class="mt-auto py-8 bg-surface-container border-t border-outline/10 text-on-surface">
<div class="max-w-container-max-lg mx-auto px-gutter flex flex-col md:flex-row justify-between items-center gap-4">
<div class="flex flex-col items-center md:items-start">
<h2 class="font-headline-sm text-primary mb-1 text-lg">Ngopidea</h2>
<p class="font-body-md text-[13px] text-on-surface-variant opacity-80">© 2024 Ngopidea Artisanal Coffee. Crafted for Clarity.</p>
</div>
<div class="flex gap-6 font-label-md text-[12px] text-on-surface-variant">
<a class="hover:text-primary transition-colors" href="#">Privacy Policy</a>
<a class="hover:text-primary transition-colors" href="#">Terms of Service</a>
<a class="hover:text-primary transition-colors" href="#">Shipping &amp; Returns</a>
<a class="hover:text-primary transition-colors" href="#">Wholesale</a>
</div>
<div class="flex gap-4 text-on-surface-variant">
<span class="material-symbols-outlined text-[20px] hover:text-primary cursor-pointer transition-colors">public</span>
<span class="material-symbols-outlined text-[20px] hover:text-primary cursor-pointer transition-colors">coffee</span>
</div>
</div>
</footer>
<script>
        // Simple input focus effects
        const inputs = document.querySelectorAll('input');
        inputs.forEach(input => {
            const container = input.parentElement;
            const icon = container.querySelector('.material-symbols-outlined');
            const label = input.closest('div.space-y-2').querySelector('label');
            
            input.addEventListener('focus', () => {
                if(icon) icon.classList.add('text-primary');
                if(label) label.classList.add('text-primary');
            });
            input.addEventListener('blur', () => {
                if(icon) icon.classList.remove('text-primary');
                if(label) label.classList.remove('text-primary');
            });
        });

        // Sticky header transition
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if(header) {
                if (window.scrollY > 20) {
                    header.classList.add('py-2');
                    header.classList.remove('py-4');
                } else {
                    header.classList.remove('py-2');
                    header.classList.add('py-4');
                }
            }
        });
    </script>
</body></html>