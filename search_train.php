<?php
session_start();

// Check login
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "railway");
if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$show_results = false;
$no_results = false;
$from_station = "";
$to_station = "";
$travel_date = "";

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $from_station = mysqli_real_escape_string($conn, $_POST['from_station']);
    $to_station = mysqli_real_escape_string($conn, $_POST['to_station']);
    $travel_date = mysqli_real_escape_string($conn, $_POST['travel_date']);

    // ✅ seats column use karo
    $query = "SELECT * FROM trains 
              WHERE destination = '$to_station' 
              AND seats > 0
              ORDER BY departure_time";

    $result = mysqli_query($conn, $query);

    if(!$result) {
        die("SQL Error: " . mysqli_error($conn));
    }

    if(mysqli_num_rows($result) > 0) {
        $show_results = true;
        $search_results = $result;
    } else {
        $no_results = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Train Finder ✦ Railway</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz@14..32&display=swap" rel="stylesheet">
    <style>
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
            padding: 20px 20px 40px;
        }

        /* ─── GLASS NAVBAR ─── */
        .navbar {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto 20px;
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
        .nav-links a.active {
            color: #f6e9a6;
        }

        /* ─── CONTAINER ─── */
        .container {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
        }

        /* ─── HEADER ─── */
        .header {
            text-align: center;
            margin-bottom: 32px;
        }
        .header h1 {
            font-size: 40px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.5px;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }
        .header h1 .emoji {
            display: inline-block;
            animation: wiggle 4s infinite ease-in-out;
        }
        @keyframes wiggle {
            0%, 100% { transform: rotate(0deg); }
            5% { transform: rotate(12deg); }
            10% { transform: rotate(-8deg); }
            15% { transform: rotate(6deg); }
            20% { transform: rotate(0deg); }
        }
        .header p {
            color: rgba(255, 255, 255, 0.60);
            font-size: 18px;
            font-weight: 400;
            margin-top: 6px;
        }

        /* ─── SEARCH BOX (GLASS) ─── */
        .search-box {
            background: rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(22px);
            -webkit-backdrop-filter: blur(22px);
            border: 1px solid rgba(255, 255, 255, 0.10);
            border-radius: 28px;
            padding: 28px 32px;
            margin-bottom: 36px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.30);
            transition: 0.3s ease;
        }
        .search-box:hover {
            border-color: rgba(255, 255, 255, 0.16);
            box-shadow: 0 24px 70px rgba(0, 0, 0, 0.40);
        }
        .search-form {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            align-items: center;
        }
        .search-form input,
        .search-form button {
            padding: 14px 20px;
            border-radius: 16px;
            border: 1.5px solid rgba(255, 255, 255, 0.12);
            font-size: 16px;
            font-family: inherit;
            background: rgba(255, 255, 255, 0.06);
            color: #fff;
            transition: 0.3s ease;
            outline: none;
            flex: 1 1 160px;
        }
        .search-form input::placeholder {
            color: rgba(255, 255, 255, 0.35);
            font-weight: 400;
        }
        .search-form input:focus {
            border-color: #f6e9a6;
            background: rgba(255, 255, 255, 0.10);
            box-shadow: 0 0 0 4px rgba(246, 233, 166, 0.08);
        }
        .search-form input[type="date"] {
            color-scheme: dark;
            color: rgba(255, 255, 255, 0.85);
        }
        .search-form input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(0.8);
        }
        .search-form button {
            background: linear-gradient(135deg, #f6e9a6, #e8d070);
            color: #0b1a3a;
            font-weight: 600;
            border: none;
            cursor: pointer;
            box-shadow: 0 6px 24px rgba(246, 233, 166, 0.20);
            flex: 0 1 auto;
            padding: 14px 36px;
            letter-spacing: 0.3px;
            transition: 0.3s ease;
        }
        .search-form button:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 12px 40px rgba(246, 233, 166, 0.30);
        }
        .search-form button:active {
            transform: scale(0.97);
        }

        /* ─── TABLE WRAPPER ─── */
        .table-wrapper {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 28px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
            transition: 0.3s ease;
        }
        .table-wrapper:hover {
            border-color: rgba(255, 255, 255, 0.14);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: transparent;
            color: #fff;
        }
        table thead {
            background: rgba(255, 255, 255, 0.06);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        table th {
            padding: 18px 16px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.50);
            background: transparent;
        }
        table td {
            padding: 16px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            font-size: 15px;
            color: rgba(255, 255, 255, 0.85);
            vertical-align: middle;
        }
        table tbody tr {
            transition: 0.2s ease;
        }
        table tbody tr:hover {
            background: rgba(255, 255, 255, 0.06);
        }
        table tbody tr:last-child td {
            border-bottom: none;
        }

        .train-name {
            font-weight: 600;
            color: #fff;
        }
        .seats-badge {
            background: rgba(246, 233, 166, 0.12);
            padding: 2px 14px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 14px;
            color: #f6e9a6;
            display: inline-block;
        }

        /* ─── BOOK BUTTON ─── */
        .book-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #4caf50, #388e3c);
            color: #fff;
            padding: 8px 18px;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: 0.3s ease;
            border: none;
            box-shadow: 0 4px 16px rgba(76, 175, 80, 0.20);
        }
        .book-btn:hover {
            transform: translateY(-2px) scale(1.04);
            box-shadow: 0 8px 28px rgba(76, 175, 80, 0.35);
        }
        .book-btn:active {
            transform: scale(0.95);
        }

        /* ─── NO RESULTS ─── */
        .no-results {
            text-align: center;
            padding: 50px 30px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            border-radius: 28px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            color: #fff;
        }
        .no-results h2 {
            font-size: 28px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.70);
            margin-bottom: 8px;
        }
        .no-results p {
            color: rgba(255, 255, 255, 0.45);
            font-size: 16px;
        }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 768px) {
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
            .header h1 {
                font-size: 30px;
            }
            .search-box {
                padding: 20px 18px;
                border-radius: 20px;
            }
            .search-form input,
            .search-form button {
                flex: 1 1 100%;
            }
            .search-form button {
                padding: 14px 20px;
            }
            table {
                font-size: 13px;
            }
            table th,
            table td {
                padding: 12px 10px;
            }
            .book-btn {
                padding: 6px 14px;
                font-size: 12px;
            }
            .seats-badge {
                font-size: 12px;
                padding: 1px 10px;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 24px;
            }
            .header p {
                font-size: 14px;
            }
            .navbar h1 {
                font-size: 17px;
            }
            .nav-links a {
                font-size: 12px;
                margin: 0 8px;
            }
            table th,
            table td {
                padding: 8px 6px;
                font-size: 11px;
            }
            .train-name {
                font-size: 12px;
            }
            .book-btn {
                font-size: 11px;
                padding: 4px 10px;
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

        .table-wrapper {
            animation: fadeSlide 0.7s ease forwards;
        }
        @keyframes fadeSlide {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
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

    <!-- ─── NAVBAR ─── -->
    <div class="navbar">
        <h1>🚆 Railway<span>Reservation</span></h1>
        <div class="nav-links">
            <a href="index.php">🏠 Home</a>
            <a href="search_train.php" class="active">🔍 Search</a>
        </div>
    </div>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1><span class="emoji">🚆</span> Train Finder</h1>
            <p>Find all trains for your destination</p>
        </div>

        <!-- Search Box -->
        <div class="search-box">
            <form method="POST" class="search-form">
                <input type="text" name="from_station" placeholder="From Station" value="<?php echo htmlspecialchars($from_station); ?>" required>
                <input type="text" name="to_station" placeholder="To Station" value="<?php echo htmlspecialchars($to_station); ?>" required>
                <input type="date" name="travel_date" value="<?php echo htmlspecialchars($travel_date); ?>" required>
                <button type="submit" name="search">🔍 Search</button>
            </form>
        </div>

        <!-- Results -->
        <?php if($show_results): ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Train Name</th>
                            <th>Source</th>
                            <th>Destination</th>
                            <th>Departure</th>
                            <th>Arrival</th>
                            <th>Seats</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($search_results)): ?>
                            <tr>
                                <td><span class="train-name"><?php echo htmlspecialchars($row['train_name']); ?></span></td>
                                <td><?php echo htmlspecialchars($row['source']); ?></td>
                                <td><?php echo htmlspecialchars($row['destination']); ?></td>
                                <td><?php echo date('h:i A', strtotime($row['departure_time'])); ?></td>
                                <td><?php echo date('h:i A', strtotime($row['arrival_time'])); ?></td>
                                <td><span class="seats-badge"><?php echo $row['seats']; ?></span></td>
                                <td><a href="book_ticket.php?train_id=<?php echo $row['id']; ?>" class="book-btn">🎟 Book</a></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif($no_results): ?>
            <div class="no-results">
                <h2>🚫 No Trains Found!</h2>
                <p>Try different stations or dates.</p>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
<?php mysqli_close($conn); ?>