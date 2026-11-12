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

    // Step 1: Delete all bookings related to this train
    $delete_bookings = "DELETE FROM bookings WHERE train_id = $train_id";
    $conn->query($delete_bookings);

    // Step 2: Delete the train itself
    $delete_train = "DELETE FROM trains WHERE id = $train_id";
    if($conn->query($delete_train) === TRUE) {
        $message = "✅ Train and related bookings deleted successfully!";
    } else {
        $message = "❌ Error deleting train: " . $conn->error;
    }
} else {
    $message = "❌ Invalid request!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Delete Train ✦ Railway</title>
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
            max-width: 520px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-radius: 36px;
            padding: 48px 40px 44px;
            box-shadow:
                0 30px 80px rgba(0, 0, 0, 0.7),
                inset 0 1px 0 rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.06);
            text-align: center;
            animation: fadeUp 0.6s ease;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Icon ── */
        .result-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(4px);
        }

        .result-icon.success {
            background: rgba(125, 223, 160, 0.12);
            border-color: rgba(125, 223, 160, 0.2);
            color: #7ddfa0;
        }

        .result-icon.error {
            background: rgba(255, 138, 138, 0.12);
            border-color: rgba(255, 138, 138, 0.2);
            color: #ff8a8a;
        }

        /* ── Message ── */
        .message {
            font-size: 22px;
            font-weight: 700;
            margin: 12px 0 8px;
            line-height: 1.4;
            background: linear-gradient(135deg, #ffffff 60%, rgba(255, 255, 255, 0.5));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .sub-message {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.3);
            margin-top: 4px;
            -webkit-text-fill-color: rgba(255, 255, 255, 0.3);
        }

        /* ── Actions ── */
        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 32px;
            padding-top: 28px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            justify-content: center;
        }

        .actions .btn {
            flex: 1;
            min-width: 140px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 22px;
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

        .actions .btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .actions .btn-primary {
            background: linear-gradient(135deg, #f7c948, #f0a500);
            color: #0b1a2f;
            border: none;
        }

        .actions .btn-primary:hover {
            background: linear-gradient(135deg, #f7d04a, #e8a000);
            box-shadow: 0 6px 24px rgba(247, 201, 72, 0.25);
        }

        /* ── Responsive ── */
        @media (max-width: 480px) {
            .container {
                padding: 32px 20px 28px;
                border-radius: 28px;
            }

            .result-icon {
                width: 64px;
                height: 64px;
                font-size: 30px;
            }

            .message {
                font-size: 18px;
            }

            .actions .btn {
                min-width: 100%;
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

        <!-- ===== ICON ===== -->
        <div class="result-icon <?= strpos($message, '✅') !== false ? 'success' : 'error' ?>">
            <i class="<?= strpos($message, '✅') !== false ? 'fas fa-check-circle' : 'fas fa-exclamation-circle' ?>"></i>
        </div>

        <!-- ===== MESSAGE ===== -->
        <div class="message"><?= $message; ?></div>
        <div class="sub-message">
            <?= strpos($message, '✅') !== false ? 'The operation was completed successfully.' : 'Please try again or contact support.' ?>
        </div>

        <!-- ===== ACTION BUTTONS ===== -->
        <div class="actions">
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