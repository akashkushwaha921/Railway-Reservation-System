<?php
session_start();
include 'includes/db.php';

// Check login
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

if(!isset($_GET['booking_id'])) {
    die("❌ Booking ID not provided!");
}
$booking_id = intval($_GET['booking_id']);

// Get booking details
$sql = "SELECT * FROM bookings WHERE id=$booking_id AND user_id=$user_id";
$result = $conn->query($sql);

if($result && $result->num_rows > 0) {
    $booking = $result->fetch_assoc();
    $seats = $booking['seats_booked'];
    $train_id = $booking['train_id'];

    // Delete booking
    $conn->query("DELETE FROM bookings WHERE id=$booking_id AND user_id=$user_id");

    // Restore seats
    $conn->query("UPDATE trains SET seats = seats + $seats WHERE id=$train_id");

    $message = "✅ Your booking has been cancelled successfully!";
} else {
    $message = "❌ Booking not found or already cancelled!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Cancel Ticket ✦ Railway</title>
    <style>
        body { font-family: "Inter", "Segoe UI", sans-serif; background: linear-gradient(145deg,#0b1a3a,#1a3a6b,#2a5298);
               min-height:100vh; display:flex; justify-content:center; align-items:center; color:#fff; }
        .cancel-box { background: rgba(255,255,255,0.08); backdrop-filter: blur(16px);
                      padding:40px; border-radius:20px; text-align:center; max-width:500px; }
        h2 { margin-bottom:20px; }
        a { display:inline-block; margin-top:20px; padding:10px 20px; border-radius:20px;
            background:#4caf50; color:#fff; text-decoration:none; font-weight:bold; }
        a:hover { background:#388e3c; }
    
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
    <div class="cancel-box">
        <h2><?php echo $message; ?></h2>
        <a href="my_booking.php">⬅ Back to My Bookings</a>
    </div>
</body>
</html>
