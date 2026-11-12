<?php
session_start();
include 'includes/db.php';

// Check train_id
if (!isset($_GET['train_id'])) {
    die("❌ Train ID not provided!");
}
$train_id = $_GET['train_id'];

// Check login
if (!isset($_SESSION['user_id'])) {
    die("❌ Please login first to book tickets!");
}
$user_id = $_SESSION['user_id'];

$message = "";

// Fetch train details
$train_sql = "SELECT * FROM trains WHERE id = $train_id";
$train_result = $conn->query($train_sql);
$train = $train_result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seats = $_POST['seats'];

    // Insert booking
    $sql = "INSERT INTO bookings (user_id, train_id, seats_booked) VALUES ('$user_id', '$train_id', '$seats')";
    if ($conn->query($sql)) {
        $conn->query("UPDATE trains SET seats = seats - $seats WHERE id=$train_id");
        $message = "✅ Booking successful!";
    } else {
        $message = "❌ Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Ticket ✦ Railway</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <style>
        /* ─── RESET & BASE ─── */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: "Inter", "Segoe UI", sans-serif;
            min-height: 100vh;
            background: linear-gradient(145deg, #0b1a3a 0%, #1a3a6b 50%, #2a5298 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0 20px 40px;
        }

        /* ─── GLASS NAVBAR ─── */
        .navbar {
            width: 100%;
            max-width: 1200px;
            margin: 20px auto 10px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.10);
            border-radius: 60px;
            padding: 14px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.35);
            transition: all 0.3s ease;
        }
        .navbar:hover {
            border-color: rgba(255, 255, 255, 0.20);
            box-shadow: 0 16px 56px rgba(0, 0, 0, 0.45);
        }
        .navbar h1 {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.3px;
            background: linear-gradient(135deg, #f6e9a6, #f0d67a);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .navbar h1 span {
            font-weight: 300;
            opacity: 0.7;
            -webkit-text-fill-color: rgba(255, 255, 255, 0.5);
        }
        .nav-links a {
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            margin-left: 28px;
            font-weight: 500;
            font-size: 15px;
            transition: 0.25s ease;
            position: relative;
            letter-spacing: 0.2px;
        }
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0%;
            height: 2px;
            background: linear-gradient(90deg, #f6e9a6, #f0d67a);
            transition: 0.3s ease;
        }
        .nav-links a:hover {
            color: #fff;
        }
        .nav-links a:hover::after {
            width: 100%;
        }
        .nav-links a:first-child {
            margin-left: 0;
        }

        /* ─── MAIN CARD ─── */
        .booking-container {
            width: 100%;
            max-width: 620px;
            margin: 36px auto 20px;
            background: rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(22px);
            -webkit-backdrop-filter: blur(22px);
            border: 1px solid rgba(255, 255, 255, 0.10);
            border-radius: 32px;
            padding: 38px 40px 44px;
            box-shadow:
                0 30px 70px rgba(0, 0, 0, 0.45),
                inset 0 1px 0 rgba(255, 255, 255, 0.06);
            animation: floatUp 0.9s cubic-bezier(0.22, 1, 0.36, 1) forwards;
            transform-origin: center bottom;
        }
        @keyframes floatUp {
            0% {
                opacity: 0;
                transform: translateY(40px) scale(0.96);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .booking-container h2 {
            font-size: 32px;
            font-weight: 700;
            text-align: center;
            color: #fff;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        .booking-container h2 .emoji {
            display: inline-block;
            animation: wiggle 3s infinite ease-in-out;
        }
        @keyframes wiggle {
            0%,
            100% {
                transform: rotate(0deg);
            }
            5% {
                transform: rotate(12deg);
            }
            10% {
                transform: rotate(-8deg);
            }
            15% {
                transform: rotate(6deg);
            }
            20% {
                transform: rotate(0deg);
            }
        }
        .sub-head {
            text-align: center;
            color: rgba(255, 255, 255, 0.45);
            font-size: 14px;
            font-weight: 400;
            margin-top: -4px;
            margin-bottom: 28px;
            letter-spacing: 0.3px;
        }

        /* ─── TRAIN DETAILS ─── */
        .train-details {
            background: rgba(255, 255, 255, 0.06);
            border-radius: 20px;
            padding: 20px 24px;
            margin-bottom: 28px;
            border-left: 4px solid #f6e9a6;
            backdrop-filter: blur(4px);
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px 18px;
            transition: 0.3s ease;
        }
        .train-details:hover {
            background: rgba(255, 255, 255, 0.09);
            border-left-width: 6px;
        }
        .train-details p {
            margin: 5px 0;
            font-size: 14.5px;
            color: rgba(255, 255, 255, 0.82);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .train-details p strong {
            color: rgba(255, 255, 255, 0.50);
            font-weight: 500;
            font-size: 13px;
            letter-spacing: 0.2px;
            min-width: 82px;
        }
        .train-details p .value {
            font-weight: 500;
            color: #fff;
        }
        .train-details .highlight {
            color: #f6e9a6;
            font-weight: 600;
        }
        .train-details .seats-badge {
            background: rgba(246, 233, 166, 0.15);
            padding: 2px 14px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            color: #f6e9a6;
            display: inline-block;
        }

        /* ─── FORM ─── */
        .form-group {
            margin-bottom: 24px;
        }
        .form-group label {
            display: block;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.70);
            font-size: 14px;
            letter-spacing: 0.3px;
            margin-bottom: 8px;
        }
        .form-group input {
            width: 100%;
            padding: 14px 18px;
            background: rgba(255, 255, 255, 0.07);
            border: 1.5px solid rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            font-size: 17px;
            font-weight: 500;
            color: #fff;
            outline: none;
            transition: 0.3s ease;
            font-family: inherit;
        }
        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.25);
            font-weight: 400;
        }
        .form-group input:focus {
            border-color: #f6e9a6;
            background: rgba(255, 255, 255, 0.10);
            box-shadow: 0 0 0 4px rgba(246, 233, 166, 0.10);
        }
        .form-group input[type="number"] {
            -moz-appearance: textfield;
        }
        .form-group input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
        }

        /* ─── BUTTONS ─── */
        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 6px;
        }
        .btn {
            flex: 1 1 auto;
            padding: 14px 28px;
            border: none;
            border-radius: 40px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s ease;
            text-align: center;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            letter-spacing: 0.2px;
            font-family: inherit;
            background: rgba(255, 255, 255, 0.06);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .btn-primary {
            background: linear-gradient(135deg, #f6e9a6, #e8d070);
            color: #0b1a3a;
            border: none;
            box-shadow: 0 6px 24px rgba(246, 233, 166, 0.25);
        }
        .btn-primary:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 12px 40px rgba(246, 233, 166, 0.35);
        }
        .btn-primary:active {
            transform: scale(0.97);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.80);
        }
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.12);
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.25);
        }

        .btn-back {
            background: rgba(255, 87, 34, 0.15);
            border: 1px solid rgba(255, 87, 34, 0.20);
            color: #ff8a65;
        }
        .btn-back:hover {
            background: rgba(255, 87, 34, 0.25);
            transform: translateY(-2px);
            border-color: rgba(255, 87, 34, 0.35);
        }

        /* ─── MESSAGE ─── */
        .message {
            margin-top: 26px;
            padding: 16px 22px;
            border-radius: 16px;
            font-weight: 500;
            font-size: 16px;
            text-align: center;
            backdrop-filter: blur(6px);
            border: 1px solid transparent;
            animation: fadeSlide 0.5s ease forwards;
        }
        @keyframes fadeSlide {
            0% {
                opacity: 0;
                transform: translateY(12px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .message.success {
            background: rgba(76, 175, 80, 0.15);
            border-color: rgba(76, 175, 80, 0.25);
            color: #a5d6a7;
        }
        .message.error {
            background: rgba(211, 47, 47, 0.15);
            border-color: rgba(211, 47, 47, 0.25);
            color: #ef9a9a;
        }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 640px) {
            .navbar {
                flex-direction: column;
                gap: 10px;
                padding: 16px 20px;
                border-radius: 40px;
            }
            .navbar h1 {
                font-size: 20px;
            }
            .nav-links a {
                margin: 0 12px;
                font-size: 14px;
            }
            .booking-container {
                padding: 28px 20px 32px;
                border-radius: 24px;
                margin-top: 24px;
            }
            .booking-container h2 {
                font-size: 26px;
            }
            .train-details {
                grid-template-columns: 1fr;
                gap: 2px;
                padding: 16px 18px;
            }
            .train-details p {
                font-size: 13.5px;
            }
            .train-details p strong {
                min-width: 70px;
            }
            .btn-group {
                flex-direction: column;
            }
            .btn {
                padding: 14px 20px;
                font-size: 15px;
            }
        }

        @media (max-width: 400px) {
            .booking-container {
                padding: 20px 14px 26px;
            }
            .booking-container h2 {
                font-size: 22px;
            }
            .navbar h1 {
                font-size: 17px;
            }
            .nav-links a {
                font-size: 12px;
                margin: 0 8px;
            }
        }

        /* ─── SCROLLBAR ─── */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        /* tiny extra touch */
        .train-details .icon {
            opacity: 0.5;
            font-size: 16px;
        }
        .train-name-large {
            grid-column: 1 / -1;
            font-size: 18px !important;
            font-weight: 600 !important;
            color: #fff !important;
            margin-bottom: 2px;
        }
        .train-name-large strong {
            font-weight: 400 !important;
            color: rgba(255, 255, 255, 0.40) !important;
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

    <!-- ─── NAVBAR ─── -->
    <div class="navbar">
        <h1>🚆 Railway<span>Reservation</span></h1>
        <div class="nav-links">
            <a href="index.php">🏠 Home</a>
            <a href="search_train.php">🔍 Search</a>
        </div>
    </div>

    <!-- ─── MAIN CARD ─── -->
    <div class="booking-container">
        <h2><span class="emoji">🎟</span> Book Your Ticket</h2>
        <p class="sub-head">Secure your seat in seconds</p>

        <!-- Train Details -->
        <div class="train-details">
            <p class="train-name-large">
                <strong>🚂</strong> <?php echo htmlspecialchars($train['train_name']); ?>
            </p>
            <p><strong>📍 Source</strong> <span class="value"><?php echo htmlspecialchars($train['source']); ?></span></p>
            <p><strong>📍 Destination</strong> <span class="value"><?php echo htmlspecialchars($train['destination']); ?></span></p>
            <p><strong>⏰ Departure</strong> <span class="value"><?php echo date('h:i A', strtotime($train['departure_time'])); ?></span></p>
            <p><strong>⏰ Arrival</strong> <span class="value"><?php echo date('h:i A', strtotime($train['arrival_time'])); ?></span></p>
            <p style="grid-column: 1 / -1; margin-top: 2px;">
                <strong>💺 Seats</strong>
                <span class="seats-badge"><?php echo $train['seats']; ?> available</span>
            </p>
        </div>

        <!-- Booking Form -->
        <form method="post">
            <div class="form-group">
                <label for="seats">How many seats would you like?</label>
                <input type="number" name="seats" id="seats" min="1" max="<?php echo $train['seats']; ?>"
                placeholder="e.g. 2" required>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">✅ Confirm Booking</button>
                <a href="search_train.php" class="btn btn-back">⬅ Back to Search</a>
            </div>
        </form>

        <!-- Message -->
        <?php if (!empty($message)): ?>
            <div class="message <?php echo strpos($message, '✅') !== false ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>