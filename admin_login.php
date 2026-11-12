<?php
session_start();
include 'includes/db.php';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if($result && $result->num_rows > 0) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "❌ Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login ✦ Railway</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            background: radial-gradient(ellipse at 30% 20%, #0b1a33 0%, #1a2f4f 50%, #0f2847 100%);
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
            filter: blur(140px);
            opacity: 0.3;
            z-index: 0;
            animation: floatOrb 22s ease-in-out infinite alternate;
        }
        body::before {
            width: 600px;
            height: 600px;
            background: #4facfe;
            top: -200px;
            right: -150px;
        }
        body::after {
            width: 500px;
            height: 500px;
            background: #00c6ff;
            bottom: -150px;
            left: -100px;
            animation-delay: 9s;
        }

        @keyframes floatOrb {
            0% {
                transform: translate(0, 0) scale(1);
            }
            100% {
                transform: translate(50px, -60px) scale(1.15);
            }
        }

        /* ── Brand Badge ── */
        .brand-badge {
            position: fixed;
            top: 28px;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(255, 255, 255, 0.12);
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            z-index: 1;
            pointer-events: none;
            backdrop-filter: blur(4px);
            padding: 8px 24px;
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            background: rgba(255, 255, 255, 0.02);
        }
        .brand-badge span {
            color: #FFD700;
        }

        /* ── Login Box ── */
        .login-box {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(24px) saturate(1.4);
            -webkit-backdrop-filter: blur(24px) saturate(1.4);
            padding: 44px 38px 38px;
            border-radius: 32px;
            border: 1px solid rgba(255, 255, 255, 0.10);
            box-shadow:
                0 30px 80px rgba(0, 0, 0, 0.6),
                inset 0 1px 0 rgba(255, 255, 255, 0.08);
            animation: slideUp 0.9s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            transition: box-shadow 0.4s;
            text-align: center;
        }
        .login-box:hover {
            box-shadow:
                0 40px 100px rgba(0, 0, 0, 0.7),
                inset 0 1px 0 rgba(255, 255, 255, 0.12);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.97);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* ── Header ── */
        .login-box h2 {
            font-size: 30px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.5px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .login-box h2 .highlight {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .login-box .subhead {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
            font-weight: 400;
            margin-bottom: 28px;
            letter-spacing: 0.3px;
        }
        .login-box .subhead span {
            color: #FFD700;
            font-weight: 500;
        }

        /* ── Error Message ── */
        .error {
            padding: 14px 18px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 20px;
            text-align: left;
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            animation: fadeSlide 0.4s ease;
            background: rgba(231, 76, 60, 0.18);
            color: #f5a89f;
            border-left: 4px solid #e74c3c;
        }

        @keyframes fadeSlide {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Form ── */
        form {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .input-group {
            position: relative;
            margin: 8px 0 4px;
        }

        .input-group .icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            opacity: 0.7;
            pointer-events: none;
            transition: opacity 0.3s;
            line-height: 1;
        }

        .input-group input {
            width: 100%;
            padding: 16px 48px 16px 48px;
            background: rgba(255, 255, 255, 0.07);
            border: 1.5px solid rgba(255, 255, 255, 0.10);
            border-radius: 16px;
            font-size: 15px;
            font-weight: 450;
            color: #fff;
            transition: all 0.3s ease;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
            font-family: inherit;
        }
        .input-group input::placeholder {
            color: rgba(255, 255, 255, 0.35);
            font-weight: 400;
            letter-spacing: 0.2px;
        }
        .input-group input:focus {
            outline: none;
            border-color: #FFD700;
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 0 4px rgba(255, 215, 0, 0.12), inset 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        .input-group input:focus + .icon,
        .input-group input:focus ~ .icon {
            opacity: 1;
        }

        /* Toggle password */
        .input-group .toggle-pass {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.5);
            transition: all 0.3s;
            user-select: none;
            padding: 4px;
            border-radius: 8px;
            line-height: 1;
        }
        .input-group .toggle-pass:hover {
            color: #FFD700;
            background: rgba(255, 215, 0, 0.08);
        }

        /* ── Submit Button ── */
        .login-box button {
            width: 100%;
            padding: 16px 20px;
            margin-top: 14px;
            background: linear-gradient(135deg, #FFD700, #F5A623);
            border: none;
            border-radius: 16px;
            font-size: 17px;
            font-weight: 700;
            color: #0b1a33;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 6px 24px rgba(255, 215, 0, 0.25);
            letter-spacing: 0.3px;
            font-family: inherit;
            position: relative;
            overflow: hidden;
        }
        .login-box button::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #FFE066, #FFB300);
            opacity: 0;
            transition: opacity 0.4s;
            border-radius: 16px;
        }
        .login-box button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 36px rgba(255, 215, 0, 0.35);
        }
        .login-box button:hover::after {
            opacity: 1;
        }
        .login-box button:active {
            transform: scale(0.97) translateY(0);
            box-shadow: 0 4px 16px rgba(255, 215, 0, 0.2);
        }
        .login-box button span {
            position: relative;
            z-index: 1;
        }

        /* ── Footer Links ── */
        .links-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 22px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .back-home {
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.25s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 40px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }
        .back-home:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.12);
            transform: translateX(-2px);
        }

        /* ── Footer Note ── */
        .login-box .footer-note {
            margin-top: 24px;
            text-align: center;
            color: rgba(255, 255, 255, 0.25);
            font-size: 12px;
            font-weight: 400;
            letter-spacing: 0.5px;
            border-top: 1px solid rgba(255, 255, 255, 0.04);
            padding-top: 18px;
        }
        .login-box .footer-note span {
            color: rgba(255, 215, 0, 0.4);
        }

        /* ── Responsive ── */
        @media (max-width: 480px) {
            .login-box {
                padding: 32px 24px 28px;
                border-radius: 24px;
            }
            .login-box h2 {
                font-size: 26px;
            }
            .input-group input {
                padding: 14px 44px 14px 44px;
                font-size: 14px;
            }
            .login-box button {
                font-size: 15px;
                padding: 14px;
            }
            .links-row {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }
            .back-home {
                justify-content: center;
            }
            .brand-badge {
                font-size: 11px;
                padding: 6px 16px;
                top: 18px;
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

    <!-- Brand badge -->
    <div class="brand-badge">🚆 Rail<span>Ease</span> · Admin</div>

    <div class="login-box">
        <h2>
            <span></span>
            <span class="highlight">Admin</span> Access
        </h2>
        <p class="subhead">Secure login for <span>railway management</span></p>

        <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>

        <form method="post">
            <div class="input-group">
                <span class="icon"></span>
                <input type="text" name="username" placeholder="Admin username" required>
            </div>

            <div class="input-group">
                <span class="icon"></span>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <span class="toggle-pass" onclick="togglePassword()">👁️</span>
            </div>

            <button type="submit" onclick="this.innerHTML='<span>⏳ Authenticating…</span>'">
                <span>🚀 &nbsp;Login</span>
            </button>
        </form>

        <div class="links-row">
            <a href="index.php" class="back-home">← Back to Home</a>
        </div>

        <div class="footer-note">
            <span>✦</span> Restricted &bull; Authorized personnel only <span>✦</span>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passField = document.getElementById("password");
            if (passField.type === "password") {
                passField.type = "text";
            } else {
                passField.type = "password";
            }
        }
    </script>

</body>
</html>