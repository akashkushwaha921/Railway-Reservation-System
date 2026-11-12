<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Railway Reservation System</title>
    <!-- Google Fonts & Font Awesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700;14..32,800;14..32,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        /* ── Reset & Base ── */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: #0b1a2f;
            color: #e2e8f0;
            line-height: 1.6;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
        }

        /* ── Navbar ── */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            background: rgba(11, 26, 47, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            padding: 12px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.4);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            transition: 0.3s;
        }

        .navbar .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .navbar .logo img {
            height: 45px;
            width: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #f7c948;
            transition: 0.3s;
        }

        .navbar .logo img:hover {
            transform: rotate(-8deg) scale(1.05);
            box-shadow: 0 0 20px rgba(247, 201, 72, 0.5);
        }

        .navbar .logo span {
            font-size: 24px;
            font-weight: 800;
            background: linear-gradient(135deg, #f7c948, #f0a500);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
        }

        .navbar .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .navbar .nav-links a {
            padding: 8px 16px;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 600;
            font-size: 15px;
            border-radius: 30px;
            transition: all 0.25s ease;
            letter-spacing: 0.2px;
            position: relative;
        }

        .navbar .nav-links a:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
        }

        .navbar .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 2px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: #f7c948;
            transition: width 0.3s ease;
        }

        .navbar .nav-links a:hover::after {
            width: 60%;
        }

        .navbar .nav-links .btn-nav {
            background: #f7c948;
            color: #0b1a2f !important;
            padding: 8px 20px;
            border-radius: 30px;
            font-weight: 700;
            transition: 0.3s;
            box-shadow: 0 4px 16px rgba(247, 201, 72, 0.25);
        }

        .navbar .nav-links .btn-nav:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(247, 201, 72, 0.4);
            background: #f7d04a;
        }

        .navbar .nav-links .btn-nav::after {
            display: none;
        }

        /* ── Hero Section ── */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0b1a2f;
            overflow: hidden;
            padding-top: 80px;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            background-image: url('assets/train1.png');
            background-size: cover;
            background-position: center;
            opacity: 0.2;           /* Reduced for transparency */
            z-index: 0;
            filter: blur(0.5px) brightness(0.8);
            transition: opacity 0.5s;
        }

        .hero-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(11, 26, 47, 0.5) 0%, rgba(11, 26, 47, 0.1) 100%);
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 900px;
            text-align: center;
            padding: 40px 24px;
            color: #fff;
            animation: fadeUp 1s ease;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero-content .badge {
            display: inline-block;
            background: rgba(247, 201, 72, 0.15);
            border: 1px solid rgba(247, 201, 72, 0.25);
            padding: 6px 20px;
            border-radius: 60px;
            font-size: 14px;
            font-weight: 600;
            color: #f7c948;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
            backdrop-filter: blur(4px);
        }

        .hero-content h1 {
            font-size: clamp(2.6rem, 8vw, 4.8rem);
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #ffffff 40%, rgba(255, 255, 255, 0.5));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-content p {
            font-size: clamp(1rem, 2vw, 1.3rem);
            color: rgba(255, 255, 255, 0.85);
            max-width: 600px;
            margin: 0 auto 32px;
            font-weight: 400;
        }

        .hero-buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 16px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 16px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }

        .btn-primary {
            background: linear-gradient(135deg, #f7c948, #f0a500);
            color: #0b1a2f;
            box-shadow: 0 8px 32px rgba(247, 201, 72, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 12px 48px rgba(247, 201, 72, 0.45);
        }

        .btn-outline {
            background: transparent;
            color: #fff;
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(4px);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: #f7c948;
            transform: translateY(-3px);
        }

        .btn i {
            font-size: 18px;
        }

        /* ── Features Section (Dark) ── */
        .features {
            padding: 80px 20px;
            background: #0b1a2f;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.04);
        }

        .features .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            font-size: clamp(2rem, 4vw, 2.8rem);
            font-weight: 800;
            color: #fff;
            margin-bottom: 12px;
        }

        .section-sub {
            color: rgba(255, 255, 255, 0.5);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto 48px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            padding: 36px 24px;
            border-radius: 24px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.06);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            border-color: #f7c948;
            background: rgba(255, 255, 255, 0.07);
        }

        .feature-card .icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #f7c948, #f0a500);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 28px;
            color: #0b1a2f;
            box-shadow: 0 8px 24px rgba(247, 201, 72, 0.25);
        }

        .feature-card h3 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: #fff;
        }

        .feature-card p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.95rem;
        }

        /* ── CTA Banner ── */
        .cta-banner {
            padding: 60px 20px;
            background: linear-gradient(135deg, #0b1a2f, #1e3a5f);
            color: #fff;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.04);
        }

        .cta-banner h2 {
            font-size: clamp(1.8rem, 3vw, 2.6rem);
            font-weight: 800;
            margin-bottom: 12px;
        }

        .cta-banner p {
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 28px;
            font-size: 1.1rem;
        }

        /* ── Footer ── */
        .footer {
            background: #0b1a2f;
            color: rgba(255, 255, 255, 0.6);
            padding: 40px 20px 30px;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .footer .footer-links {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .footer .footer-links a {
            color: rgba(255, 255, 255, 0.5);
            font-weight: 500;
            transition: 0.3s;
            font-size: 14px;
        }

        .footer .footer-links a:hover {
            color: #f7c948;
        }

        .footer .social-icons {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-bottom: 20px;
        }

        .footer .social-icons a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.5);
            transition: 0.3s;
            border: 1px solid rgba(255, 255, 255, 0.04);
        }

        .footer .social-icons a:hover {
            background: #f7c948;
            color: #0b1a2f;
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(247, 201, 72, 0.2);
        }

        .footer .copy {
            font-size: 13px;
            opacity: 0.5;
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .navbar {
                padding: 12px 20px;
                flex-direction: column;
                gap: 8px;
                align-items: stretch;
            }

            .navbar .logo {
                justify-content: center;
            }

            .navbar .nav-links {
                justify-content: center;
                gap: 4px;
            }

            .navbar .nav-links a {
                font-size: 13px;
                padding: 6px 12px;
            }

            .hero-content h1 {
                font-size: 2.4rem;
            }

            .features-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 480px) {
            .navbar .nav-links a {
                font-size: 11px;
                padding: 4px 10px;
            }

            .hero-content h1 {
                font-size: 2rem;
            }

            .btn {
                padding: 10px 20px;
                font-size: 14px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }
        }
    
/* ===== Responsive layout improvements (mobile, tablet and desktop) ===== */
html { width: 100%; max-width: 100%; -webkit-text-size-adjust: 100%; }
*, *::before, *::after { box-sizing: border-box; }
body { max-width: 100%; overflow-x: hidden; }
img, video, canvas, svg { max-width: 100%; }
input, select, textarea, button { max-width: 100%; }
input, select, textarea { min-width: 0; }
table { max-width: 100%; }
.table-wrapper, .table-container, .table-responsive {
    max-width: 100%;
    overflow-x: auto !important;
    -webkit-overflow-scrolling: touch;
}
@media (max-width: 768px) {
    body { padding-left: 14px !important; padding-right: 14px !important; }
    .navbar {
        width: 100% !important; max-width: 100% !important;
        border-radius: 20px !important; padding: 14px 16px !important;
        gap: 12px; flex-wrap: wrap; align-items: center;
    }
    .navbar h1, .logo { font-size: clamp(18px, 5vw, 23px) !important; }
    .nav-links {
        width: 100%; display: flex !important; flex-wrap: wrap;
        justify-content: center; align-items: center; gap: 8px 14px;
    }
    .nav-links a { margin: 0 !important; font-size: 14px; }
    .container, .booking-container, .dashboard-wrapper, .form-container,
    .login-card, .login-box, main, .content {
        width: 100% !important; max-width: 100% !important;
    }
    .hero { min-height: auto; }
    .hero-content { padding: 48px 18px !important; }
    .hero h1, .header h1, h1 { overflow-wrap: anywhere; }
    .hero h1 { font-size: clamp(30px, 8vw, 48px) !important; }
    .header h1, .section-title { font-size: clamp(25px, 6vw, 36px) !important; }
    .features-grid, .dashboard-grid { grid-template-columns: repeat(2, minmax(0, 1fr)) !important; }
    .row-2col { grid-template-columns: minmax(0, 1fr) !important; }
    .form-container, .login-card, .login-box, .booking-container,
    .card, .container { padding-left: 18px; padding-right: 18px; }
    .table-wrapper { border-radius: 16px !important; }
    table { min-width: 680px; }
    th, td { white-space: nowrap; }
    .top-actions, .actions-group, .btn-group, .bottom-actions {
        display: flex; flex-wrap: wrap; gap: 10px; align-items: stretch;
    }
    .top-actions > *, .actions-group > *, .btn-group > * { max-width: 100%; }
    .header, .header-left, .header-right { flex-wrap: wrap; gap: 12px; }
    .header-stats { display: flex; flex-wrap: wrap; gap: 8px; }
    .btn, button, input[type="submit"] { min-height: 42px; }
    input, select, textarea { width: 100%; font-size: 16px !important; }
}
@media (max-width: 480px) {
    body { padding: 12px !important; }
    .features-grid, .dashboard-grid { grid-template-columns: minmax(0, 1fr) !important; }
    .form-container, .login-card, .login-box { padding: 28px 20px !important; border-radius: 22px !important; }
    .brand-badge { top: 10px; max-width: calc(100% - 24px); font-size: 10px; letter-spacing: 1.5px; padding: 6px 12px; }
    .hero-buttons, .actions { flex-direction: column; align-items: stretch; }
    .hero-buttons .btn, .actions .btn { width: 100%; justify-content: center; }
    .stat-badge { white-space: normal; }
    .header { text-align: center; }
    .header-left { justify-content: center; }
}

</style>
</head>
<body>

    <!-- ===== NAVBAR ===== -->
    <div class="navbar">
        <div class="logo">
            <img src="assets/log.png" alt="Rail Logo" />
            <span>Railway System</span>
        </div>
        <div class="nav-links">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <a href="search_train.php"><i class="fas fa-search"></i> Search Trains</a>
            <a href="my_booking.php"><i class="fas fa-ticket-alt"></i> My Booking</a>
            <a href="admin_login.php"><i class="fas fa-user-shield"></i> Admin</a>
            <a href="search_train.php" class="btn-nav"><i class="fas fa-bookmark"></i> Book</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            <?php else: ?>
                <a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                <a href="register.php"><i class="fas fa-user-plus"></i> Register</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- ===== HERO ===== -->
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="hero-content">
            <div class="badge"><i class="fas fa-train"></i> India’s Trusted Railway Platform</div>
            <h1>Book Your Journey<br />With Confidence</h1>
            <p>Experience seamless train reservations, real-time availability, and secure payments — all in one place.</p>
            <div class="hero-buttons">
                <?php if(!isset($_SESSION['user_id'])): ?>
                    <a href="register.php" class="btn btn-primary"><i class="fas fa-user-plus"></i> Get Started</a>
                    <a href="login.php" class="btn btn-outline"><i class="fas fa-sign-in-alt"></i> Login</a>
                <?php else: ?>
                    <a href="search_train.php" class="btn btn-primary"><i class="fas fa-search"></i> Search Trains</a>
                    <a href="my_booking.php" class="btn btn-outline"><i class="fas fa-ticket-alt"></i> My Bookings</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ===== FEATURES (Dark) ===== -->
    <section class="features">
        <div class="container">
            <h2 class="section-title">Why Choose Us</h2>
            <p class="section-sub">We make train travel effortless with modern features designed for you.</p>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="icon"><i class="fas fa-bolt"></i></div>
                    <h3>Instant Booking</h3>
                    <p>Book your tickets in seconds with real-time seat availability and instant confirmation.</p>
                </div>
                <div class="feature-card">
                    <div class="icon"><i class="fas fa-lock"></i></div>
                    <h3>Secure Payments</h3>
                    <p>Your transactions are protected with industry‑grade encryption and secure gateways.</p>
                </div>
                <div class="feature-card">
                    <div class="icon"><i class="fas fa-headset"></i></div>
                    <h3>24/7 Support</h3>
                    <p>Our dedicated team is always ready to help you with any query or issue.</p>
                </div>
                <div class="feature-card">
                    <div class="icon"><i class="fas fa-undo-alt"></i></div>
                    <h3>Easy Cancellation</h3>
                    <p>Manage your bookings with flexible cancellation and hassle‑free refunds.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== CTA BANNER ===== -->
    <section class="cta-banner">
        <div class="container">
            <h2>Ready to explore the rails?</h2>
            <p>Join thousands of happy passengers and start your journey today.</p>
            <a href="search_train.php" class="btn btn-primary"><i class="fas fa-arrow-right"></i> Search Trains</a>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="footer">
        <div class="social-icons">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-youtube"></i></a>
        </div>
        <div class="footer-links">
            <a href="about.php">About Us</a>
            <a href="contact.php">Contact</a>
            <a href="privacy.php">Privacy Policy</a>
            <a href="terms.php">Terms of Service</a>
        </div>
        <div class="copy">
            &copy; 2026 Railway Reservation System | All Rights Reserved
        </div>
    </footer>

</body>
</html>