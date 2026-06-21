<?php 
require_once 'includes/auth.php';
require_once 'includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($full_name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = "Email address is already registered.";
        } else {
            $role_name = 'Pelanggan';
            $role_stmt = $conn->prepare("SELECT id FROM roles WHERE role_name = ?");
            $role_stmt->bind_param("s", $role_name);
            $role_stmt->execute();
            $role_result = $role_stmt->get_result();
            
            if ($role_result->num_rows === 0) {
                $conn->query("INSERT INTO roles (role_name) VALUES ('Pelanggan')");
                $role_id = $conn->insert_id;
            } else {
                $role_row = $role_result->fetch_assoc();
                $role_id = $role_row['id'];
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_user = $conn->prepare("INSERT INTO users (role_id, email, password) VALUES (?, ?, ?)");
            $insert_user->bind_param("iss", $role_id, $email, $hashed_password);
            
            if ($insert_user->execute()) {
                $user_id = $conn->insert_id;
                
                $insert_customer = $conn->prepare("INSERT INTO customers (user_id, full_name) VALUES (?, ?)");
                $insert_customer->bind_param("is", $user_id, $full_name);
                $insert_customer->execute();

                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $full_name;
                
                header("Location: menu.php");
                exit();
            } else {
                $error = "An error occurred during registration. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html><html class="scroll-smooth" lang="en" style=""><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Join the Ritual | Ngopidea Artisanal Coffee</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&amp;family=Merriweather:wght@400;700&amp;family=Plus+Jakarta+Sans:wght@500;600;700&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@100..900&amp;family=Playfair+Display:wght@100..900&amp;family=Plus+Jakarta+Sans:wght@100..900&amp;display=swap" rel="stylesheet">
<link href="style.css" rel="stylesheet">
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "on-error-container": "#93000a",
                    "surface-container-low": "#fff1e9",
                    "secondary-container": "#ffcf99",
                    "on-secondary-fixed-variant": "#604016",
                    "error-container": "#ffdad6",
                    "surface-container-highest": "#f2dfd3",
                    "primary-fixed": "#ffdbc9",
                    "inverse-surface": "#392e26",
                    "surface-variant": "#f2dfd3",
                    "surface-container-high": "#f7e5d9",
                    "secondary": "#7b572b",
                    "error": "#ba1a1a",
                    "on-secondary-fixed": "#2b1700",
                    "surface-container-lowest": "#ffffff",
                    "outline": "#8c7264",
                    "surface-container": "#fdeade",
                    "primary-container": "#ff790b",
                    "on-surface-variant": "#584236",
                    "surface-tint": "#9a4600",
                    "on-tertiary-container": "#263849",
                    "on-secondary": "#ffffff",
                    "on-primary-fixed-variant": "#763300",
                    "tertiary-fixed-dim": "#b5c9de",
                    "tertiary-fixed": "#d1e5fb",
                    "surface-bright": "#fff8f5",
                    "primary-fixed-dim": "#ffb68d",
                    "on-primary-container": "#5d2700",
                    "surface-dim": "#e9d7cb",
                    "on-primary": "#ffffff",
                    "primary": "#9a4600",
                    "tertiary": "#4d6073",
                    "on-error": "#ffffff",
                    "inverse-primary": "#ffb68d",
                    "on-tertiary": "#ffffff",
                    "on-secondary-container": "#7a562a",
                    "background": "#fff8f5",
                    "secondary-fixed-dim": "#edbe89",
                    "on-surface": "#231a13",
                    "inverse-on-surface": "#ffede3",
                    "tertiary-container": "#8ea2b6",
                    "secondary-fixed": "#ffddb9",
                    "surface": "#fff8f5",
                    "on-tertiary-fixed-variant": "#36495a",
                    "on-background": "#231a13",
                    "outline-variant": "#e0c0b0",
                    "on-primary-fixed": "#321200",
                    "on-tertiary-fixed": "#081d2d"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
            },
            "spacing": {
                    "unit": "8px",
                    "section-lg": "100px",
                    "gutter": "20px",
                    "section-md": "80px",
                    "container-max-md": "1100px",
                    "section-sm": "40px",
                    "container-max-lg": "1200px"
            },
            "fontFamily": {
                    "headline-sm": ["Playfair Display"],
                    "body-lg": ["Merriweather"],
                    "label-md": ["Plus Jakarta Sans"],
                    "display-hero-mobile": ["Playfair Display"],
                    "body-md": ["Merriweather"],
                    "display-hero": ["Playfair Display"],
                    "headline-lg-mobile": ["Playfair Display"],
                    "headline-md": ["Playfair Display"],
                    "headline-lg": ["Playfair Display"]
            },
            "fontSize": {
                    "headline-sm": ["24px", {"lineHeight": "1.4", "fontWeight": "700"}],
                    "body-lg": ["18px", {"lineHeight": "1.8", "fontWeight": "400"}],
                    "label-md": ["14px", {"lineHeight": "1.2", "fontWeight": "600"}],
                    "display-hero-mobile": ["35px", {"lineHeight": "1.2", "letterSpacing": "1px", "fontWeight": "700"}],
                    "body-md": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}],
                    "display-hero": ["72px", {"lineHeight": "1.2", "letterSpacing": "2px", "fontWeight": "700"}],
                    "headline-lg-mobile": ["32px", {"lineHeight": "1.2", "letterSpacing": "0px", "fontWeight": "700"}],
                    "headline-md": ["40px", {"lineHeight": "1.3", "fontWeight": "700"}],
                    "headline-lg": ["56px", {"lineHeight": "1.2", "letterSpacing": "-1px", "fontWeight": "700"}]
            }
          },
        },
      }
    </script>
