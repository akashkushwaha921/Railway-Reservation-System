<?php
session_start();
include 'includes/db.php';

if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$message = "";
if(isset($_GET['id'])) {
    $train_id = intval($_GET['id']);
    $sql = "SELECT * FROM trains WHERE id = $train_id";
    $result = $conn->query($sql);
    $train = $result->fetch_assoc();

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $train_name = $_POST['train_name'];
        $source = $_POST['source'];
        $destination = $_POST['destination'];
        $departure_time = $_POST['departure_time'];
        $arrival_time = $_POST['arrival_time'];
        $seats = intval($_POST['seats']);

        $update_sql = "UPDATE trains 
                       SET train_name='$train_name', source='$source', destination='$destination', 
                           departure_time='$departure_time', arrival_time='$arrival_time', seats=$seats 
                       WHERE id=$train_id";

        if($conn->query($update_sql) === TRUE) {
            $message = "✅ Train updated successfully!";
        } else {
            $message = "❌ Error: " . $conn->error;
        }
    }
} else {
    die("❌ Invalid request!");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Train ✦ Railway</title>
    <!-- Google Fonts & Font Awesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet" />
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0b1a2f;
            background-image:
                radial-gradient(ellipse at 10% 20%, rgba(30, 100, 200, 0.3) 0%, transparent 55%),
                radial-gradient(ellipse at 90% 80%, rgba(0, 200, 255, 0.2) 0%, transparent 55%),
                linear-gradient(145deg, #0b1a2f 0%, #132a4a 100%);
            padding: 30px 20px;
            animation: bgShift 15s ease infinite alternate;
        }

        @keyframes bgShift {
            0% { background-position: 0% 50%; }
            100% { background-position: 100% 50%; }
        }

        /* ── Glass Container ── */
        .container {
            width: 100%;
            max-width: 580px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-radius: 36px;
            padding: 44px 40px 48px;
            box-shadow:
                0 30px 80px rgba(0, 0, 0, 0.7),
                inset 0 1px 0 rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.06);
            transition: all 0.3s ease;
            animation: fadeUp 0.6s ease;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Header ── */
        .header {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 30px;
            padding-bottom: 22px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .header-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #f7c948, #f0a500);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #0b1a2f;
            box-shadow: 0 8px 28px rgba(247, 201, 72, 0.35);
            flex-shrink: 0;
        }

        .header-text h1 {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #ffffff 60%, rgba(255, 255, 255, 0.5));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header-text p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.35);
            margin-top: 2px;
            -webkit-text-fill-color: rgba(255, 255, 255, 0.35);
        }

        /* ── Message ── */
        .message {
            padding: 14px 20px;
            border-radius: 14px;
            margin-bottom: 26px;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(4px);
            animation: fadeUp 0.4s ease;
        }

        .message.success {
            color: #7ddfa0;
            border-color: rgba(125, 223, 160, 0.2);
            background: rgba(125, 223, 160, 0.08);
        }

        .message.error {
            color: #ff8a8a;
            border-color: rgba(255, 138, 138, 0.2);
            background: rgba(255, 138, 138, 0.08);
        }

        /* ── Form ── */
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.2);
            font-size: 16px;
            transition: color 0.3s;
            pointer-events: none;
            z-index: 1;
        }

        .input-group input {
            width: 100%;
            padding: 15px 16px 15px 50px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 16px;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            color: #fff;
            transition: all 0.25s ease;
            outline: none;
            backdrop-filter: blur(4px);
        }

        .input-group input::placeholder {
            color: rgba(255, 255, 255, 0.2);
            font-weight: 400;
        }

        .input-group input:focus {
            border-color: rgba(247, 201, 72, 0.45);
            background: rgba(255, 255, 255, 0.09);
            box-shadow: 0 0 0 4px rgba(247, 201, 72, 0.06);
        }

        .input-group input:focus + i {
            color: #f7c948;
        }

        .input-group input[type="time"] {
            padding-left: 50px;
            color-scheme: dark;
        }

        .input-group input[type="time"]::-webkit-calendar-picker-indicator {
            filter: invert(1) brightness(0.7);
            opacity: 0.4;
        }

        .input-group input[type="number"]::-webkit-outer-spin-button,
        .input-group input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .input-group input[type="number"] {
            -moz-appearance: textfield;
        }

        .row-2col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        /* ── Update Button ── */
        .btn-submit {
            width: 100%;
            padding: 17px;
            background: linear-gradient(135deg, #f7c948, #f0a500);
            border: none;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 700;
            color: #0b1a2f;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 6px 28px rgba(247, 201, 72, 0.2);
            margin-top: 6px;
            font-family: 'Inter', sans-serif;
        }

        .btn-submit:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 10px 40px rgba(247, 201, 72, 0.3);
        }

        .btn-submit:active {
            transform: scale(0.97);
        }

        /* ── Bottom Actions ── */
        .bottom-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 26px;
            padding-top: 22px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .bottom-actions .btn {
            flex: 1;
            min-width: 120px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 13px 18px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.25s ease;
            border: none;
            cursor: pointer;
            font-family: inherit;
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(4px);
        }

        .bottom-actions .btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .bottom-actions .btn-primary {
            background: linear-gradient(135deg, #f7c948, #f0a500);
            color: #0b1a2f;
            border: none;
        }

        .bottom-actions .btn-primary:hover {
            background: linear-gradient(135deg, #f7d04a, #e8a000);
            box-shadow: 0 6px 24px rgba(247, 201, 72, 0.25);
        }

        /* ── Responsive ── */
        @media (max-width: 640px) {
            .container {
                padding: 28px 18px 32px;
                border-radius: 28px;
            }

            .header-text h1 {
                font-size: 22px;
            }

            .header-icon {
                width: 48px;
                height: 48px;
                font-size: 22px;
            }

            .row-2col {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .input-group input {
                padding: 13px 14px 13px 46px;
                font-size: 14px;
            }

            .btn-submit {
                font-size: 15px;
                padding: 15px;
            }

            .bottom-actions {
                flex-direction: column;
            }

            .bottom-actions .btn {
                flex: 1;
                padding: 14px;
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

    <div class="container">

        <!-- ===== HEADER ===== -->
        <div class="header">
            <div class="header-icon">
                <i class="fas fa-pencil-alt"></i>
            </div>
            <div class="header-text">
                <h1>Edit Train</h1>
                <p>Update the train information</p>
            </div>
        </div>

        <!-- ===== MESSAGE ===== -->
        <?php if($message): ?>
            <div class="message <?= strpos($message, '✅') !== false ? 'success' : 'error' ?>">
                <?= $message; ?>
            </div>
        <?php endif; ?>

        <!-- ===== FORM ===== -->
        <form method="POST">
            <div class="input-group">
                <i class="fas fa-train"></i>
                <input type="text" name="train_name" value="<?= htmlspecialchars($train['train_name']); ?>" placeholder="Train Name" required />
            </div>

            <div class="row-2col">
                <div class="input-group">
                    <i class="fas fa-map-pin"></i>
                    <input type="text" name="source" value="<?= htmlspecialchars($train['source']); ?>" placeholder="Source" required />
                </div>
                <div class="input-group">
                    <i class="fas fa-flag-checkered"></i>
                    <input type="text" name="destination" value="<?= htmlspecialchars($train['destination']); ?>" placeholder="Destination" required />
                </div>
            </div>

            <div class="row-2col">
                <div class="input-group">
                    <i class="fas fa-clock"></i>
                    <input type="time" name="departure_time" value="<?= htmlspecialchars($train['departure_time']); ?>" required />
                </div>
                <div class="input-group">
                    <i class="fas fa-clock"></i>
                    <input type="time" name="arrival_time" value="<?= htmlspecialchars($train['arrival_time']); ?>" required />
                </div>
            </div>

            <div class="input-group">
                <i class="fas fa-chair"></i>
                <input type="number" name="seats" value="<?= htmlspecialchars($train['seats']); ?>" placeholder="Total Seats" required />
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-sync-alt"></i> Update Train
            </button>
        </form>

        <!-- ===== BOTTOM ACTIONS ===== -->
        <div class="bottom-actions">
            <a href="manage_trains.php" class="btn">
                <i class="fas fa-arrow-left"></i> Manage Trains
            </a>
            <a href="admin_dashboard.php" class="btn btn-primary">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </div>

    </div>

</body>
</html>