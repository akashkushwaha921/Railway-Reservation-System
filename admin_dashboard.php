<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard ✦ Railway</title>
    <style>
        /* ── Reset & Base ── */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            background: radial-gradient(ellipse at 20% 30%, #0b1a33 0%, #1a2f4f 60%, #0f2847 100%);
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* ── Animated background orbs ── */
        body::before,
        body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(150px);
            opacity: 0.2;
            z-index: 0;
            animation: floatOrb 24s ease-in-out infinite alternate;
        }
        body::before {
            width: 700px;
            height: 700px;
            background: #4facfe;
            top: -250px;
            right: -150px;
        }
        body::after {
            width: 600px;
            height: 600px;
            background: #00c6ff;
            bottom: -200px;
            left: -100px;
            animation-delay: 10s;
        }

        @keyframes floatOrb {
            0% {
                transform: translate(0, 0) scale(1);
            }
            100% {
                transform: translate(60px, -50px) scale(1.2);
            }
        }

        /* ── Container ── */
        .dashboard-wrapper {
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* ── Header ── */
        .header {
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(20px) saturate(1.4);
            -webkit-backdrop-filter: blur(20px) saturate(1.4);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 24px 36px;
            margin-bottom: 36px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .header-left .brand-icon {
            font-size: 32px;
            background: linear-gradient(135deg, #FFD700, #FFA500);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .header-left h1 {
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.5px;
        }
        .header-left h1 span {
            color: #FFD700;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 18px;
            flex-wrap: wrap;
        }
        .header-right .greeting {
            color: rgba(255, 255, 255, 0.7);
            font-size: 16px;
            font-weight: 500;
        }
        .header-right .greeting strong {
            color: #FFD700;
            font-weight: 600;
        }
        .header-right .logout-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            background: rgba(255, 215, 0, 0.15);
            border: 1px solid rgba(255, 215, 0, 0.25);
            border-radius: 40px;
            color: #FFD700;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s;
        }
        .header-right .logout-btn:hover {
            background: rgba(255, 215, 0, 0.25);
            border-color: #FFD700;
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.15);
            transform: translateY(-2px);
        }

        /* ── Dashboard Grid ── */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 28px;
            margin-bottom: 40px;
            animation: fadeUp 1s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Cards ── */
        .card {
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(16px) saturate(1.3);
            -webkit-backdrop-filter: blur(16px) saturate(1.3);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 32px 24px 28px;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 48px rgba(0, 0, 0, 0.4);
            border-color: rgba(255, 215, 0, 0.2);
            background: rgba(255, 255, 255, 0.09);
        }

        .card .card-icon {
            font-size: 44px;
            line-height: 1;
            margin-bottom: 4px;
        }
        .card h2 {
            font-size: 20px;
            font-weight: 600;
            color: #fff;
            letter-spacing: -0.3px;
        }
        .card p {
            color: rgba(255, 255, 255, 0.5);
            font-size: 14px;
            margin-bottom: 8px;
        }
        .card .card-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 28px;
            background: linear-gradient(135deg, #FFD700, #F5A623);
            border-radius: 40px;
            color: #0b1a33;
            font-weight: 700;
            font-size: 15px;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 4px 16px rgba(255, 215, 0, 0.2);
            margin-top: 4px;
        }
        .card .card-link:hover {
            transform: scale(1.04);
            box-shadow: 0 8px 28px rgba(255, 215, 0, 0.35);
        }
        .card .card-link:active {
            transform: scale(0.97);
        }

        /* ── Logout card special ── */
        .card.logout-card .card-link {
            background: rgba(231, 76, 60, 0.2);
            color: #ff6b6b;
            box-shadow: none;
            border: 1px solid rgba(231, 76, 60, 0.3);
        }
        .card.logout-card .card-link:hover {
            background: rgba(231, 76, 60, 0.3);
            border-color: #e74c3c;
            box-shadow: 0 8px 28px rgba(231, 76, 60, 0.2);
        }

        /* ── Footer ── */
        .footer {
            margin-top: 40px;
            padding: 24px 20px;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            color: rgba(255, 255, 255, 0.25);
            font-size: 14px;
            font-weight: 400;
            letter-spacing: 0.3px;
        }
        .footer a {
            color: rgba(255, 215, 0, 0.5);
            text-decoration: none;
            margin: 0 12px;
            transition: color 0.3s;
        }
        .footer a:hover {
            color: #FFD700;
        }
        .footer .footer-divider {
            color: rgba(255, 255, 255, 0.08);
            margin: 0 4px;
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
                padding: 20px 24px;
            }
            .header-right {
                width: 100%;
                justify-content: space-between;
            }
            .header-left h1 {
                font-size: 22px;
            }
            .dashboard-grid {
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 20px;
            }
            .card {
                padding: 24px 16px 22px;
            }
            .card .card-icon {
                font-size: 36px;
            }
            .card h2 {
                font-size: 18px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 12px;
            }
            .header {
                padding: 16px 18px;
                border-radius: 18px;
            }
            .header-left h1 {
                font-size: 20px;
            }
            .header-right .greeting {
                font-size: 14px;
            }
            .header-right .logout-btn {
                padding: 8px 16px;
                font-size: 13px;
            }
            .dashboard-grid {
                grid-template-columns: 1fr 1fr;
                gap: 14px;
            }
            .card {
                padding: 20px 12px 18px;
                border-radius: 18px;
            }
            .card .card-icon {
                font-size: 30px;
            }
            .card h2 {
                font-size: 16px;
            }
            .card p {
                font-size: 12px;
            }
            .card .card-link {
                padding: 8px 18px;
                font-size: 13px;
            }
            .footer {
                font-size: 12px;
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
    <div class="dashboard-wrapper">

        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <span class="brand-icon">🚆</span>
                <h1>Rail<span>Ease</span> · Admin</h1>
            </div>
            <div class="header-right">
                <span class="greeting">👋 Welcome, <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong></span>
                <a href="logout.php" class="logout-btn">🚪 Logout</a>
            </div>
        </header>

        <!-- Dashboard Cards -->
        <div class="dashboard-grid">
            <!-- Manage Trains -->
            <div class="card">
                <div class="card-icon">🚆</div>
                <h2>Manage Trains</h2>
                <p>Add, edit, or remove train schedules</p>
                <a href="manage_trains.php" class="card-link">Go →</a>
            </div>

            <!-- Manage Bookings -->
            <div class="card">
                <div class="card-icon">🎟️</div>
                <h2>Manage Bookings</h2>
                <p>View and manage all passenger bookings</p>
                <a href="manage_bookings.php" class="card-link">Go →</a>
            </div>

            <!-- Manage Users -->
            <div class="card">
                <div class="card-icon">👥</div>
                <h2>Manage Users</h2>
                <p>View registered users and their activity</p>
                <a href="manage_users.php" class="card-link">Go →</a>
            </div>

            <!-- Logout (special style) -->
            <div class="card logout-card">
                <div class="card-icon">🚪</div>
                <h2>Logout</h2>
                <p>End your admin session securely</p>
                <a href="logout.php" class="card-link">Logout</a>
            </div>
        </div>

        <!-- Footer -->
        <footer class="footer">
            <p>&copy; 2026 Railway Reservation System &bull; Admin Panel</p>
            <p style="margin-top: 6px;">
                <a href="index.php">🏠 Home</a>
                <span class="footer-divider">|</span>
                <a href="contact.php">📧 Support</a>
            </p>
        </footer>

    </div>
</body>
</html>