<style>
      .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
      }
      .amber-glow:hover {
        box-shadow: 0 10px 30px rgba(138, 62, 0, 0.2);
        transform: translateY(-2px);
      }
    </style>
</head>
<body class="bg-background text-on-background font-body-md selection:bg-secondary-container selection:text-on-secondary-container">
<!-- TopNavBar -->
<?php include 'includes/navbar.php'; ?>
<main class="min-h-[calc(100vh-160px)] flex items-center justify-center pt-32 pb-section-md px-gutter relative overflow-hidden bg-surface-container-low">
<div class="w-full max-w-[500px]">
<div class="bg-white p-8 md:p-12 rounded-[24px] shadow-xl border border-outline/10 relative z-10">
<div class="text-center mb-10">
<h1 class="font-headline-md text-headline-md text-on-surface mb-3">Join the Community</h1>
<p class="font-body-md text-body-md text-on-surface-variant">Start your artisanal coffee journey today.</p>
</div>

<?php if (!empty($error)): ?>
<div class="mb-6 p-4 bg-error-container text-on-error-container rounded-lg text-sm font-label-md">
    <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<form action="signup.php" class="space-y-6" method="POST">
<div>
<label class="block font-label-md text-label-md text-on-surface mb-1.5 ml-1" for="full_name">Full Name</label>
<input class="w-full px-4 py-3 rounded-xl border border-outline/30 bg-surface-container-lowest focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all duration-200 text-on-surface" id="full_name" name="full_name" placeholder="Alex Mercer" required="" type="text">
</div>
<div>
<label class="block font-label-md text-label-md text-on-surface mb-1.5 ml-1" for="email">Email Address</label>
<input class="w-full px-4 py-3 rounded-xl border border-outline/30 bg-surface-container-lowest focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all duration-200 text-on-surface" id="email" name="email" placeholder="name@example.com" required="" type="email">
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div>
<label class="block font-label-md text-label-md text-on-surface mb-1.5 ml-1" for="password">Password</label>
<input class="w-full px-4 py-3 rounded-xl border border-outline/30 bg-surface-container-lowest focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all duration-200 text-on-surface" id="password" name="password" placeholder="••••••••" required="" type="password">
</div>
<div>
<label class="block font-label-md text-label-md text-on-surface mb-1.5 ml-1" for="confirm_password">Confirm Password</label>
<input class="w-full px-4 py-3 rounded-xl border border-outline/30 bg-surface-container-lowest focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all duration-200 text-on-surface" id="confirm_password" name="confirm_password" placeholder="••••••••" required="" type="password">
</div>
</div>
<div class="pt-4">
<button class="w-full py-4 bg-primary-container text-on-primary-container rounded-full font-label-md text-label-md amber-glow transition-all duration-300 shadow-lg shadow-primary-container/20" type="submit">
                            Create Account
                        </button>
</div>
<div class="relative py-4 flex items-center">
<div class="flex-grow border-t border-outline-variant"></div>
<span class="flex-shrink mx-4 text-label-md text-outline font-label-md uppercase tracking-widest text-[10px]">or continue with</span>
<div class="flex-grow border-t border-outline-variant"></div>
</div>
<button class="w-full py-3.5 bg-surface-container-lowest border border-outline/20 text-on-surface rounded-full font-label-md text-label-md flex items-center justify-center gap-3 hover:bg-surface-bright hover:shadow-sm transition-all duration-300" type="button">
<svg class="w-5 h-5" viewBox="0 0 24 24">
<path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path>
<path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path>
<path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"></path>
<path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"></path>
</svg>
                        Sign up with Google
                    </button>
</form>
<div class="mt-8 text-center">
<p class="font-body-md text-body-md text-on-surface-variant">
                        Already have an account? 
                        <a class="text-primary font-bold hover:underline ml-1" href="signin.php">Login</a>
</p>
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
        // Simple micro-interaction for input fields
        const inputs = document.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.querySelector('label').classList.add('text-primary');
            });
            input.addEventListener('blur', () => {
                input.parentElement.querySelector('label').classList.remove('text-primary');
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