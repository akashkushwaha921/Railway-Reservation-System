<?php
session_start();
include 'includes/db.php';

// Check login
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Fetch user bookings
$sql = "SELECT b.id, b.seats_booked, b.booking_date, b.train_id,
               t.train_name, t.source, t.destination, t.departure_time, t.arrival_time
        FROM bookings b
        JOIN trains t ON b.train_id = t.id
        WHERE b.user_id = $user_id
        ORDER BY b.booking_date DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>My Bookings ✦ Railway</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz@14..32&display=swap" rel="stylesheet">
    <style>
        body { font-family: "Inter", "Segoe UI", sans-serif; min-height: 100vh;
               background: linear-gradient(145deg, #0b1a3a, #1a3a6b, #2a5298);
               display: flex; flex-direction: column; align-items: center; padding: 20px; }
        .navbar { width: 100%; max-width: 1200px; margin: 0 auto 20px;
                  background: rgba(255,255,255,0.08); backdrop-filter: blur(18px);
                  border-radius: 60px; padding: 14px 32px; display: flex;
                  justify-content: space-between; align-items: center; color: #fff; }
        .navbar h1 { font-size: 24px; font-weight: 700;
                     background: linear-gradient(135deg, #f6e9a6, #f0d67a);
                     -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .nav-links a { color: rgba(255,255,255,0.75); text-decoration: none; margin-left: 28px; }
        .nav-links a:hover { color: #fff; }
        .nav-links a.active { color: #f6e9a6; }
        .container { width: 100%; max-width: 1100px; }
        .header { text-align: center; margin-bottom: 32px; }
        .header h1 { font-size: 36px; font-weight: 700; color: #fff; }
        .table-wrapper { background: rgba(255,255,255,0.05); backdrop-filter: blur(16px);
                         border-radius: 28px; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; color: #fff; }
        table th, table td { padding: 16px; border-bottom: 1px solid rgba(255,255,255,0.08); }
        table th { text-transform: uppercase; font-size: 13px; color: rgba(255,255,255,0.6); }
        table tr:hover { background: rgba(255,255,255,0.06); }
        .cancel-btn { background: linear-gradient(135deg, #e53935, #b71c1c);
                      color: #fff; padding: 6px 14px; border-radius: 20px;
                      text-decoration: none; font-size: 13px; font-weight: 600; }
        .cancel-btn:hover { background: #c62828; }
    
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
    <div class="navbar">
        <h1>🚆 Railway<span>Reservation</span></h1>
        <div class="nav-links">
            <a href="index.php">🏠 Home</a>
            <a href="search_train.php">🔍 Search</a>
            <a href="my_booking.php" class="active">📖 My Bookings</a>
        </div>
    </div>

    <div class="container">
        <div class="header">
            <h1>📖 My Bookings</h1>
            <p style="color:rgba(255,255,255,0.7)">Manage your ticket reservations</p>
        </div>

        <?php if($result->num_rows > 0): ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Train</th>
                            <th>Source</th>
                            <th>Destination</th>
                            <th>Departure</th>
                            <th>Arrival</th>
                            <th>Seats</th>
                            <th>Booked On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['train_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['source']); ?></td>
                                <td><?php echo htmlspecialchars($row['destination']); ?></td>
                                <td><?php echo date('h:i A', strtotime($row['departure_time'])); ?></td>
                                <td><?php echo date('h:i A', strtotime($row['arrival_time'])); ?></td>
                                <td><?php echo $row['seats_booked']; ?></td>
                                <td><?php echo date('d M Y, h:i A', strtotime($row['booking_date'])); ?></td>
                                <td><a href="cancel_ticket.php?booking_id=<?php echo $row['id']; ?>" class="cancel-btn">❌ Cancel</a></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="no-bookings">
                <h2>🚫 No Bookings Found!</h2>
                <p>You haven’t booked any tickets yet.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
