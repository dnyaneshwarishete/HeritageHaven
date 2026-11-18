<?php
session_start();

require_once 'includes/db.php';

// Only logged-in tourists can access this page
if (
    !isset($_SESSION['user_id']) ||
    ($_SESSION['user_role'] ?? '') !== 'tourist'
) {
    header("Location: login.php");
    exit;
}

// Get the logged-in user's email from the users table
$user_id = (int) $_SESSION['user_id'];

$user_sql = "SELECT email FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_sql);

if (!$user_stmt) {
    die("Unable to load your account details.");
}

$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();
$user_stmt->close();

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit;
}

$user_email = $user['email'];

// Fetch only bookings matching the logged-in user's email
$sql = "SELECT id, destination, travel_date, guests,
               experience, booking_date
        FROM bookings
        WHERE email = ?
        ORDER BY booking_date DESC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Unable to load your bookings.");
}

$stmt->bind_param("s", $user_email);
$stmt->execute();
$bookings = $stmt->get_result();

function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Bookings | HeritageHaven</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f5f2;
            color: #222;
        }

        .page {
            max-width: 1100px;
            margin: auto;
            padding: 55px 20px;
        }

        .heading {
            text-align: center;
            margin-bottom: 35px;
        }

        .heading span {
            color: #e88945;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .heading h1 {
            font-size: 38px;
            margin: 12px 0;
        }

        .heading p {
            color: #666;
        }

        .top-links {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        .top-links a,
        .button {
            display: inline-block;
            padding: 12px 18px;
            border-radius: 9px;
            text-decoration: none;
            background: #171717;
            color: white;
        }

        .top-links a:hover,
        .button:hover {
            background: #e88945;
        }

        .booking-card {
            background: white;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
        }

        .booking-card h2 {
            margin-top: 0;
            color: #bd7137;
        }

        .booking-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .info-item {
            background: #faf8f5;
            padding: 13px;
            border-radius: 9px;
            overflow-wrap: anywhere;
        }

        .info-item strong {
            display: block;
            margin-bottom: 6px;
            color: #555;
            font-size: 13px;
        }

        .empty {
            text-align: center;
            background: white;
            padding: 45px 20px;
            border-radius: 16px;
        }

        .empty h2 {
            margin-top: 0;
        }

        @media (max-width: 600px) {
            .heading h1 {
                font-size: 30px;
            }

            .booking-info {
                grid-template-columns: 1fr;
            }

            .booking-card {
                padding: 18px;
            }
        }
    </style>
</head>

<body>

<main class="page">

    <div class="heading">
        <span>YOUR TRAVEL JOURNEY</span>
        <h1>My Bookings</h1>
        <p>View your heritage trips and booking details.</p>
    </div>

    <div class="top-links">
        <a href="tourist_dashboard.php">← Dashboard</a>
        <a href="booking.php">+ Plan a New Journey</a>
    </div>

    <?php if ($bookings->num_rows > 0): ?>

        <?php while ($booking = $bookings->fetch_assoc()): ?>

            <article class="booking-card">

                <h2><?= e($booking['destination']) ?></h2>

                <div class="booking-info">

                    <div class="info-item">
                        <strong>Booking ID</strong>
                        #<?= e($booking['id']) ?>
                    </div>

                    <div class="info-item">
                        <strong>Travel Date</strong>
                        <?= e(date('d M Y', strtotime($booking['travel_date']))) ?>
                    </div>

                    <div class="info-item">
                        <strong>Number of Guests</strong>
                        <?= e($booking['guests']) ?>
                    </div>

                    <div class="info-item">
                        <strong>Experience</strong>
                        <?= e($booking['experience']) ?>
                    </div>

                    <div class="info-item">
                        <strong>Booking Submitted</strong>
                        <?= e(date('d M Y, h:i A', strtotime($booking['booking_date']))) ?>
                    </div>

                    <div class="info-item">
                        <strong>Booking Status</strong>
                        Booking received
                    </div>

                </div>

            </article>

        <?php endwhile; ?>

    <?php else: ?>

        <div class="empty">
            <h2>No Bookings Yet</h2>
            <p>You haven't made any bookings using your account email yet.</p>
            <a href="heritage.php" class="button">Explore Heritage Sites</a>
        </div>

    <?php endif; ?>

</main>

</body>
</html>

<?php
$stmt->close();
?>
