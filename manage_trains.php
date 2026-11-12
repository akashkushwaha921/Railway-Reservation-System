<?php
session_start();
include 'includes/db.php';

if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$sql = "SELECT * FROM trains ORDER BY id ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Trains ✦ Railway</title>
    <!-- Google Fonts & Font Awesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
                radial-gradient(ellipse at 10% 20%, rgba(30, 100, 200, 0.25) 0%, transparent 50%),
                radial-gradient(ellipse at 90% 80%, rgba(0, 200, 255, 0.15) 0%, transparent 50%),
                linear-gradient(145deg, #0b1a2f 0%, #132a4a 100%);
            padding: 30px 20px;
        }

        /* ── Glass Container ── */
        .container {
            width: 100%;
            max-width: 1200px;
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 32px;
            padding: 36px 40px 44px;
            box-shadow:
                0 30px 80px rgba(0, 0, 0, 0.6),
                inset 0 1px 0 rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.06);
            transition: all 0.3s ease;
        }

        /* ── Header ── */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 32px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-icon {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #f7c948, #f0a500);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #0b1a2f;
            box-shadow: 0 8px 24px rgba(247, 201, 72, 0.3);
        }

        .header h1 {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #ffffff 60%, rgba(255, 255, 255, 0.6));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header h1 span {
            font-weight: 300;
            opacity: 0.5;
            -webkit-text-fill-color: rgba(255, 255, 255, 0.3);
        }

        .header-stats {
            display: flex;
            align-items: center;
            gap: 18px;
            flex-wrap: wrap;
        }

        .stat-badge {
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.06);
            padding: 8px 18px;
            border-radius: 60px;
            font-size: 14px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.75);
            display: flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(4px);
        }

        .stat-badge i {
            color: #f7c948;
            font-size: 14px;
        }

        .stat-badge strong {
            color: #fff;
            font-weight: 700;
        }

        /* ── Top Actions ── */
        .top-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 28px;
        }

        .actions-group {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.25s ease;
            border: none;
            cursor: pointer;
            font-family: inherit;
            letter-spacing: 0.2px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #f7c948, #f0a500);
            color: #0b1a2f;
            box-shadow: 0 6px 20px rgba(247, 201, 72, 0.25);
        }
        .btn-primary:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 10px 32px rgba(247, 201, 72, 0.35);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.10);
            backdrop-filter: blur(4px);
        }
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.20);
        }

        .btn-ghost {
            background: transparent;
            color: rgba(255, 255, 255, 0.6);
            padding: 10px 16px;
        }
        .btn-ghost:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.05);
        }

        .btn-sm {
            padding: 6px 14px;
            font-size: 12px;
            border-radius: 8px;
            gap: 5px;
        }

        .btn-edit {
            background: rgba(30, 144, 255, 0.20);
            color: #6ab0ff;
            border: 1px solid rgba(30, 144, 255, 0.15);
        }
        .btn-edit:hover {
            background: rgba(30, 144, 255, 0.30);
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(30, 144, 255, 0.20);
        }

        .btn-delete {
            background: rgba(255, 70, 70, 0.15);
            color: #ff6b6b;
            border: 1px solid rgba(255, 70, 70, 0.10);
        }
        .btn-delete:hover {
            background: rgba(255, 70, 70, 0.25);
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(255, 70, 70, 0.15);
        }

        /* ── Table Wrapper ── */
        .table-wrapper {
            overflow-x: auto;
            border-radius: 16px;
            background: rgba(0, 0, 0, 0.20);
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(4px);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            min-width: 640px;
        }

        thead {
            background: rgba(255, 255, 255, 0.04);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        thead th {
            padding: 16px 18px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: rgba(255, 255, 255, 0.50);
            border: none;
            white-space: nowrap;
        }

        thead th i {
            margin-right: 6px;
            font-size: 12px;
            opacity: 0.5;
        }

        tbody tr {
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            transition: background 0.2s ease;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        tbody td {
            padding: 14px 18px;
            color: rgba(255, 255, 255, 0.85);
            border: none;
            vertical-align: middle;
        }

        .train-name-cell {
            font-weight: 600;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .train-name-cell .train-emoji {
            font-size: 18px;
        }

        .route-cell {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.70);
        }

        .route-cell .arrow {
            color: rgba(255, 255, 255, 0.20);
            font-size: 12px;
        }

        .route-cell .station {
            background: rgba(255, 255, 255, 0.06);
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.80);
        }

        .seats-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.06);
            padding: 3px 14px 3px 10px;
            border-radius: 60px;
            font-weight: 600;
            color: #fff;
            font-size: 13px;
        }

        .seats-badge i {
            color: #f7c948;
            font-size: 13px;
        }

        .actions-cell {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: rgba(255, 255, 255, 0.40);
        }

        .empty-state i {
            font-size: 44px;
            margin-bottom: 14px;
            opacity: 0.3;
            display: block;
        }
        .empty-state p {
            font-size: 16px;
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .container {
                padding: 24px 16px 30px;
                border-radius: 20px;
            }

            .header {
                flex-direction: column;
                align-items: stretch;
                gap: 14px;
            }

            .header-left {
                justify-content: center;
            }

            .header h1 {
                font-size: 22px;
            }

            .header-stats {
                justify-content: center;
            }

            .top-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .actions-group {
                justify-content: center;
            }

            .btn {
                padding: 8px 16px;
                font-size: 13px;
                justify-content: center;
            }

            tbody td {
                padding: 10px 14px;
                font-size: 13px;
            }

            thead th {
                padding: 12px 14px;
                font-size: 10px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 16px 10px 22px;
            }

            .header h1 {
                font-size: 18px;
            }

            .header-icon {
                width: 40px;
                height: 40px;
                font-size: 20px;
            }

            .stat-badge {
                font-size: 11px;
                padding: 4px 12px;
            }

            .btn {
                font-size: 12px;
                padding: 6px 12px;
                gap: 4px;
            }

            .btn-sm {
                font-size: 10px;
                padding: 4px 10px;
            }

            tbody td {
                padding: 8px 10px;
                font-size: 12px;
            }

            .train-name-cell {
                font-size: 13px;
            }

            .seats-badge {
                font-size: 11px;
                padding: 2px 10px 2px 8px;
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
            <div class="header-left">
                <div class="header-icon">
                    <i class="fas fa-train"></i>
                </div>
                <h1>Manage Trains <span>✦</span></h1>
            </div>
            <div class="header-stats">
                <div class="stat-badge">
                    <i class="fas fa-route"></i>
                    Total <strong><?= $result->num_rows ?></strong>
                </div>
                <div class="stat-badge">
                    <i class="fas fa-clock"></i>
                    Live
                </div>
            </div>
        </div>

        <!-- ===== ACTIONS ===== -->
        <div class="top-actions">
            <div class="actions-group">
                <a href="add_train.php" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Add New Train
                </a>
                <a href="admin_dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Dashboard
                </a>
            </div>
            <div class="actions-group">
                <button class="btn btn-ghost" onclick="window.location.reload();">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>

        <!-- ===== TABLE ===== -->
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> ID</th>
                        <th><i class="fas fa-train"></i> Train</th>
                        <th><i class="fas fa-map-pin"></i> Route</th>
                        <th><i class="fas fa-chair"></i> Seats</th>
                        <th><i class="fas fa-tools"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td style="font-weight:600; color:rgba(255,255,255,0.4);">#<?= $row['id']; ?></td>
                                <td>
                                    <div class="train-name-cell">
                                        <span class="train-emoji">🚆</span>
                                        <?= htmlspecialchars($row['train_name']); ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="route-cell">
                                        <span class="station"><?= htmlspecialchars($row['source']); ?></span>
                                        <span class="arrow"><i class="fas fa-arrow-right"></i></span>
                                        <span class="station"><?= htmlspecialchars($row['destination']); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="seats-badge">
                                        <i class="fas fa-chair"></i> <?= $row['seats']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="actions-cell">
                                        <a href="edit_train.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-edit">
                                            <i class="fas fa-pen"></i> Edit
                                        </a>
                                        <a href="delete_train.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-delete">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="fas fa-train"></i>
                                    <p>No trains found. <a href="add_train.php" style="color:#f7c948; text-decoration:none; font-weight:600;">Add your first train →</a></p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>